<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CccdAuthController extends Controller
{
    //giả lập function xác thực cccd, trả về cccd từ hình ảnh
    public function authenticate(Request $request)
    {
        $imageUrl = $request->input('image_url');

        if (!$imageUrl) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không nhận được đường dẫn ảnh CCCD (image_url).'
            ], 400);
        }

        $isAuthorized = true;

        if (stripos($imageUrl, 'valid') !== false || stripos($imageUrl, 'approved') !== false) {
            $isAuthorized = false;
        }

        $text = '001123456789'; // giả lập cccd trả về

        if ($isAuthorized) {
            return response()->json([
                'status' => 'success',
                // 'message' => 'Xác thực CCCD thành công.',
                'cccd_text' => $text
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Không thể xác thực CCCD.'
            ], 200);
        }
    }

    //-----------------------------------------------------------------------------
    //giả lập check tồn tại sinh viên theo cccd
   public function checkInfo(Request $request)
{
    $cccdText = $request->input('cccd_text');

    if (!$cccdText) {
        return response()->json([
            'status' => 'error',
            'message' => 'Không có mã CCCD nào được cung cấp.'
        ], 400);
    }

    // check tồn tại cccd có trạng thái active
    $record = DB::table('can_cuoc_cong_dan')
        ->where('so_cccd', $cccdText)
        ->where('trang_thai', 'active')
        ->first();

    if (!$record) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Không tìm thấy thông tin CCCD hoạt động.'
        ], 200);
    }

    // tìm sinhvien theo cccd
    $student = DB::table('sinh_vien')
        ->where('so_cccd', $cccdText)
        ->first();

    if (!$student) {
        return response()->json([
            'status' => 'fail',
            'message' => 'Không tìm thấy sinh viên khớp với CCCD này.'
        ], 200);
    }

    // lấy thông tin 3 tài khoản
    $edu = DB::table('tai_khoan_edu')
        ->select('tai_khoan', 'mat_khau', 'ngay_reset')
        ->where('id', $student->tai_khoan_edu_id)
        ->first();

    $vle = DB::table('tai_khoan_vle')
        ->select('tai_khoan', 'mat_khau', 'ngay_reset')
        ->where('id', $student->tai_khoan_vle_id)
        ->first();

    $msteam = DB::table('tai_khoan_ms_team')
        ->select('tai_khoan', 'mat_khau', 'ngay_reset')
        ->where('id', $student->tai_khoan_ms_team_id)
        ->first();

    // trả qua form2
    return view('form2', [
        'record' => $record,
        'student' => $student,
        'edu' => $edu,
        'vle' => $vle,
        'msteam' => $msteam
    ]);
}

}
