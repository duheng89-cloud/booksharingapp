<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户注册请求验证类
 * 
 * 用于验证用户注册时的输入数据
 */
class RegisterRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     * 
     * 注册功能对所有用户开放，无需登录
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // 所有人都可以注册
    }

    /**
     * 获取验证规则
     * 
     * 定义用户注册时需要验证的字段和规则
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',                    // 姓名：必填，字符串，最大255字符
            'email' => 'required|string|email|max:255|unique:users', // 邮箱：必填，有效邮箱格式，最大255字符，唯一性
            'password' => 'required|string|min:8|confirmed',        // 密码：必填，最小8字符，需要确认密码
        ];
    }

    /**
     * 获取自定义验证错误消息
     * 
     * 可以自定义验证失败时显示的错误消息
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email has already been registered',
            'password.required' => 'Please enter a password',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'The passwords do not match',
        ];
    }
}
