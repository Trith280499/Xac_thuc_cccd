<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaiKhoan extends Model
{
    use HasFactory;

    protected $table = 'tai_khoan';

    protected $fillable = [
        'ten_tai_khoan',
        'loai_tai_khoan_id',
        'sinh_vien_id',
        'mat_khau',
        'ngay_reset',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_reset' => 'date',
    ];

    /**
     * Relationship với LoaiTaiKhoan
     */
    public function loaiTaiKhoan(): BelongsTo
    {
        return $this->belongsTo(LoaiTaiKhoan::class, 'loai_tai_khoan_id');
    }

    /**
     * Relationship với SinhVien
     */
    public function sinhVien(): BelongsTo
    {
        return $this->belongsTo(SinhVien::class, 'sinh_vien_id');
    }

    /**
     * Relationship với LichSuReset
     */
    public function lichSuResets(): HasMany
    {
        return $this->hasMany(LichSuReset::class, 'tai_khoan_id');
    }
}