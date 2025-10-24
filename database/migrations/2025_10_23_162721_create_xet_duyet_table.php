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
            $table->string('mssv_input');
            $table->string('cccd_input');
            $table->string('trang_thai')->default('pending'); // pending, approved, rejected
            $table->string('anh_cccd');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->index('mssv_input');
            $table->index('cccd_input');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xet_duyet');
    }
};