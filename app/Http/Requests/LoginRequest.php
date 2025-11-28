<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * 用户登录请求验证类
 * 
 * 用于验证用户登录时的输入数据
 */
class LoginRequest extends FormRequest
{
    /**
     * 确定用户是否有权限进行此请求
     * 
     * 登录功能对所有用户开放，无需登录
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // 所有人都可以登录
    }

    /**
     * 获取验证规则
     * 
     * 定义用户登录时需要验证的字段和规则
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',     // 邮箱：必填，有效邮箱格式
            'password' => 'required',         // 密码：必填
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
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Please enter your password',
        ];
    }
}
