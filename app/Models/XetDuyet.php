<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XetDuyet extends Model
{
    use HasFactory;

    protected $table = 'xet_duyet';

    protected $fillable = [
        'mssv_input',
        'cccd_input',
        'trang_thai',
        'anh_cccd',
        'ghi_chu',
    ];
}
