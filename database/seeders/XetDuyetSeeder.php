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
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'ghi_chu' => 'Đã xét duyệt thành công',

                // ====== Thông tin CCCD tạm ======
                'so_cccd' => '001123456789',
                'ho_ten' => 'Nguyễn Văn A',
                'ngay_sinh' => '2002-01-15',
                'gioi_tinh' => 'Nam',
                'quoc_tich' => 'Việt Nam',
                'que_quan' => 'Hà Nội',
                'noi_thuong_tru' => 'Số 123, đường Lý Thường Kiệt, Hà Nội',
                'ngay_cap' => '2020-03-10',
                'noi_cap' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội - Hà Nội',
                'dac_diem_nhan_dang' => 'Nốt ruồi nhỏ dưới mắt phải',
                'ngay_het_han' => '2030-03-10',
                'anh_cccd_moi' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'trang_thai_cccd' => 'active'
            ],
            [
                'mssv_input' => '20240002',
                'cccd_input' => '001123456788',
                'trang_thai' => 'pending',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'ghi_chu' => 'Đang chờ xét duyệt',

                'so_cccd' => '001123456788',
                'ho_ten' => 'Trần Thị B',
                'ngay_sinh' => '1995-08-20',
                'gioi_tinh' => 'Nữ',
                'quoc_tich' => 'Việt Nam',
                'que_quan' => 'Hồ Chí Minh',
                'noi_thuong_tru' => 'Số 456, đường Nguyễn Huệ, quận 1, TP Hồ Chí Minh',
                'ngay_cap' => '2021-03-10',
                'noi_cap' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội - TP Hồ Chí Minh',
                'dac_diem_nhan_dang' => 'Kính cận',
                'ngay_het_han' => '2031-03-10',
                'anh_cccd_moi' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'trang_thai_cccd' => 'active'
            ],
            [
                'mssv_input' => '20240003',
                'cccd_input' => '001123456787',
                'trang_thai' => 'rejected',
                'anh_cccd' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'ghi_chu' => 'Thông tin không khớp',

                'so_cccd' => '001123456787',
                'ho_ten' => 'Lê Văn C',
                'ngay_sinh' => '1985-12-30',
                'gioi_tinh' => 'Nam',
                'quoc_tich' => 'Việt Nam',
                'que_quan' => 'Đà Nẵng',
                'noi_thuong_tru' => 'Số 789, đường Trần Phú, quận Hải Châu, Đà Nẵng',
                'ngay_cap' => '2019-06-25',
                'noi_cap' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội - Đà Nẵng',
                'dac_diem_nhan_dang' => 'Râu quai nón',
                'ngay_het_han' => '2029-06-25',
                'anh_cccd_moi' => 'https://0711.vn/storage/uploads/img2022071309455272424000.jpg',
                'trang_thai_cccd' => 'active'
            ],
        ];

        foreach ($xetDuyetData as $data) {
            XetDuyet::create($data);
        }
    }
}
