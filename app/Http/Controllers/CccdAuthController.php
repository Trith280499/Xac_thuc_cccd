<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

            // Save to temp file
            $tempFile = tempnam(sys_get_temp_dir(), 'cccd_') . '.' . $type;
            file_put_contents($tempFile, $imageData);

            // Call OCR API
           $ocrResponse = Http::withoutVerifying()->withHeaders([
                'x-api-key' => '5dd1b197-c051-42c9-8f3f-4accd12335ex',
            ])->attach(
                'myfile', file_get_contents($tempFile), 'cccd_image.' . $type
            )->post('https://ocr.hcmue.edu.vn/extract');


            unlink($tempFile);

            if (!$ocrResponse->ok()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OCR API lỗi: ' . $ocrResponse->status()
                ], 500);
            }

            $ocrData = $ocrResponse->json();

            if (empty($ocrData['id'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OCR không nhận dạng được CCCD'
                ], 422);
            }

            // Use OCR result as input
            $cccdText = $ocrData['id'];
            $base64Img = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

            // Query database
            $student = DB::table('sinh_vien')->where('so_cccd', $cccdText)->first();

            if (!$student) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Không tìm thấy sinh viên trong DB',
                    'ocr_data' => $ocrData
                ], 200);
            }

            // Optional: query related tables
            $cccdData = DB::table('can_cuoc_cong_dan')->where('so_cccd', $cccdText)->first();
            $eduAccounts = DB::table('tai_khoan_edu')->where('id', $student->tai_khoan_edu_id)->get();
            $vleAccounts = DB::table('tai_khoan_vle')->where('id', $student->tai_khoan_vle_id)->get();
            $msteamAccounts = DB::table('tai_khoan_ms_team')->where('id', $student->tai_khoan_ms_team_id)->get();

            session([
            'cccd_authenticated' => true,
            'cccd_number' => $cccdText,
            'student_data' => [
                'sv' => $student,
                'cccdData' => $cccdData,
                'eduAccounts' => $eduAccounts,
                'vleAccounts' => $vleAccounts,
                'msteamAccounts' => $msteamAccounts,
                'decodedBase64' => $base64Img,
                'ocrData' => $ocrData
            ]
        ]);

        //Redirect to Form 2
        return redirect()->route('form2.view');

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }



// Thêm method đăng xuất
public function logout()
{
    session()->forget(['cccd_authenticated', 'cccd_number', 'student_data']);
    return redirect('/');
}

private function checkInfoInternal($cccdText, $decodedBase64)
{
    $student = DB::table('sinh_vien')->where('so_cccd', $cccdText)->first();
    $cccdData = DB::table('can_cuoc_cong_dan')->where('so_cccd', $cccdText)->first();

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

    return view('form2', [
        'sv' => $student,
        'cccdData' => $cccdData,
        'decodedBase64' => $decodedBase64,
        'eduAccounts' => $eduAccounts,
        'vleAccounts' => $vleAccounts,
        'msteamAccounts' => $msteamAccounts
    ]);
}

public function showForm2(Request $request)
{
    if (!session('cccd_authenticated')) {
        return redirect('/');
    }

    $data = session('student_data', []);

    return view('form2', [
        'sv' => $data['sv'] ?? null,
        'cccdData' => $data['cccdData'] ?? null,
        'decodedBase64' => $data['decodedBase64'] ?? null,
        'eduAccounts' => $data['eduAccounts'] ?? collect(),
        'vleAccounts' => $data['vleAccounts'] ?? collect(),
        'msteamAccounts' => $data['msteamAccounts'] ?? collect(),
    ]);
}

}
