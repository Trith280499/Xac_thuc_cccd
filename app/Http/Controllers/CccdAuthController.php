<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CccdAuthController extends Controller
{
    //giả lập function xác thực cccd, trả về cccd từ hình ảnh
  public function authenticate(Request $request)
{
    $encoded_img = $request->input('image_base64');

    if (!$encoded_img) {
        return response()->json(['status' => 'error', 'message' => 'Thiếu dữ liệu ảnh'], 400);
    }

    //  Decode base64
    if (preg_match('/^data:image\/(\w+);base64,/', $encoded_img, $type)) {
        $encoded_img = substr($encoded_img, strpos($encoded_img, ',') + 1);
        $type = strtolower($type[1]);
        $imageData = base64_decode($encoded_img);
    }

    $cccdText = '001123456789'; // giả lập OCR
    $base64Img = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

    return $this->checkInfoInternal($cccdText, $base64Img);
}

private function checkInfoInternal($cccdText, $decodedBase64)
{
    $student = DB::table('sinh_vien')->where('so_cccd', $cccdText)->first();

    $eduAccounts = DB::table('tai_khoan_edu')
        ->select('tai_khoan', 'mat_khau', 'ngay_reset')
        ->where('id', $student->tai_khoan_edu_id ?? null)
        ->get();

    $vleAccounts = DB::table('tai_khoan_vle')
        ->select('tai_khoan', 'mat_khau', 'ngay_reset')
        ->where('id', $student->tai_khoan_vle_id ?? null)
        ->get();

    $msteamAccounts = DB::table('tai_khoan_ms_team')
        ->select('tai_khoan', 'mat_khau', 'ngay_reset')
        ->where('id', $student->tai_khoan_ms_team_id ?? null)
        ->get();

    // ✅ Trả về view form2 luôn
    return view('form2', [
        'sv' => $student,
        'cccd' => $cccdText,
        'decodedBase64' => $decodedBase64,
        'eduAccounts' => $eduAccounts,
        'vleAccounts' => $vleAccounts,
        'msteamAccounts' => $msteamAccounts
    ]);
}

}
