<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loai_tai_khoan', function (Blueprint $table) {
            $table->id();
            $table->string('ten_loai')->unique(); // VLE, EDU, MS_TEAM
            $table->string('mo_ta')->nullable();
            $table->string('trang_thai')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loai_tai_khoan');
    }
};