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
                'title' => 'The Let Them',
                'author' => 'Mel Robbins,Sawyer Robbins',
                'isbn' => '1401971369',
                'description' => 'The Let Them Theory: A Life-Changing Tool That Millions of People Cant Stop Talking About',
                'status' => 'approved',
                'is_available' => true,
                'cover_image'=>'book-covers/91ZVf3kNrcL._SY466_.jpg'
            ],
            [
                'title' => 'Dog Man: Big Jim Believes',
                'author' => 'Dav Pilkey',
                'isbn' => '978-1546176183',
                'description' => 'Dog Man: Big Jim Believes: A Graphic Novel (Dog Man #14): From the Creator of Captain Underpants',
                'status' => 'approved',
                'is_available' => true,
                 'cover_image'=>'book-covers/91cUuNGL-GL._SY466_.jpg'
            ],
            [
                'title' => 'The Primal Hunter 14',
                'author' => 'Zogarth',
                'isbn' => '978-7-121-12347-0',
                'description' => 'The Primal Hunter 14: A LitRPG Adventure Kindle',
                'status' => 'approved',
                'is_available' => true,
                'cover_image'=>'book-covers/81Z+ZLon5fL._SY385_.jpg'
            ],
            [
                'title' => 'Heart Life Music',
                'author' => 'Kenny Chesney,Holly Gleason',
                'isbn' => '978-0063423107',
                'description' => 'Heart Life Music is a love letter to the journey: all the places I’ve gone and how we got here. This book takes you on the ride.',
                'status' => 'approved',
                'is_available' => true,
                'cover_image'=>'book-covers/81-6OLc+HAL._SY385_.jpg'
            ],
              [
                'title' => 'Fae & Alchemy Book 3',
                'author' => 'Callie Hart',
                'isbn' => '978-1538774250',
                'description' => 'The rules have all been broken. Everything has changed. Can Saeris and Fisher save their kingdom, or is it already too late? The global phenomenon and #1 New York Times bestselling Fae & Alchemy series continues…',
                'status' => 'approved',
                'is_available' => true,
                'cover_image'=>'book-covers/61HSq7zlcyL._SY385_.jpg'
            ]
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
                'cover_image' => $bookData['cover_image'],
            ]);
            
            $createdCount++;
        }

        $this->command->info("图书种子数据创建完成！新创建 {$createdCount} 本图书。");
    }
}
