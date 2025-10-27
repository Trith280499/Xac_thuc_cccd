<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaiKhoan;
use App\Models\LoaiTaiKhoan;
use App\Models\SinhVien;
use Carbon\Carbon;

class TaiKhoanSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách loại tài khoản
        $loaiVLE = LoaiTaiKhoan::where('ten_loai', 'VLE')->first();
        $loaiEDU = LoaiTaiKhoan::where('ten_loai', 'EDU')->first();
        $loaiMSTeam = LoaiTaiKhoan::where('ten_loai', 'MS_TEAM')->first();

        // Lấy danh sách sinh viên
        $sinhViens = SinhVien::all();

        $taiKhoanData = [];

        // Tạo tài khoản VLE cho mỗi sinh viên
        foreach ($sinhViens as $index => $sinhVien) {
            $taiKhoanData[] = [
                'ten_tai_khoan' => $sinhVien->mssv, 
                'loai_tai_khoan_id' => $loaiVLE->id,
                'sinh_vien_id' => $sinhVien->id,
                'ngay_reset' => Carbon::now()->addDays($index),
                'trang_thai' => 'active'
            ];
        }

        // Tạo tài khoản EDU cho mỗi sinh viên
        foreach ($sinhViens as $index => $sinhVien) {
            $taiKhoanData[] = [
                'ten_tai_khoan' => $sinhVien->mssv . '@edu', 
                'loai_tai_khoan_id' => $loaiEDU->id,
                'sinh_vien_id' => $sinhVien->id,
                'ngay_reset' => Carbon::now()->addDays($index),
                'trang_thai' => 'active'
            ];
        }

        // Tạo tài khoản MS Teams cho mỗi sinh viên
        foreach ($sinhViens as $index => $sinhVien) {
            $taiKhoanData[] = [
                'ten_tai_khoan' => $sinhVien->mssv . '@team', 
                'loai_tai_khoan_id' => $loaiMSTeam->id,
                'sinh_vien_id' => $sinhVien->id,
                'ngay_reset' => Carbon::now()->addDays($index),
                'trang_thai' => 'active'
            ];
        }

        foreach ($taiKhoanData as $data) {
            TaiKhoan::create($data);
        }
    }
}