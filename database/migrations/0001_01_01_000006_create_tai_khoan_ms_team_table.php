<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tai_khoan_ms_team', function (Blueprint $table) {
            $table->id();
            $table->string('tai_khoan')->unique();
            $table->string('mat_khau');
            $table->date('ngay_reset')->nullable();
            $table->string('trang_thai')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tai_khoan_ms_team');
    }
};