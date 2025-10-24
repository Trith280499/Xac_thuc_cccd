<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\SinhVien;
use App\Models\CanCuocCongDan;
use App\Models\TaiKhoanVLE;
use App\Models\TaiKhoanEdu;
use App\Models\TaiKhoanMSTeam;

define('_SV', 'sinh_vien');
define('_CCCD', 'can_cuoc_cong_dan');
define('_VLE', 'tai_khoan_vle');
define('_EDU', 'tai_khoan_edu');   
define('_MSTEAM', 'tai_khoan_ms_team');
define('_LICH_SU_RESET', 'lich_su_reset');

class ResetPassController extends Controller
{
    private function generateSecurePassword($length = 8) {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $special = '!@#$%^&*';
        
        $all = $lowercase . $uppercase . $numbers . $special;
        $password = '';
        
        // Ensure at least one character from each set
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $special[random_int(0, strlen($special) - 1)];
        
        // Fill remaining length with random characters
        for ($i = strlen($password); $i < $length; $i++) {
            $password .= $all[random_int(0, strlen($all) - 1)];
        }
        
        // Shuffle the password
        return str_shuffle($password);
    }

    /**
     * Kiểm tra xem tài khoản có thể reset hay không (sau 3 ngày)
     */
    private function canResetAccount($username, $accountType)
    {
        // Lấy lần reset gần nhất từ bảng lịch sử
        $lastReset = DB::table(_LICH_SU_RESET)
            ->where('tai_khoan', $username)
            ->where('loai_tai_khoan', $accountType)
            ->orderBy('thoi_gian_reset', 'desc')
            ->first();

        // Nếu chưa từng reset thì cho phép
        if (!$lastReset) {
            return [
                'can_reset' => true,
                'message' => 'Có thể reset'
            ];
        }

        $lastResetTime = \Carbon\Carbon::parse($lastReset->thoi_gian_reset);
        $now = \Carbon\Carbon::now();
        $daysSinceLastReset = $lastResetTime->diffInDays($now);

        // Kiểm tra nếu chưa đủ 3 ngày
        if ($daysSinceLastReset < 3) {
            $remainingDays = 3 - $daysSinceLastReset;
            $nextResetDate = $lastResetTime->addDays(3)->format('d/m/Y H:i:s');
            
            return [
                'can_reset' => false,
                'message' => "Tài khoản này đã được reset gần đây. Chỉ có thể reset lại sau 3 ngày. Bạn có thể reset lại vào: {$nextResetDate}",
                'next_reset_date' => $nextResetDate,
                'remaining_days' => $remainingDays
            ];
        }

        return [
            'can_reset' => true,
            'message' => 'Có thể reset'
        ];
    }

    public function handleReset(Request $request)
    {
        $accountType = $request->input('type');
        $username = $request->input('username');
        
        // Kiểm tra điều kiện reset
        $checkResult = $this->canResetAccount($username, $accountType);
        
        if (!$checkResult['can_reset']) {
            return response()->json([
                'success' => false,
                'message' => $checkResult['message'],
                'next_reset_date' => $checkResult['next_reset_date'] ?? null,
                'remaining_days' => $checkResult['remaining_days'] ?? null
            ], 400);
        }
        
        $newPassword = $this->generateSecurePassword();
        
        DB::beginTransaction();
        
        try {
            switch($accountType) {
                case 'Teams':
                    DB::table(_EDU)
                        ->where('tai_khoan', $username)
                        ->update([
                            'mat_khau' => $newPassword,
                            'ngay_reset' => now()
                        ]);
                    break;
                case 'VLE': 
                    DB::table(_VLE)
                        ->where('tai_khoan', $username)
                        ->update([
                            'mat_khau' => $newPassword,
                            'ngay_reset' => now()
                        ]);
                    break;
                case 'Portal':
                    DB::table(_MSTEAM)
                        ->where('tai_khoan', $username)
                        ->update([
                            'mat_khau' => $newPassword,
                            'ngay_reset' => now()
                        ]);
                    break;
            }

            DB::table(_LICH_SU_RESET)->insert([
                'tai_khoan' => $username,
                'loai_tai_khoan' => $accountType,
                'mat_khau_moi' => $newPassword,
                'thoi_gian_reset' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Lưu thông tin reset mới nhất vào session
            $recentReset = [
                'username' => $username,
                'type' => $accountType,
                'new_password' => $newPassword,
                'reset_time' => now()
            ];
            
            // Lấy danh sách reset gần đây từ session hoặc tạo mới
            $recentResets = session('recent_resets', []);
            array_unshift($recentResets, $recentReset); // Thêm vào đầu mảng
            // Giới hạn chỉ lưu 10 reset gần nhất
            $recentResets = array_slice($recentResets, 0, 10);
            session(['recent_resets' => $recentResets]);
                
            DB::commit();

            return response()->json([
                'success' => true,
                'username' => $username,
                'password' => $newPassword,
                'type' => $accountType,
                'message' => 'Reset mật khẩu thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi reset mật khẩu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Hàm lấy lịch sử reset
    public function getLichSuReset($username = null)
    {
        $query = DB::table(_LICH_SU_RESET)
            ->orderBy('thoi_gian_reset', 'desc');

        if ($username) {
            $query->where('tai_khoan', $username);
        }

        return $query->get();
    }

    /**
     * API kiểm tra trạng thái reset của tài khoản
     */
    public function checkResetStatus(Request $request)
    {
        $username = $request->input('username');
        $accountType = $request->input('type');

        $checkResult = $this->canResetAccount($username, $accountType);

        return response()->json($checkResult);
    }
}