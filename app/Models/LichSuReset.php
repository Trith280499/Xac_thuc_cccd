<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuReset extends Model
{
    use HasFactory;

    protected $table = 'lich_su_reset';
    
    protected $fillable = [
        'tai_khoan',
        'loai_tai_khoan', 
        'mat_khau_moi',
        'thoi_gian_reset',
        'so_cccd'
    ];

    protected $casts = [
        'thoi_gian_reset' => 'datetime'
    ];

    /**
     * Relationship với CanCuocCongDan
     */
    public function canCuocCongDan()
    {
        return $this->belongsTo(CanCuocCongDan::class, 'so_cccd', 'so_cccd');
    }
}