<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\XetDuyet;

class XetDuyetSeeder extends Seeder
{
    public function run(): void
    {
        $xetDuyetData = [
            [
                'mssv_input' => '20240001',
                'cccd_input' => '001123456789',
                'trang_thai' => 'approved',
                'ghi_chu' => 'Đã xét duyệt thành công'
            ],
            [
                'mssv_input' => '20240002',
                'cccd_input' => '001123456788',
                'trang_thai' => 'pending',
                'ghi_chu' => 'Đang chờ xét duyệt'
            ],
            [
                'mssv_input' => '20240003',
                'cccd_input' => '001123456787',
                'trang_thai' => 'rejected',
                'ghi_chu' => 'Thông tin không khớp'
            ]
        ];

        foreach ($xetDuyetData as $data) {
            XetDuyet::create($data);
        }
    }
}