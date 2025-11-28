<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 创建用户表
 * 
 * 这个迁移文件用于创建 users 表
 * 用于存储用户的基本信息和认证数据
 */
return new class extends Migration
{
    /**
     * 执行迁移
     * 创建 users 表
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();                                    // 用户ID，主键
            $table->string('name');                         // 用户姓名
            $table->string('email')->unique();               // 用户邮箱，唯一索引
            $table->timestamp('email_verified_at')->nullable(); // 邮箱验证时间
            $table->string('password');                      // 用户密码（加密存储）
            $table->enum('role', ['user', 'admin'])->default('user'); // 用户角色：user=普通用户，admin=管理员
            $table->rememberToken();                        // 记住我功能的令牌
            $table->timestamps();                           // 创建时间和更新时间
        });

        // 创建密码重置令牌表（Laravel 认证系统需要）
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 创建会话表（Laravel 会话系统需要）
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
     * 回滚迁移
     * 删除 users 表及相关表
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
