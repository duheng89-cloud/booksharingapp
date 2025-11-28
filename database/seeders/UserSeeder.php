<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * 用户种子数据
 * 
 * 用于创建初始的管理员账户和示例用户
 */
class UserSeeder extends Seeder
{
    /**
     * 运行数据库种子
     */
    public function run(): void
    {
        // 创建管理员账户（如果不存在）
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('123456789'), 
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        if ($admin->wasRecentlyCreated) {
            $this->command->info('管理员账户创建成功！');
        } else {
            $this->command->info('管理员账户已存在，跳过创建。');
        }

        // 创建几个普通用户账户
        $users = [
            [
                'name' => 'Zhang San',
                'email' => 'zhangsan@example.com',
                'password' => Hash::make('123456789'), 
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Li Si',
                'email' => 'lisi@example.com',
                'password' => Hash::make('123456789'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Wang Wu',
                'email' => 'wangwu@example.com',
                'password' => Hash::make('123456789'),
                'role' => 'user',
                'email_verified_at' => now(),
            ],
        ];

        $createdCount = 0;
        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            if ($user->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        $this->command->info("用户种子数据创建完成！新创建 {$createdCount} 个用户。");
        $this->command->info('管理员账户：admin@example.com / admin123');
        $this->command->info('普通用户账户：zhangsan@example.com / password123');
    }
}
