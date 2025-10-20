<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('can_cuoc_cong_dan', function (Blueprint $table) {
            $table->id();
            $table->string('so_cccd')->unique();
            $table->string('ho_ten');
            $table->date('ngay_sinh');
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác']);
            $table->string('quoc_tich')->default('Việt Nam');
            $table->string('que_quan');
            $table->string('noi_thuong_tru');
            $table->date('ngay_cap');
            $table->string('noi_cap');
            $table->text('dac_diem_nhan_dang')->nullable();
            $table->date('ngay_het_han')->nullable();
            $table->string('anh_cccd')->nullable();
            $table->string('trang_thai')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('can_cuoc_cong_dan');
    }
};