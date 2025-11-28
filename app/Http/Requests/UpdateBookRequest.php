<?php

namespace App\Http\Requests;

use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;

/**
 * 更新图书请求验证类
 * 
 * 用于验证更新图书时的输入数据
 */
class UpdateBookRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     * 
     * 只有图书所有者或管理员才能更新图书
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        // 获取要更新的图书
        $book = $this->route('book');
        
        // 检查用户是否已登录
        if (!auth()->check()) {
            return false;
        }
        
        // 图书所有者或管理员可以更新
        return $book->user_id === auth()->id() || auth()->user()->isAdmin();
    }

    /**
     * 获取验证规则
     * 
     * 定义更新图书时需要验证的字段和规则
     * 注意：ISBN 的唯一性验证需要排除当前图书
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $book = $this->route('book'); // 获取当前要更新的图书
        
        return [
            'title' => 'required|string|max:255',                              // 标题：必填，字符串，最大255字符
            'author' => 'required|string|max:255',                             // 作者：必填，字符串，最大255字符
            'isbn' => 'nullable|string|max:255|unique:books,isbn,' . $book->id, // ISBN：可选，字符串，最大255字符，唯一性（排除当前图书）
            'description' => 'nullable|string',                               // 描述：可选，文本
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 封面图片：可选，图片格式，支持 jpeg/png/jpg/gif，最大2MB
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
            'title.required' => 'Please enter the book title',
            'title.max' => 'Book title cannot exceed 255 characters',
            'author.required' => 'Please enter the author name',
            'author.max' => 'Author name cannot exceed 255 characters',
            'isbn.unique' => 'This ISBN has already been used',
            'cover_image.image' => 'Cover must be an image file',
            'cover_image.mimes' => 'Cover image must be in jpeg, png, jpg, or gif format',
            'cover_image.max' => 'Cover image size cannot exceed 2MB',
        ];
    }
}
