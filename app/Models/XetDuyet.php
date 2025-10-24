<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XetDuyet extends Model
{
    use HasFactory;

    protected $table = 'xet_duyet';

    protected $fillable = [
        'mssv_input',
        'cccd_input',
        'trang_thai',
        'ghi_chu'
    ];

    /**
     * Relationship với SinhVien (qua mssv)
     */
    public function sinhVien(): BelongsTo
    {
        return $this->belongsTo(SinhVien::class, 'mssv_input', 'mssv');
    }

    /**
     * Relationship với CanCuocCongDan (qua cccd)
     */
    public function canCuocCongDan(): BelongsTo
    {
        return $this->belongsTo(CanCuocCongDan::class, 'cccd_input', 'so_cccd');
    }

    /**
     * Scope để lấy các bản ghi theo trạng thái
     */
    public function scopeTrangThai($query, $trangThai)
    {
        return $query->where('trang_thai', $trangThai);
    }

    /**
     * Scope để lấy các bản ghi đang chờ xét duyệt
     */
    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'pending');
    }

    /**
     * Scope để lấy các bản ghi đã duyệt
     */
    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', 'approved');
    }

    /**
     * Scope để lấy các bản ghi bị từ chối
     */
    public function scopeTuChoi($query)
    {
        return $query->where('trang_thai', 'rejected');
    }
}