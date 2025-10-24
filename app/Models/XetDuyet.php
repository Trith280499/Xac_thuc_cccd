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
        // Dữ liệu nhập từ người dùng
        'mssv_input',
        'cccd_input',
        'trang_thai',
        'anh_cccd',
        'ghi_chu',

        // ====== Thông tin CCCD tạm thời ======
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
        'anh_cccd_moi',
        'trang_thai_cccd',
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
