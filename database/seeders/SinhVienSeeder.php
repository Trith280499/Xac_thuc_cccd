<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SinhVien;

class SinhVienSeeder extends Seeder
{
    public function run(): void
    {
        $sinhVienData = [
            [
                'mssv' => '48.01.104.030',
                'so_cccd' => '079204021537',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
            ],
            [
                'mssv' => '20240002',
                'so_cccd' => '001123456788',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
            ],
            [
                'mssv' => '20240003',
                'so_cccd' => '001123456787',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
            ]
        ];

        foreach ($sinhVienData as $data) {
            SinhVien::create($data);
        }
    }
}