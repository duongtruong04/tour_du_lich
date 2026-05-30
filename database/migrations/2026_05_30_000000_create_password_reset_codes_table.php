<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Bảng password_reset_codes lưu mã OTP đã hash cho chức năng quên mật khẩu.
     * - email_hash: HMAC SHA256 của email (không lưu email raw)
     * - code_hash: bcrypt hash của OTP 6 số
     * - Có đầy đủ rate limit tracking: attempts, locked_until, cooldown
     */
    public function up()
    {
        Schema::create('password_reset_codes', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            $table->string('email_hash', 64)->index();
            $table->string('code_hash');

            $table->dateTime('sent_at')->nullable();
            $table->dateTime('expires_at')->index();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->dateTime('locked_until')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->dateTime('used_at')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index(['email_hash', 'used_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('password_reset_codes');
    }
};
