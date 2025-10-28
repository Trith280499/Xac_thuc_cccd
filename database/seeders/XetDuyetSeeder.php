<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\XetDuyet;

class XetDuyetSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'mssv_input' => '20240001',
                'cccd_input' => '001123456789',
                'trang_thai' => 'approved',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'ghi_chu' => 'Đã xét duyệt thành công',
            ],
            [
                'mssv_input' => '20240002',
                'cccd_input' => '001123456788',
                'trang_thai' => 'pending',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'ghi_chu' => 'Đang chờ xét duyệt',
            ],
            [
                'mssv_input' => '20240003',
                'cccd_input' => '001123456787',
                'trang_thai' => 'rejected',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'ghi_chu' => 'Thông tin không khớp',
            ],
        ];

        XetDuyet::insert($data);
    }
}
