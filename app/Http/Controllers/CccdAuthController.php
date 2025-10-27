<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\SinhVien;
use App\Models\User;
use App\Models\TaiKhoan;

class CccdAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            $encoded_img = $request->input('image_base64');

            if (!$encoded_img) {
                return response()->json(['status' => 'error', 'message' => 'Thiếu dữ liệu ảnh'], 400);
            }

            // Decode base64 -> binary
            if (preg_match('/^data:image\/(\w+);base64,/', $encoded_img, $type)) {
                $encoded_img = substr($encoded_img, strpos($encoded_img, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($encoded_img);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Ảnh không hợp lệ'], 400);
            }

            // Lưu ảnh vào storage
            $fileName = 'cccd_' . Str::random(20) . '.' . $type;
            $filePath = 'cccd_images/' . $fileName;
            
            // Lưu file vào storage (public disk)
            Storage::disk('public')->put($filePath, $imageData);
            
            // Lấy URL công khai
            $imageUrl = Storage::url($filePath);

            // Call OCR API
            $ocrResponse = Http::withoutVerifying()->withHeaders([
                'x-api-key' => '5dd1b197-c051-42c9-8f3f-4accd12335ex',
            ])->attach(
                'myfile', $imageData, 'cccd_image.' . $type
            )->post('https://ocr.hcmue.edu.vn/extract');

            if (!$ocrResponse->ok()) {
                // Xóa ảnh đã lưu nếu OCR fail
                Storage::disk('public')->delete($filePath);
                return response()->json([
                    'status' => 'error',
                    'message' => 'OCR API lỗi: ' . $ocrResponse->status()
                ], 500);
            }

            $ocrData = $ocrResponse->json();

            if (empty($ocrData['id'])) {
                // Xóa ảnh đã lưu nếu OCR không nhận dạng được
                Storage::disk('public')->delete($filePath);
                return response()->json([
                    'status' => 'error',
                    'message' => 'OCR không nhận dạng được CCCD'
                ], 422);
            }

            // Use OCR result as input
            $cccdText = $ocrData['id'];

            // Query database với cấu trúc mới
            $student = SinhVien::with(['taiKhoans.loaiTaiKhoan'])
                ->where('so_cccd', $cccdText)
                ->first();

           if (!$student) {
            session([
                'cccd_authenticated' => false, // chưa xác thực sinh viên
                'cccd_number' => $cccdText,
            ]);

            return response()->json([
                'status' => 'warning',
                'message' => 'Không tìm thấy sinh viên trong DB',
                'ocr_data' => $ocrData,
                'image_url' => $imageUrl
            ], 200);
        }

            // Cập nhật ảnh CCCD
            $student->update([
                'anh_cccd' => $imageUrl,
                'updated_at' => now()
            ]);

            // Lấy thông tin tài khoản theo loại
            $taiKhoans = $student->taiKhoans;
            $eduAccounts = $taiKhoans->where('loaiTaiKhoan.ten_loai', 'EDU');
            $vleAccounts = $taiKhoans->where('loaiTaiKhoan.ten_loai', 'VLE');
            $msteamAccounts = $taiKhoans->where('loaiTaiKhoan.ten_loai', 'MS_TEAM');

            // Lấy thông tin user
            $user = User::where('mssv', $student->mssv)->first();
            
            // Lưu thông tin vào session
            session([
                'cccd_authenticated' => true,
                'cccd_number' => $cccdText,
                'student_data' => [
                    'sv' => $student,
                    'user' => $user,
                    'eduAccounts' => $eduAccounts,
                    'vleAccounts' => $vleAccounts,
                    'msteamAccounts' => $msteamAccounts,
                    'image_url' => $imageUrl, 
                    'ocrData' => $ocrData
                ]
            ]);

            // Redirect to Form 2
            return redirect()->route('form2.view');

        } catch (\Exception $e) {
            // Xóa ảnh đã lưu nếu có lỗi
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    // Thêm method đăng xuất
    public function logout()
    {
        // Có thể xóa ảnh khi logout nếu cần
        session()->forget(['cccd_authenticated', 'cccd_number', 'student_data']);
        return redirect('/');
    }

    public function showForm2(Request $request)
    {
        if (!session('cccd_authenticated')) {
            return redirect('/');
        }

        $data = session('student_data', []);
        $cccdNumber = session('cccd_number');

        // Lấy lịch sử reset theo số CCCD
        $lichSuReset = DB::table('lich_su_reset')
            ->where('so_cccd', $cccdNumber)
            ->orderBy('thoi_gian_reset', 'desc')
            ->get();

        return view('form2', [
            'sv' => $data['sv'] ?? null,
            'user' => $data['user'] ?? null,
            'cccdData' => $data['cccdData'] ?? null,
            'image_url' => $data['image_url'] ?? null,
            'eduAccounts' => $data['eduAccounts'] ?? collect(),
            'vleAccounts' => $data['vleAccounts'] ?? collect(),
            'msteamAccounts' => $data['msteamAccounts'] ?? collect(),
            'lichSuReset' => $lichSuReset
        ]);
    }

    // Method để reset mật khẩu
    public function resetPassword(Request $request)
    {
        try {
            if (!session('cccd_authenticated')) {
                return response()->json(['status' => 'error', 'message' => 'Chưa xác thực CCCD'], 401);
            }

            $taiKhoanId = $request->input('tai_khoan_id');
            $matKhauMoi = $request->input('mat_khau_moi');

            $taiKhoan = TaiKhoan::find($taiKhoanId);
            
            if (!$taiKhoan) {
                return response()->json(['status' => 'error', 'message' => 'Tài khoản không tồn tại'], 404);
            }

            // Kiểm tra xem tài khoản có thuộc về sinh viên đã xác thực không
            $studentData = session('student_data');
            if ($taiKhoan->sinh_vien_id !== $studentData['sv']->id) {
                return response()->json(['status' => 'error', 'message' => 'Tài khoản không thuộc về sinh viên này'], 403);
            }

            // Cập nhật mật khẩu
            $taiKhoan->update([
                'mat_khau' => $matKhauMoi,
                'ngay_reset' => now(),
                'updated_at' => now()
            ]);

            // Ghi lịch sử reset
            DB::table('lich_su_reset')->insert([
                'tai_khoan_id' => $taiKhoan->id,
                'mat_khau_moi' => $matKhauMoi,
                'thoi_gian_reset' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Reset mật khẩu thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi reset mật khẩu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method để xử lý xét duyệt thủ công
    public function manualApproval(Request $request)
    {
        try {
            $mssv = $request->input('mssv');
            $cccd = $request->input('cccd');

            // Tìm sinh viên theo MSSV và CCCD
            $student = SinhVien::with(['taiKhoans.loaiTaiKhoan'])
                ->where('mssv', $mssv)
                ->where('so_cccd', $cccd)
                ->first();

            if (!$student) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy sinh viên với MSSV và CCCD khớp'
                ], 404);
            }

            // Lấy thông tin tài khoản
            $taiKhoans = $student->taiKhoans;
            $eduAccounts = $taiKhoans->where('loaiTaiKhoan.ten_loai', 'EDU');
            $vleAccounts = $taiKhoans->where('loaiTaiKhoan.ten_loai', 'VLE');
            $msteamAccounts = $taiKhoans->where('loaiTaiKhoan.ten_loai', 'MS_TEAM');

            // Lấy thông tin user
            $user = User::where('mssv', $student->mssv)->first();

            // Lưu thông tin vào session
            session([
                'cccd_authenticated' => true,
                'cccd_number' => $cccd,
                'student_data' => [
                    'sv' => $student,
                    'user' => $user,
                    'eduAccounts' => $eduAccounts,
                    'vleAccounts' => $vleAccounts,
                    'msteamAccounts' => $msteamAccounts,
                    'image_url' => $student->anh_cccd ?? null,
                    'manual_approval' => true
                ]
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Xác thực thủ công thành công',
                'redirect' => route('form2.view')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xác thực thủ công: ' . $e->getMessage()
            ], 500);
        }
    }

    // Thêm method để xóa ảnh cũ nếu cần
    public function cleanupOldImages()
    {
        // Có thể thêm logic xóa ảnh cũ sau 24h nếu cần
        $files = Storage::disk('public')->files('cccd_images');
        $now = now();
        
        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            if ($now->diffInHours(\Carbon\Carbon::createFromTimestamp($lastModified)) > 24) {
                Storage::disk('public')->delete($file);
            }
        }
    }
}