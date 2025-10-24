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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('mssv')->unique();
            $table->string('so_cccd');
            $table->string('trang_thai')->default('active');
            $table->timestamps();

            // Thêm index trước khi tạo khóa ngoại
            $table->index('so_cccd');
        });

        // Tạo khóa ngoại sau khi đã tạo bảng can_cuoc_cong_dan
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('so_cccd')->references('so_cccd')->on('can_cuoc_cong_dan');
        });

        // Giữ nguyên các bảng mặc định của Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        
        // Xóa khóa ngoại trước khi xóa bảng
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['so_cccd']);
        });
        
        Schema::dropIfExists('users');
    }
};