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
