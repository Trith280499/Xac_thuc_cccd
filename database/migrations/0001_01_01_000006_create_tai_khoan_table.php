<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->id();
            $table->string('ten_tai_khoan');
            $table->foreignId('loai_tai_khoan_id')->constrained('loai_tai_khoan');
            $table->foreignId('sinh_vien_id')->constrained('sinh_vien');
            $table->date('ngay_reset')->nullable();
            $table->string('trang_thai')->default('active');
            $table->timestamps();

            // Đảm bảo tên tài khoản là duy nhất theo từng loại
            $table->unique(['ten_tai_khoan', 'loai_tai_khoan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tai_khoan');
    }
};