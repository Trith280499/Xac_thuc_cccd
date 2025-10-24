<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyAccountController extends Controller
{
    public function showForm()
    {
        return view('VerifyAccount');
    }

    public function verify(Request $request)
    {
        $cccd = $request->cccd;
        $mssv = $request->mssv;

        // Giả lập: kiểm tra sinh viên trong DB
        $student = DB::table('SINHVIEN')
            ->where('cccd', $cccd)
            ->where('mssv', $mssv)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Không tìm thấy sinh viên'
            ]);
        }

        // Nếu có sinh viên, giả sử sinh viên có 2 loại tài khoản:
        $accounts = [
            ['type' => 'Email Trường', 'username' => $student->email],
            ['type' => 'Tài khoản LMS', 'username' => $student->mssv . '@hcmue.edu.vn']
        ];

        return response()->json([
            'status' => 'success',
            'student' => $student,
            'accounts' => $accounts
        ]);
    }

    public function notStudent()
    {
        return view('NotStudent');
    }
}
