<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CanCuocCongDan;
use Carbon\Carbon;

class CanCuocCongDanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cccdData = [
            [
                'so_cccd' => '001123456789',
                'ho_ten' => 'Nguyễn Văn A',
                'ngay_sinh' => '1990-05-15',
                'gioi_tinh' => 'Nam',
                'quoc_tich' => 'Việt Nam',
                'que_quan' => 'Hà Nội',
                'noi_thuong_tru' => 'Số 123, đường Lê Lợi, quận Hoàn Kiếm, Hà Nội',
                'ngay_cap' => '2020-01-15',
                'noi_cap' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội - Hà Nội',
                'dac_diem_nhan_dang' => 'Nốt ruồi nhỏ bên má trái',
                'ngay_het_han' => '2030-01-15',
                'anh_cccd' => 'https://img.cand.com.vn/resize/800x800/NewFiles/Images/2024/02/16/6_tuoi_tro_len-1708097315445.png',
                'trang_thai' => 'active'
            ],
            [
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
                'anh_cccd' => 'https://img.cand.com.vn/resize/800x800/NewFiles/Images/2024/02/16/6_tuoi_tro_len-1708097315445.png',
                'trang_thai' => 'active'
            ],
            [
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
                'anh_cccd' => 'https://img.cand.com.vn/resize/800x800/NewFiles/Images/2024/02/16/6_tuoi_tro_len-1708097315445.png',
                'trang_thai' => 'active'
            ],
            [
                'so_cccd' => '001123456786',
                'ho_ten' => 'Phạm Thị D',
                'ngay_sinh' => '2000-03-08',
                'gioi_tinh' => 'Nữ',
                'quoc_tich' => 'Việt Nam',
                'que_quan' => 'Cần Thơ',
                'noi_thuong_tru' => 'Số 321, đường 30/4, quận Ninh Kiều, Cần Thơ',
                'ngay_cap' => '2022-11-05',
                'noi_cap' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội - Cần Thơ',
                'dac_diem_nhan_dang' => 'Má lúm đồng tiền',
                'ngay_het_han' => '2032-11-05',
                'anh_cccd' => 'https://img.cand.com.vn/resize/800x800/NewFiles/Images/2024/02/16/6_tuoi_tro_len-1708097315445.png',
                'trang_thai' => 'active'
            ],
            [
                'so_cccd' => '001123456785',
                'ho_ten' => 'Hoàng Văn E',
                'ngay_sinh' => '1978-07-12',
                'gioi_tinh' => 'Nam',
                'quoc_tich' => 'Việt Nam',
                'que_quan' => 'Hải Phòng',
                'noi_thuong_tru' => 'Số 654, đường Điện Biên Phủ, quận Hồng Bàng, Hải Phòng',
                'ngay_cap' => '2018-09-18',
                'noi_cap' => 'Cục Cảnh sát quản lý hành chính về trật tự xã hội - Hải Phòng',
                'dac_diem_nhan_dang' => 'Sẹo nhỏ trên trán',
                'ngay_het_han' => '2028-09-18',
                'anh_cccd' => 'https://img.cand.com.vn/resize/800x800/NewFiles/Images/2024/02/16/6_tuoi_tro_len-1708097315445.png',
                'trang_thai' => 'active'
            ]
        ];

        foreach ($cccdData as $data) {
            CanCuocCongDan::create($data);
        }
    }
}