<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LoaiTaiKhoan;

class LoaiTaiKhoanSeeder extends Seeder
{
    public function run(): void
    {
        $loaiTaiKhoanData = [
            [
                'ten_loai' => 'VLE',
                'mo_ta' => 'Tài khoản hệ thống VLE',
                'trang_thai' => 'active'
            ],
            [
                'ten_loai' => 'EDU',
                'mo_ta' => 'Tài khoản hệ thống EDU',
                'trang_thai' => 'active'
            ],
            [
                'ten_loai' => 'MS_TEAM',
                'mo_ta' => 'Tài khoản Microsoft Teams',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($loaiTaiKhoanData as $data) {
            LoaiTaiKhoan::create($data);
        }
    }
}