<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 创建图书请求验证类
 * 
 * 用于验证创建图书时的输入数据
 */
class StoreBookRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     * 
     * 只有登录用户才能创建图书
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        // 检查用户是否已登录
        return auth()->check();
    }

    /**
     * 获取验证规则
     * 
     * 定义创建图书时需要验证的字段和规则
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',                              // 标题：必填，字符串，最大255字符
            'author' => 'required|string|max:255',                             // 作者：必填，字符串，最大255字符
            'isbn' => 'nullable|string|max:255|unique:books',                 // ISBN：可选，字符串，最大255字符，唯一性
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
