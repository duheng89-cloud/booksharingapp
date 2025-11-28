<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 创建借阅请求验证类
 * 
 * 用于验证创建借阅请求时的输入数据
 */
class StoreBorrowRequestRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     * 
     * 只有登录用户才能创建借阅请求
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        // 检查用户是否已登录
        if (!auth()->check()) {
            return false;
        }
        
        // 获取要借阅的图书
        $bookId = $this->input('book_id');
        if (!$bookId) {
            return false;
        }
        
        $book = Book::find($bookId);
        if (!$book) {
            return false;
        }
        
        // 不能借阅自己的图书
        return $book->user_id !== auth()->id();
    }

    /**
     * 获取验证规则
     * 
     * 定义创建借阅请求时需要验证的字段和规则
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'book_id' => [
                'required',
                'exists:books,id',  // 必须存在于books表中
                // 自定义验证：确保图书可借阅
                Rule::exists('books', 'id')->where(function ($query) {
                    $query->where('status', 'approved')
                          ->where('is_available', true);
                }),
            ],
            'request_message' => 'nullable|string|max:1000', // 借阅留言：可选，字符串，最大1000字符
        ];
    }

    /**
     * 获取自定义验证错误消息
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'book_id.required' => 'Please select a book to borrow',
            'book_id.exists' => 'The book does not exist or is not available',
            'request_message.max' => 'Request message cannot exceed 1000 characters',
        ];
    }

    /**
     * 配置验证器实例
     * 
     * 可以在这里添加自定义验证逻辑
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $bookId = $this->input('book_id');
            
            if ($bookId) {
                // 检查是否已经提交过借阅请求
                $existingRequest = \App\Models\BorrowRequest::where('book_id', $bookId)
                    ->where('borrower_id', auth()->id())
                    ->whereIn('status', ['pending', 'approved'])
                    ->exists();
                
                if ($existingRequest) {
                    $validator->errors()->add('book_id', 'You have already submitted a borrow request for this book');
                }
            }
        });
    }
}
