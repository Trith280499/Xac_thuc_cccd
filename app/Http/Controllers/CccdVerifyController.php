<?php
namespace App\Http\Controllers;

use App\Models\XetDuyet;
use App\Models\LoaiTaiKhoan;
use App\Models\SinhVien;
use App\Models\TaiKhoan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CccdVerifyController extends Controller
{
    public function checkCccdStatus(Request $request)
    {
        try {
            $cccd = $request->input('cccd');

            // Kiểm tra xem CCCD đã tồn tại trong bảng xet_duyet chưa
            $xet_duyet = XetDuyet::where('cccd_input', $cccd)->first();

            if ($xet_duyet) {
                return response()->json([
                    'exists' => true,
                    'status' => $xet_duyet->trang_thai,
                    'data' => [
                        'mssv' => $xet_duyet->mssv_input,
                        'cccd' => $xet_duyet->cccd_input,
                        'reason' => $xet_duyet->ghi_chu
                    ]
                ]);
            } else {
                return response()->json([
                    'exists' => false
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'exists' => false,
                'error' => 'Lỗi khi kiểm tra trạng thái CCCD: ' . $e->getMessage()
            ], 500);
        }
    }


public function getAllLoaiTK(Request $request)
{
    try {
        // Luôn truy vấn DB, không dùng session
        $activeAccounts = LoaiTaiKhoan::where('trang_thai', 'active')
            ->select('id', 'ten_loai', 'mo_ta', 'trang_thai')
            ->get();

        if ($activeAccounts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy loại tài khoản nào có trạng thái active.'
            ], 404);
        }

        //
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách loại tài khoản thành công.',
            'data' => $activeAccounts
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi lấy danh sách loại tài khoản: ' . $e->getMessage()
        ], 500);
    }
}


    private function safeParseDate($dateString)
    {
        if (empty($dateString)) return null;

        $dateString = trim($dateString);

        // Chuẩn hóa tất cả dấu ngăn cách về dạng "/"
        $normalized = str_replace(['-', '.', ' '], '/', $dateString);

        try {
            // Nếu là dạng dd/mm/yyyy
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $normalized)) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $normalized)->format('Y-m-d');
            }

            // Nếu là dạng yyyy-mm-dd (đã chuẩn)
            if (preg_match('/^\d{4}\/\d{1,2}\/\d{1,2}$/', $normalized)) {
                return \Carbon\Carbon::createFromFormat('Y/m/d', $normalized)->format('Y-m-d');
            }

            // Thử auto parse nếu không khớp mẫu nào
            return \Carbon\Carbon::parse($normalized)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Date parse failed for: '.$dateString.' | '.$e->getMessage());
            return null;
        }
    }

        public function submitApproval(Request $request)
    {
        try {
            $mssv = $request->input('mssv');
            $cccd = $request->input('cccd');
            $imageUrl = $request->input('imageUrl');

            if (empty($mssv)) {
                return response()->json([
                    'success' => false,
                    'message' => 'MSSV không được để trống.'
                ], 400);
            }

            // Kiểm tra xem CCCD đã được gửi yêu cầu trước chưa
            $existingRecord = XetDuyet::where('cccd_input', $cccd)->first();
            if ($existingRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số CCCD này đã được gửi yêu cầu xét duyệt trước đó.'
                ], 400);
            }

            //  Lấy dữ liệu OCR đã lưu trong session
            $sessionData = session('extracted_cccd');

            if (!$sessionData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy dữ liệu CCCD trong session. Vui lòng xác thực lại.'
                ], 400);
            }

            // Tạo bản ghi xét duyệt mới từ thông tin session
            XetDuyet::create([
                'mssv_input'          => $mssv,
                'cccd_input'          => $cccd,
                'trang_thai'          => 'pending',
                'anh_cccd'            => $imageUrl ?? $sessionData['anh_cccd'] ?? null,
                'ghi_chu'             =>null,

                // Dữ liệu CCCD trích xuất (theo migration)
                'so_cccd'             => $sessionData['so_cccd'] ?? null,
                'ho_ten'              => $sessionData['ho_ten'] ?? null,
                'ngay_sinh'     => $this->safeParseDate($sessionData['ngay_sinh'] ?? null),

                'gioi_tinh'           => $sessionData['gioi_tinh'] ?? null,
                'quoc_tich'           => $sessionData['quoc_tich'] ?? 'Việt Nam',
                'que_quan'            => $sessionData['que_quan'] ?? null,
                'noi_thuong_tru'      => $sessionData['noi_thuong_tru'] ?? null,
                'ngay_het_han'  => $this->safeParseDate($sessionData['ngay_het_han'] ?? null),
                'trang_thai_cccd'     => 'active',
            ]);

            // Xóa session sau khi lưu để tránh duplicate
            session()->forget('extracted_cccd');

            return response()->json([
                'success' => true,
                'message' => 'Đã gửi yêu cầu xét duyệt thành công. Vui lòng chờ quản trị viên duyệt.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gửi yêu cầu: ' . $e->getMessage()
            ], 500);
        }
    }


    // Lấy tất cả các yêu cầu xét duyệt
    public function getAllApprovals()
    {
        try {
            $approvals = XetDuyet::all();
            return response()->json($approvals);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Lỗi khi lấy danh sách xét duyệt: ' . $e->getMessage()
            ], 500);
        }
    }


    // Cập nhật trạng thái xét duyệt
   public function updateApprovalStatus(Request $request)
{
    try {
        $id = $request->input('id');
        $newStatus = $request->input('status');
        $reason = $request->input('reason', '');
        $accounts = $request->input('accounts', []); // ✅ mảng tài khoản từ UI

        $approval = XetDuyet::find($id);
        if (!$approval) {
            return response()->json([
                'success' => false,
                'message' => 'Yêu cầu xét duyệt không tồn tại.'
            ], 404);
        }

        // ✅ Lấy sinh viên_id tương ứng từ mssv_input
        $student = SinhVien::where('mssv', $approval->mssv_input)->first();
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sinh viên có MSSV: ' . $approval->mssv_input
            ], 404);
        }

        // ✅ Nếu trạng thái là approved → lưu vào bảng tai_khoan
        if ($newStatus === 'approved' && !empty($accounts)) {
            foreach ($accounts as $acc) {
                TaiKhoan::create([
                    'ten_tai_khoan' => $acc['ten_tai_khoan'],
                    'loai_tai_khoan_id' => $acc['id'],
                    'sinh_vien_id' => $student->id,
                    'ngay_reset' => null,
                    'trang_thai' => 'active'
                ]);
            }
        }

        // ✅ Cập nhật trạng thái xét duyệt
        $approval->trang_thai = $newStatus;
        $approval->ghi_chu = $newStatus === 'rejected' ? $reason : '';
        $approval->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái xét duyệt thành công.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
        ], 500);
    }
}

}