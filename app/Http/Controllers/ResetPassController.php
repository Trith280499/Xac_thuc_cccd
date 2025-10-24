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
    private function canResetAccount($taiKhoanId)
    {
        // Lấy tài khoản và thông tin loại tài khoản
        $taiKhoan = TaiKhoan::with('loaiTaiKhoan')->find($taiKhoanId);
        
        if (!$taiKhoan) {
            return [
                'can_reset' => false,
                'message' => 'Tài khoản không tồn tại'
            ];
        }

        // Lấy lần reset gần nhất từ bảng lịch sử
        $lastReset = LichSuReset::where('tai_khoan_id', $taiKhoanId)
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

    public function handleReset(Request $request)
    {
        $taiKhoanId = $request->input('tai_khoan_id');
        
        // Kiểm tra điều kiện reset
        $checkResult = $this->canResetAccount($taiKhoanId);
        
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
                'tai_khoan_id' => $taiKhoanId,
                'mat_khau_moi' => $newPassword,
                'thoi_gian_reset' => now()
            ]);

            // Lưu thông tin reset mới nhất vào session
            $recentReset = [
                'tai_khoan_id' => $taiKhoanId,
                'username' => $taiKhoan->ten_tai_khoan,
                'type' => $taiKhoan->loaiTaiKhoan->ten_loai,
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
                'tai_khoan_id' => $taiKhoanId,
                'username' => $taiKhoan->ten_tai_khoan,
                'password' => $newPassword,
                'type' => $taiKhoan->loaiTaiKhoan->ten_loai,
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
     * Reset nhiều tài khoản cùng lúc
     */
    public function handleBulkReset(Request $request)
    {
        $taiKhoanIds = $request->input('tai_khoan_ids', []);
        
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
            $checkResult = $this->canResetAccount($taiKhoanId);
            
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
     * API kiểm tra trạng thái reset của tài khoản
     */
    public function checkResetStatus(Request $request)
    {
        $taiKhoanId = $request->input('tai_khoan_id');

        $checkResult = $this->canResetAccount($taiKhoanId);

        return response()->json($checkResult);
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
                $checkResult = $this->canResetAccount($taiKhoan->id);
                
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