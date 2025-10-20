<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SinhVien extends Model
{
    use HasFactory;

    protected $table = 'sinh_vien';

    protected $fillable = [
        'mssv',
        'so_cccd',
        'tai_khoan_vle_id',
        'tai_khoan_edu_id',
        'tai_khoan_ms_team_id',
        'trang_thai'
    ];

    public function canCuocCongDan(): BelongsTo
    {
        return $this->belongsTo(CanCuocCongDan::class, 'so_cccd', 'so_cccd');
    }

    public function taiKhoanVLE(): BelongsTo
    {
        return $this->belongsTo(TaiKhoanVLE::class, 'tai_khoan_vle_id');
    }

    public function taiKhoanEdu(): BelongsTo
    {
        return $this->belongsTo(TaiKhoanEdu::class, 'tai_khoan_edu_id');
    }

    public function taiKhoanMSTeam(): BelongsTo
    {
        return $this->belongsTo(TaiKhoanMSTeam::class, 'tai_khoan_ms_team_id');
    }
}