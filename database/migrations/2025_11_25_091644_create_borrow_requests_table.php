<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 创建借阅请求表
 * 
 * 这个迁移文件用于创建 borrow_requests 表
 * 用于存储用户提交的借阅请求信息
 */
return new class extends Migration
{
    /**
     * 执行迁移
     * 创建 borrow_requests 表
     */
    public function up(): void
    {
        Schema::create('borrow_requests', function (Blueprint $table) {
            $table->id();                                    // 借阅请求ID，主键
            $table->unsignedBigInteger('book_id');          // 图书ID，关联books表的id字段（不使用外键约束）
            $table->unsignedBigInteger('borrower_id');      // 借阅者ID，关联users表的id字段（不使用外键约束）
            $table->unsignedBigInteger('owner_id');         // 图书所有者ID，关联users表的id字段（不使用外键约束）
            $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending'); // 请求状态：pending=待处理，approved=已同意，rejected=已拒绝，returned=已归还
            $table->text('request_message')->nullable();    // 借阅留言，可选
            $table->date('borrow_date')->nullable();        // 借阅日期，可选
            $table->date('return_date')->nullable();        // 归还日期，可选
            $table->timestamps();                           // 创建时间和更新时间
            
            // 添加索引以提高查询性能
            $table->index('book_id');                       // 图书ID索引
            $table->index('borrower_id');                   // 借阅者ID索引
            $table->index('owner_id');                      // 所有者ID索引
            $table->index('status');                        // 状态索引
        });
    }

    /**
     * 回滚迁移
     * 删除 borrow_requests 表
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_requests');
    }
};
