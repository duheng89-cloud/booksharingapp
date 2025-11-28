<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * 用户模型
 * 
 * 这个模型对应数据库中的 users 表
 * 用于处理用户相关的数据操作
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * 可批量赋值的属性
     * 
     * 这些字段可以通过 create() 或 update() 方法批量赋值
     * 这是 Laravel 的安全机制，防止恶意数据注入
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',      // 用户姓名
        'email',     // 用户邮箱
        'password',  // 用户密码
        'role',      // 用户角色：user=普通用户，admin=管理员
    ];

    /**
     * 序列化时应该隐藏的属性
     * 
     * 这些字段在将模型转换为数组或JSON时会被隐藏
     * 主要用于保护敏感信息，如密码
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // 密码
        'remember_token',  // 记住我令牌
    ];

    /**
     * 属性类型转换
     * 
     * 定义哪些属性应该被转换为特定的类型
     * 例如：日期时间、布尔值等
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // 邮箱验证时间转换为日期时间类型
            'password' => 'hashed',            // 密码自动进行哈希加密
        ];
    }

    /**
     * 获取用户拥有的所有图书
     * 
     * 这是一个关联关系方法，用于获取该用户分享的所有图书
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function books(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * 获取用户提交的所有借阅请求
     * 
     * 这是一个关联关系方法，用于获取该用户作为借阅者提交的所有借阅请求
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrowRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BorrowRequest::class, 'borrower_id');
    }

    /**
     * 获取用户收到的所有借阅请求
     * 
     * 这是一个关联关系方法，用于获取该用户作为图书所有者收到的所有借阅请求
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receivedBorrowRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BorrowRequest::class, 'owner_id');
    }

    /**
     * 检查用户是否是管理员
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 检查用户是否是普通用户
     * 
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
