<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

class ResetPassController extends Controller
{
    public function handleReset(Request $request)
    {
        // Xử lý ảnh CCCD (demo)
        if ($request->hasFile('cccd')) {
            $path = $request->file('cccd')->store('uploads', 'public');
        }

        // Ở bước này, sau khi tích hợp API của Anh Mẫn kiểm tra CCCD và gọi API reset
        // Tạm thời mình demo kết quả trả về:
        return back()->with('success', 'Yêu cầu reset mật khẩu đã được gửi thành công!');
    }

    public function getInfo(Request $request): JsonResponse
    {
        $cccd = $request->input('cccd');
        $mssv = $request->input('mssv');

        // Kiểm tra đầu vào
        if (!$cccd && !$mssv) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập MSSV, số CCCD hoặc họ tên để tra cứu.'
            ], 400);
        }

        try {
            // Xây dựng query tìm sinh viên
            $query = SinhVien::query();
            
            if ($mssv) {
                $query->where('mssv', $mssv);
            } elseif ($cccd) {
                // Tìm sinh viên thông qua CCCD
                $cccdInfo = CanCuocCongDan::where('cccd', $cccd)->first();
                if ($cccdInfo) {
                    $query->where('id', $cccdInfo->sinh_vien_id);
                } else {
                    // Nếu không tìm thấy CCCD, trả về lỗi
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy thông tin CCCD.'
                    ], 404);
                }
            }

            $sv = $query->first();

            if (!$sv) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sinh viên phù hợp.'
                ], 404);
            }

            // Lấy thông tin CCCD
            $cccd_info = CanCuocCongDan::where('sinh_vien_id', $sv->id)->first();

            // Lấy thông tin các tài khoản
            $vle = TaiKhoanVLE::where('sinh_vien_id', $sv->id)->first();
            $edu = TaiKhoanEdu::where('sinh_vien_id', $sv->id)->first();
            $msteam = TaiKhoanMSTeam::where('sinh_vien_id', $sv->id)->first();

            // Trả về JSON response
            return response()->json([
                'success' => true,
                'data' => [
                    'sinh_vien' => $sv,
                    'can_cuoc_cong_dan' => $cccd_info,
                    'tai_khoan_vle' => $vle,
                    'tai_khoan_edu' => $edu,
                    'tai_khoan_ms_team' => $msteam
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra trong quá trình xử lý.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}