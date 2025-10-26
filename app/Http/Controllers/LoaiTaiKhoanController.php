<?php

namespace App\Http\Controllers;

use App\Models\LoaiTaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoaiTaiKhoanController extends Controller
{
    public function index()
    {
        try {
            $loaiTaiKhoans = LoaiTaiKhoan::select('id', 'ten_loai', 'mo_ta', 'trang_thai')->get();
            
            return response()->json([
                'success' => true,
                'data' => $loaiTaiKhoans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy dữ liệu loại tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ten_loai' => 'required|string|max:255|unique:loai_tai_khoan,ten_loai',
                'mo_ta' => 'required|string',
                'trang_thai' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $loaiTaiKhoan = LoaiTaiKhoan::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Thêm loại tài khoản thành công',
                'data' => $loaiTaiKhoan
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm loại tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $loaiTaiKhoan = LoaiTaiKhoan::find($id);

            if (!$loaiTaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại tài khoản'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'ten_loai' => 'required|string|max:255|unique:loai_tai_khoan,ten_loai,' . $id,
                'mo_ta' => 'required|string',
                'trang_thai' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $loaiTaiKhoan->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật loại tài khoản thành công',
                'data' => $loaiTaiKhoan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật loại tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $loaiTaiKhoan = LoaiTaiKhoan::find($id);

            if (!$loaiTaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại tài khoản'
                ], 404);
            }

            $loaiTaiKhoan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa loại tài khoản thành công'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa loại tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $loaiTaiKhoan = LoaiTaiKhoan::find($id);

            if (!$loaiTaiKhoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy loại tài khoản'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $loaiTaiKhoan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin loại tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}