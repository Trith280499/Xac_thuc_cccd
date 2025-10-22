<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lich_su_reset', function (Blueprint $table) {
            $table->id();
            $table->string('tai_khoan');
            $table->string('loai_tai_khoan'); // Teams, VLE, Portal
            $table->string('mat_khau_moi');
            $table->timestamp('thoi_gian_reset')->useCurrent();
            $table->timestamps();
            
            $table->index('tai_khoan');
            $table->index('loai_tai_khoan');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_su_reset');
    }
};