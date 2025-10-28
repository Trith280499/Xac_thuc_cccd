<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    use HasFactory;

    protected $table = 'tai_khoan';

    protected $fillable = [
        'cccd',
        'ten_tai_khoan',
        'loai_tai_khoan_id',
        'ngay_cap_nhat',
    ];

    protected $casts = [
        'ngay_cap_nhat' => 'date',
    ];

    public function loaiTaiKhoan()
    {
        return $this->belongsTo(LoaiTaiKhoan::class, 'loai_tai_khoan_id');
    }
}
