<?php

namespace App\Http\Controllers;

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

        // Ở bước này, sau khi tích hợp API của Anh Mẫn  kiểm tra CCCD và gọi API reset
        // Tạm thời mình demo kết quả trả về:
        return back()->with('success', 'Yêu cầu reset mật khẩu đã được gửi thành công!');
    }
    public function getInfo(Request $request)
    {
        $cccd = $request->input('cccd');
        $mssv = $request->input('mssv');
        $hoten = $request->input('hoten');

        // Kiểm tra đầu vào
        if (!$cccd && !$mssv) {
            return back()->with('error', 'Vui lòng nhập MSSV hoặc số CCCD để tra cứu.');
        }

        // Lấy thông tin sinh viên
        $sv = SinhVien::where('mssv', $mssv)
            ->orWhere('ho_ten', 'LIKE', "%$hoten%")
            ->first();

        if (!$sv) {
            return back()->with('error', 'Không tìm thấy sinh viên phù hợp.');
        }

        // Lấy thông tin CCCD
        $cccd_info = CanCuocCongDan::where('cccd', $cccd)
            ->orWhere('sinh_vien_id', $sv->id ?? null)
            ->first();

        // Lấy thông tin các tài khoản
        $vle = TaiKhoanVLE::where('sinh_vien_id', $sv->id)->first();
        $edu = TaiKhoanEdu::where('sinh_vien_id', $sv->id)->first();
        $msteam = TaiKhoanMSTeam::where('sinh_vien_id', $sv->id)->first();

        // Gom tất cả dữ liệu lại để truyền sang view
        $data = [
            'sv' => $sv,
            'cccd' => $cccd_info,
            'vle' => $vle,
            'edu' => $edu,
            'msteam' => $msteam
        ];

        // Trả về view form2 với dữ liệu điền sẵn
        return view('form2', $data);
    }

}
