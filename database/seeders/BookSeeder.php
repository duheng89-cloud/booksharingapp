<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * 图书种子数据
 * 
 * 用于创建示例图书数据
 */
class BookSeeder extends Seeder
{
    /**
     * 运行数据库种子
     */
    public function run(): void
    {
        // 获取所有用户（用于分配图书所有者）
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('没有找到用户，请先运行 UserSeeder！');
            return;
        }

        // 示例图书数据
        $books = [
            [
                'title' => 'Laravel 从入门到精通',
                'author' => 'Taylor Otwell',
                'isbn' => '978-7-121-12345-6',
                'description' => '这是一本全面介绍 Laravel 框架的书籍，从基础概念到高级应用，帮助开发者快速掌握 Laravel 开发技能。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => 'PHP 现代开发实践',
                'author' => 'Josh Lockhart',
                'isbn' => '978-7-121-12346-3',
                'description' => '深入探讨 PHP 现代开发的最佳实践，包括面向对象编程、设计模式、性能优化等内容。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => 'MySQL 数据库设计与优化',
                'author' => 'Baron Schwartz',
                'isbn' => '978-7-121-12347-0',
                'description' => '全面介绍 MySQL 数据库的设计原则、索引优化、查询优化等核心知识，适合数据库管理员和开发人员阅读。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => 'Vue.js 3.0 实战指南',
                'author' => 'Evan You',
                'isbn' => '978-7-121-12348-7',
                'description' => 'Vue.js 3.0 的完整实战教程，涵盖组合式 API、响应式系统、组件开发等核心内容。',
                'status' => 'approved',
                'is_available' => false, // 已借出
            ],
            [
                'title' => 'JavaScript 高级程序设计',
                'author' => 'Matt Frisbie',
                'isbn' => '978-7-121-12349-4',
                'description' => 'JavaScript 领域的经典教材，深入讲解 JavaScript 的核心概念和高级特性。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => '设计模式：可复用面向对象软件的基础',
                'author' => 'Gang of Four',
                'isbn' => '978-7-121-12350-1',
                'description' => '软件设计模式的经典著作，介绍了23种常用的设计模式，是每个程序员都应该阅读的书籍。',
                'status' => 'pending', // 待审核
                'is_available' => true,
            ],
            [
                'title' => '算法导论',
                'author' => 'Thomas H. Cormen',
                'isbn' => '978-7-121-12351-8',
                'description' => '计算机科学领域的经典教材，全面系统地介绍了算法和数据结构的知识。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => '深入理解计算机系统',
                'author' => 'Randal E. Bryant',
                'isbn' => '978-7-121-12352-5',
                'description' => '从程序员的角度深入理解计算机系统的工作原理，包括程序执行、内存管理、网络编程等内容。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => '代码整洁之道',
                'author' => 'Robert C. Martin',
                'isbn' => '978-7-121-12353-2',
                'description' => '软件工程领域的经典著作，教你如何编写清晰、可维护的代码。',
                'status' => 'approved',
                'is_available' => true,
            ],
            [
                'title' => '重构：改善既有代码的设计',
                'author' => 'Martin Fowler',
                'isbn' => '978-7-121-12354-9',
                'description' => '重构领域的权威指南，详细介绍了各种重构技巧和最佳实践。',
                'status' => 'approved',
                'is_available' => true,
            ],
        ];

        // 创建图书，随机分配给用户
        $createdCount = 0;
        foreach ($books as $bookData) {
            // 检查图书是否已存在（通过 ISBN）
            if ($bookData['isbn'] && Book::where('isbn', $bookData['isbn'])->exists()) {
                continue; // 如果已存在，跳过
            }

            // 随机选择一个用户作为图书所有者（排除管理员，让普通用户拥有图书）
            $owner = $users->where('role', 'user')->random();
            
            Book::create([
                'user_id' => $owner->id,
                'title' => $bookData['title'],
                'author' => $bookData['author'],
                'isbn' => $bookData['isbn'],
                'description' => $bookData['description'],
                'status' => $bookData['status'],
                'is_available' => $bookData['is_available'],
            ]);
            
            $createdCount++;
        }

        $this->command->info("图书种子数据创建完成！新创建 {$createdCount} 本图书。");
    }
}
