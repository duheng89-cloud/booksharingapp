<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 数据库种子主类
 * 
 * 用于运行所有种子数据
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * 运行数据库种子
     * 
     * 执行顺序：
     * 1. 先创建用户（UserSeeder）
     * 2. 再创建图书（BookSeeder），因为图书需要关联用户
     */
    public function run(): void
    {
        $this->command->info('开始创建种子数据...');
        
        // 先创建用户
        $this->call(UserSeeder::class);
        
        // 再创建图书（图书需要关联用户）
        $this->call(BookSeeder::class);
        
        $this->command->info('种子数据创建完成！');
    }
}
