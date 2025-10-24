<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xet_duyet', function (Blueprint $table) {
            $table->id();

            // Thông tin nhập vào
            $table->string('mssv_input');
            $table->string('cccd_input');
            $table->string('trang_thai')->default('pending'); // pending, approved, rejected
            $table->string('anh_cccd')->nullable();
            $table->text('ghi_chu')->nullable();

            // ====== Thông tin tạm từ CCCD (copy từ bảng can_cuoc_cong_dan) ======
            $table->string('so_cccd')->nullable();
            $table->string('ho_ten')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác'])->nullable();
            $table->string('quoc_tich')->default('Việt Nam');
            $table->string('que_quan')->nullable();
            $table->string('noi_thuong_tru')->nullable();
            $table->date('ngay_cap')->nullable();
            $table->string('noi_cap')->nullable();
            $table->text('dac_diem_nhan_dang')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->string('anh_cccd_moi')->nullable(); // lưu bản ảnh CCCD mới nếu cần
            $table->string('trang_thai_cccd')->default('active');

            $table->timestamps();

            // Index cho tra cứu nhanh
            $table->index('mssv_input');
            $table->index('cccd_input');
            $table->index('so_cccd');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xet_duyet');
    }
};
