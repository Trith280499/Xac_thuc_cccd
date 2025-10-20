 <?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
