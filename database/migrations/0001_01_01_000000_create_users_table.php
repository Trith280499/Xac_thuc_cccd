<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void  //Hàm up() – tạo bảng
    {
        // Bảng này dùng để lưu thông tin người dùng của hệ thống.
        // Nó được Laravel sử dụng mặc định cho việc đăng ký / đăng nhập.
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // tự động tạo khóa chính "id" (auto increment)
            $table->string('name');
            $table->string('email')->unique(); // không trùng lặp email
            $table->timestamp('email_verified_at')->nullable(); // ngày xác minh email
            $table->string('password'); // mật khẩu (mã hóa bcrypt)
            $table->rememberToken(); // token để "ghi nhớ đăng nhập"
            $table->timestamps(); // cặp cột created_at và updated_at
        });
        
        // Bảng này dùng để lưu token khi người dùng quên mật khẩu.
        // Khi bạn bấm “Quên mật khẩu”, hệ thống gửi email chứa link có token này để đặt lại password.
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });


        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // ID của phiên đăng nhập
            $table->foreignId('user_id')->nullable()->index(); // người dùng nào (nếu có)
            $table->string('ip_address', 45)->nullable(); // IP của người dùng
            $table->text('user_agent')->nullable(); // trình duyệt, thiết bị  (Anh Trí mới thêm)
            $table->longText('payload'); // dữ liệu phiên (session data)
            $table->integer('last_activity')->index(); // thời gian hoạt động gần nhất
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void //Hàm down() – xóa bảng 
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
