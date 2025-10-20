<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThongTinController extends Controller
{
    /**
     * Hiển thị form nhập thông tin sinh viên & chọn loại tài khoản reset
     */
    public function index()
    {
        return view('reset.form2'); // View resources/views/reset/form2.blade.php
    }

    /**
     * Xử lý khi người dùng nhấn "Reset"
     */
    public function reset(Request $request)
    {
        $request->validate([
            'hoten' => 'required|string|max:100',
            'cccd' => 'required|string|max:20',
            'mssv' => 'required|string|max:20',
            'account_types' => 'required|array|min:1',
        ]);

        // Gộp danh sách loại tài khoản đã chọn
        $types = implode(', ', $request->account_types);

        // (Giả lập xử lý reset)
        return back()->with('success', "✅ Đã reset các tài khoản: $types cho sinh viên {$request->hoten}");
    }
}
