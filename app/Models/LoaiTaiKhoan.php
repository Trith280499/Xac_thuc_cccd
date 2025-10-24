<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoaiTaiKhoan extends Model
{
    use HasFactory;

    protected $table = 'loai_tai_khoan';

    protected $fillable = [
        'ten_loai',
        'mo_ta',
        'trang_thai'
    ];

    /**
     * Relationship với TaiKhoan
     */
    public function taiKhoans(): HasMany
    {
        return $this->hasMany(TaiKhoan::class, 'loai_tai_khoan_id');
    }
}