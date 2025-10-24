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
        // Lưu dữ liệu cache (key, value, expiration) Nơi lưu dữ liệu tạm giúp tăng tốc web
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary(); // Khóa duy nhất cho cache
            $table->mediumText('value'); // Dữ liệu được lưu trữ
            $table->integer('expiration'); //Lưu thông tin trong bao nhiêu phút
        });

        // Quản lý khóa tránh ghi trùng cache, Ngăn lỗi khi nhiều tiến trình cùng ghi cache
        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary(); // Khóa của lock
            $table->string('owner'); // Ai đang giữ lock này
            $table->integer('expiration'); //Lưu thông tin trong bao nhiêu phút
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
