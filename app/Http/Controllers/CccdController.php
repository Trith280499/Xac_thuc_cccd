<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CccdController extends Controller
{
    public function process(Request $request)
    {
        if (!$request->hasFile('cccd')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy file ảnh nào được tải lên.'
            ], 400);
        }

        $file = $request->file('cccd');
        $path = $file->store('uploads/cccd', 'public'); 
        $url = Storage::url($path); 

        return response()->json([
            'status' => 'success',
            'message' => 'Ảnh CCCD đã được tải lên thành công.',
            'image_url' => $url
        ]);
    }
}
