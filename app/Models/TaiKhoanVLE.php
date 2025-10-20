<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TaiKhoanVLE extends Model
{
    use HasFactory;

    protected $table = 'tai_khoan_vle';

    protected $fillable = [
        'tai_khoan',
        'mat_khau',
        'ngay_reset',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_reset' => 'date',
    ];

    public function sinhVien(): HasOne
    {
        return $this->hasOne(SinhVien::class, 'tai_khoan_vle_id');
    }
}