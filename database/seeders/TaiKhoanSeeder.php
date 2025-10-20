<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaiKhoanVLE;
use App\Models\TaiKhoanEdu;
use App\Models\TaiKhoanMSTeam;
use Carbon\Carbon;

class TaiKhoanSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo dữ liệu cho TaiKhoanVLE
        $vleAccounts = [
            [
                'tai_khoan' => 'SV001.vle',
                'mat_khau' => 'password123',
                'ngay_reset' => '2024-01-15',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV002.vle',
                'mat_khau' => 'password123',
                'ngay_reset' => '2024-01-16',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV003.vle',
                'mat_khau' => 'password123',
                'ngay_reset' => '2024-01-17',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV004.vle',
                'mat_khau' => 'password123',
                'ngay_reset' => '2024-01-18',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV005.vle',
                'mat_khau' => 'password123',
                'ngay_reset' => '2024-01-19',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($vleAccounts as $account) {
            TaiKhoanVLE::create($account);
        }

        // Tạo dữ liệu cho TaiKhoanEdu
        $eduAccounts = [
            [
                'tai_khoan' => 'SV001@edu.com',
                'mat_khau' => 'edu123',
                'ngay_reset' => '2024-02-15',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV002@edu.com',
                'mat_khau' => 'edu123',
                'ngay_reset' => '2024-02-16',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV003@edu.com',
                'mat_khau' => 'edu123',
                'ngay_reset' => '2024-02-17',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV004@edu.com',
                'mat_khau' => 'edu123',
                'ngay_reset' => '2024-02-18',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV005@edu.com',
                'mat_khau' => 'edu123',
                'ngay_reset' => '2024-02-19',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($eduAccounts as $account) {
            TaiKhoanEdu::create($account);
        }

        // Tạo dữ liệu cho TaiKhoanMSTeam
        $msTeamAccounts = [
            [
                'tai_khoan' => 'SV001@msteam.com',
                'mat_khau' => 'team123',
                'ngay_reset' => '2024-03-15',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV002@msteam.com',
                'mat_khau' => 'team123',
                'ngay_reset' => '2024-03-16',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV003@msteam.com',
                'mat_khau' => 'team123',
                'ngay_reset' => '2024-03-17',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV004@msteam.com',
                'mat_khau' => 'team123',
                'ngay_reset' => '2024-03-18',
                'trang_thai' => 'active'
            ],
            [
                'tai_khoan' => 'SV005@msteam.com',
                'mat_khau' => 'team123',
                'ngay_reset' => '2024-03-19',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($msTeamAccounts as $account) {
            TaiKhoanMSTeam::create($account);
        }
    }
}