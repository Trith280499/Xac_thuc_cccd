<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sinh_vien', function (Blueprint $table) {
            $table->id();
            $table->string('mssv')->unique();
            $table->string('so_cccd');
            $table->string('trang_thai')->default('active');
            $table->timestamps();

            // Khóa ngoại đến bảng căn cước công dân
            $table->foreign('so_cccd')->references('so_cccd')->on('can_cuoc_cong_dan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sinh_vien');
    }
};