<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_vien';

    protected $fillable = [
        'mssv',
        'so_cccd',
        'trang_thai'
    ];

    /**
     * Relationship với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mssv', 'mssv');
    }

    /**
     * Relationship với TaiKhoan
     */
    public function taiKhoans(): HasMany
    {
        return $this->hasMany(TaiKhoan::class, 'sinh_vien_id');
    }

    /**
     * Relationship với XetDuyet
     */
    public function xetDuyets(): HasMany
    {
        return $this->hasMany(XetDuyet::class, 'mssv_input', 'mssv');
    }
}