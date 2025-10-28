<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuReset extends Model
{
    use HasFactory;

    protected $table = 'lich_su_reset';

    protected $fillable = [
        'so_cccd',
        'tai_khoan',
        'loai_tai_khoan',
        'mat_khau_moi',
        'thoi_gian_reset',
    ];

    protected $casts = [
        'thoi_gian_reset' => 'datetime',
    ];
}
