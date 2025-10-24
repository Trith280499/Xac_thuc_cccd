<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CanCuocCongDan extends Model
{
    use HasFactory;

    protected $table = 'can_cuoc_cong_dan';

    protected $fillable = [
        'so_cccd',
        'ho_ten',
        'ngay_sinh',
        'gioi_tinh',
        'quoc_tich',
        'que_quan',
        'noi_thuong_tru',
        'ngay_cap',
        'noi_cap',
        'dac_diem_nhan_dang',
        'ngay_het_han',
        'anh_cccd',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_cap' => 'date',
        'ngay_het_han' => 'date',
    ];
}