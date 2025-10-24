<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TaiKhoan;
use App\Models\LoaiTaiKhoan;
use App\Models\LichSuReset;
use App\Models\SinhVien;

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
    public function canResetAccount($username, $type)
    {
        // Tìm tài khoản dựa trên username và type
        $taiKhoan = TaiKhoan::where('ten_tai_khoan', $username)->first();
        
        if (!$taiKhoan) {
            return [
                'can_reset' => false,
                'message' => 'Tài khoản không tồn tại'
            ];
        }

        // Lấy lần reset gần nhất từ bảng lịch sử
        $lastReset = LichSuReset::where('tai_khoan', $username)
            ->where('loai_tai_khoan', $type)
            ->orderBy('thoi_gian_reset', 'desc')
            ->first();

        // Nếu chưa từng reset thì cho phép
        if (!$lastReset) {
            return [
                'can_reset' => true,
                'message' => 'Có thể reset',
                'tai_khoan' => $taiKhoan
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
                'remaining_days' => $remainingDays,
                'tai_khoan' => $taiKhoan
            ];
        }

        return [
            'can_reset' => true,
            'message' => 'Có thể reset',
            'tai_khoan' => $taiKhoan
        ];
    }

    /**
     * API kiểm tra trạng thái reset của tài khoản
     */
    public function checkResetStatus(Request $request)
    {
        $username = $request->input('username');
        $type = $request->input('type');

        if (!$username || !$type) {
            return response()->json([
                'can_reset' => false,
                'message' => 'Thiếu thông tin username hoặc type'
            ], 400);
        }

        $checkResult = $this->canResetAccount($username, $type);

        return response()->json($checkResult);
    }

    /**
     * Xử lý reset mật khẩu
     */
    public function handleReset(Request $request)
    {
        $username = $request->input('username');
        $type = $request->input('type');

        if (!$username || !$type) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin username hoặc type'
            ], 400);
        }

        // Lấy số CCCD từ session
        $cccdNumber = session('cccd_number');
        
        if (!$cccdNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa xác thực CCCD'
            ], 401);
        }

        // Kiểm tra điều kiện reset
        $checkResult = $this->canResetAccount($username, $type);
        
        if (!$checkResult['can_reset']) {
            return response()->json([
                'success' => false,
                'message' => $checkResult['message'],
                'next_reset_date' => $checkResult['next_reset_date'] ?? null,
                'remaining_days' => $checkResult['remaining_days'] ?? null
            ], 400);
        }
        
        $taiKhoan = $checkResult['tai_khoan'];
        $newPassword = $this->generateSecurePassword();
        
        DB::beginTransaction();
        
        try {
            // Cập nhật mật khẩu trong bảng tai_khoan
            $taiKhoan->update([
                'mat_khau' => $newPassword,
                'ngay_reset' => now(),
                'updated_at' => now()
            ]);

            // Ghi lịch sử reset
            LichSuReset::create([
                'so_cccd' => $cccdNumber,
                'tai_khoan' => $username,
                'loai_tai_khoan' => $type,
                'mat_khau_moi' => $newPassword,
                'thoi_gian_reset' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'username' => $username,
                'password' => $newPassword,
                'type' => $type,
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

    /**
     * Lấy lịch sử reset theo CCCD (API)
     */
    public function getResetHistory(Request $request)
    {
        $cccdNumber = session('cccd_number');
        
        if (!$cccdNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa xác thực CCCD'
            ], 401);
        }

        $history = LichSuReset::where('so_cccd', $cccdNumber)
            ->orderBy('thoi_gian_reset', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Reset nhiều tài khoản cùng lúc
     */
    public function handleBulkReset(Request $request)
    {
        $taiKhoanIds = $request->input('username', []);
        $type = $request->input('type');
        
        if (empty($taiKhoanIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có tài khoản nào được chọn'
            ], 400);
        }

        $results = [];
        $successCount = 0;
        $errorCount = 0;

        foreach ($taiKhoanIds as $taiKhoanId) {
            $checkResult = $this->canResetAccount($taiKhoanId, $type);
            
            if (!$checkResult['can_reset']) {
                $results[] = [
                    'tai_khoan_id' => $taiKhoanId,
                    'success' => false,
                    'message' => $checkResult['message']
                ];
                $errorCount++;
                continue;
            }

            $taiKhoan = $checkResult['tai_khoan'];
            $newPassword = $this->generateSecurePassword();

            DB::beginTransaction();
            
            try {
                // Cập nhật mật khẩu
                $taiKhoan->update([
                    'mat_khau' => $newPassword,
                    'ngay_reset' => now(),
                    'updated_at' => now()
                ]);

                // Ghi lịch sử reset
                LichSuReset::create([
                    'tai_khoan_id' => $taiKhoanId,
                    'mat_khau_moi' => $newPassword,
                    'thoi_gian_reset' => now()
                ]);

                $results[] = [
                    'tai_khoan_id' => $taiKhoanId,
                    'success' => true,
                    'username' => $taiKhoan->ten_tai_khoan,
                    'type' => $taiKhoan->loaiTaiKhoan->ten_loai,
                    'password' => $newPassword,
                    'message' => 'Reset thành công'
                ];
                $successCount++;

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                
                $results[] = [
                    'tai_khoan_id' => $taiKhoanId,
                    'success' => false,
                    'message' => 'Lỗi: ' . $e->getMessage()
                ];
                $errorCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Đã reset {$successCount} tài khoản thành công, {$errorCount} tài khoản thất bại",
            'results' => $results,
            'success_count' => $successCount,
            'error_count' => $errorCount
        ]);
    }

    // Hàm lấy lịch sử reset
    public function getLichSuReset($taiKhoanId = null)
    {
        $query = LichSuReset::with('taiKhoan.loaiTaiKhoan')
            ->orderBy('thoi_gian_reset', 'desc');

        if ($taiKhoanId) {
            $query->where('tai_khoan_id', $taiKhoanId);
        }

        return $query->get();
    }

    /**
     * Lấy danh sách tài khoản của sinh viên đã xác thực
     */
    public function getStudentAccounts(Request $request)
    {
        if (!session('cccd_authenticated')) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa xác thực CCCD'
            ], 401);
        }

        $studentData = session('student_data');
        $sinhVien = $studentData['sv'];

        $taiKhoans = TaiKhoan::with('loaiTaiKhoan')
            ->where('sinh_vien_id', $sinhVien->id)
            ->get()
            ->map(function ($taiKhoan) {
                $checkResult = $this->canResetAccount($taiKhoan->id, $taiKhoan->loaiTaiKhoan->ten_loai);
                
                return [
                    'id' => $taiKhoan->id,
                    'ten_tai_khoan' => $taiKhoan->ten_tai_khoan,
                    'loai_tai_khoan' => $taiKhoan->loaiTaiKhoan->ten_loai,
                    'mo_ta' => $taiKhoan->loaiTaiKhoan->mo_ta,
                    'ngay_reset' => $taiKhoan->ngay_reset,
                    'trang_thai' => $taiKhoan->trang_thai,
                    'can_reset' => $checkResult['can_reset'],
                    'reset_message' => $checkResult['message'],
                    'next_reset_date' => $checkResult['next_reset_date'] ?? null
                ];
            });

        return response()->json([
            'success' => true,
            'tai_khoans' => $taiKhoans
        ]);
    }
}