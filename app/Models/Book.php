<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 图书模型
 * 
 * 这个模型对应数据库中的 books 表
 * 用于处理图书相关的数据操作
 */
class Book extends Model
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
        'user_id',       // 图书所有者ID
        'title',         // 图书标题
        'author',        // 图书作者
        'isbn',          // ISBN号
        'description',   // 图书描述
        'cover_image',   // 图书封面图片路径
        'status',        // 审核状态：pending=待审核，approved=已通过，rejected=已拒绝
        'is_available',  // 是否可借阅
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
            'is_available' => 'boolean',  // 将 is_available 转换为布尔类型
        ];
    }

    /**
     * 获取图书的所有者（用户）
     * 
     * 这是一个关联关系方法，用于获取拥有这本书的用户
     * 
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 获取图书的所有借阅请求
     * 
     * 这是一个关联关系方法，用于获取这本书的所有借阅请求
     * 
     * @return HasMany
     */
    public function borrowRequests(): HasMany
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /**
     * 检查图书是否可借阅
     * 
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->is_available && $this->status === 'approved';
    }
}
