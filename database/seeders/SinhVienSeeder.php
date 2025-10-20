<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SinhVien;
use App\Models\TaiKhoanVLE;
use App\Models\TaiKhoanEdu;
use App\Models\TaiKhoanMSTeam;

class SinhVienSeeder extends Seeder
{
    public function run(): void
    {
        $sinhVienData = [
            [
                'mssv' => '20240001',
                'so_cccd' => '001123456789',
                'tai_khoan_vle_id' => 1,
                'tai_khoan_edu_id' => 1,
                'tai_khoan_ms_team_id' => 1,
            ],
            [
                'mssv' => '20240002',
                'so_cccd' => '001123456788',
                'tai_khoan_vle_id' => 2,
                'tai_khoan_edu_id' => 2,
                'tai_khoan_ms_team_id' => 2,
            ],
            [
                'mssv' => '20240003',
                'so_cccd' => '001123456787',
                'tai_khoan_vle_id' => 3,
                'tai_khoan_edu_id' => 3,
                'tai_khoan_ms_team_id' => 3,
            ],
            [
                'mssv' => '20240004',
                'so_cccd' => '001123456786',
                'tai_khoan_vle_id' => 4,
                'tai_khoan_edu_id' => 4,
                'tai_khoan_ms_team_id' => 4,
            ],
            [
                'mssv' => '20240005',
                'so_cccd' => '001123456785',
                'tai_khoan_vle_id' => 5,
                'tai_khoan_edu_id' => 5,
                'tai_khoan_ms_team_id' => 5,
            ]
        ];

        foreach ($sinhVienData as $data) {
            SinhVien::create($data);
        }
    }
}