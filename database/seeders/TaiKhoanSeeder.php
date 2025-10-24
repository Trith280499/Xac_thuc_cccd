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
                'ten_tai_khoan' => 'SV' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '.vle',
                'loai_tai_khoan_id' => $loaiVLE->id,
                'sinh_vien_id' => $sinhVien->id,
                'mat_khau' => 'password123',
                'ngay_reset' => '2024-01-' . (15 + $index),
                'trang_thai' => 'active'
            ];
        }

        // Tạo tài khoản EDU cho mỗi sinh viên
        foreach ($sinhViens as $index => $sinhVien) {
            $taiKhoanData[] = [
                'ten_tai_khoan' => 'SV' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '@edu.com',
                'loai_tai_khoan_id' => $loaiEDU->id,
                'sinh_vien_id' => $sinhVien->id,
                'mat_khau' => 'edu123',
                'ngay_reset' => '2024-02-' . (15 + $index),
                'trang_thai' => 'active'
            ];
        }

        // Tạo tài khoản MS Teams cho mỗi sinh viên
        foreach ($sinhViens as $index => $sinhVien) {
            $taiKhoanData[] = [
                'ten_tai_khoan' => 'SV' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) . '@msteam.com',
                'loai_tai_khoan_id' => $loaiMSTeam->id,
                'sinh_vien_id' => $sinhVien->id,
                'mat_khau' => 'team123',
                'ngay_reset' => '2024-03-' . (15 + $index),
                'trang_thai' => 'active'
            ];
        }

        foreach ($taiKhoanData as $data) {
            TaiKhoan::create($data);
        }
    }
}