<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 创建图书表
 * 
 * 这个迁移文件用于创建 books 表
 * 用于存储用户分享的图书信息
 */
return new class extends Migration
{
    /**
     * 执行迁移
     * 创建 books 表
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();                                    // 图书ID，主键
            $table->unsignedBigInteger('user_id');          // 图书所有者ID，关联users表的id字段（不使用外键约束）
            $table->string('title');                         // 图书标题
            $table->string('author');                       // 图书作者
            $table->string('isbn')->nullable()->unique();   // ISBN号，可选，唯一索引
            $table->text('description')->nullable();       // 图书描述，可选
            $table->string('cover_image')->nullable();      // 图书封面图片路径，可选
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // 审核状态：pending=待审核，approved=已通过，rejected=已拒绝
            $table->boolean('is_available')->default(true); // 是否可借阅，默认true（可借阅）
            $table->timestamps();                           // 创建时间和更新时间
            
            // 添加索引以提高查询性能
            $table->index('user_id');                       // 用户ID索引
            $table->index('status');                        // 状态索引
            $table->index('is_available');                 // 可借阅状态索引
        });
    }

    /**
     * 回滚迁移
     * 删除 books 表
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
