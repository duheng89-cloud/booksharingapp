<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 借阅请求模型
 * 
 * 这个模型对应数据库中的 borrow_requests 表
 * 用于处理借阅请求相关的数据操作
 */
class BorrowRequest extends Model
{
    use HasFactory;

    /**
     * 可批量赋值的属性
     * 
     * 这些字段可以通过 create() 或 update() 方法批量赋值
     *
     * @var array<string>
     */
    protected $fillable = [
        'book_id',        // 图书ID
        'borrower_id',    // 借阅者ID
        'owner_id',       // 图书所有者ID
        'status',         // 请求状态：pending=待处理，approved=已同意，rejected=已拒绝，returned=已归还
        'request_message', // 借阅留言
        'borrow_date',    // 借阅日期
        'return_date',    // 归还日期
    ];

    /**
     * 属性类型转换
     * 
     * 定义哪些属性应该被转换为特定的类型
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'borrow_date' => 'date',  // 将 borrow_date 转换为日期类型
            'return_date' => 'date',  // 将 return_date 转换为日期类型
        ];
    }

    /**
     * 获取借阅请求对应的图书
     * 
     * 这是一个关联关系方法，用于获取被借阅的图书
     * 
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * 获取借阅者（用户）
     * 
     * 这是一个关联关系方法，用于获取提交借阅请求的用户
     * 
     * @return BelongsTo
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * 获取图书所有者（用户）
     * 
     * 这是一个关联关系方法，用于获取图书的所有者
     * 
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * 检查请求是否待处理
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * 检查请求是否已同意
     * 
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * 检查请求是否已拒绝
     * 
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * 检查图书是否已归还
     * 
     * @return bool
     */
    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }
}
