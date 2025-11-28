<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * 认证控制器
 * 
 * 处理用户注册、登录、登出等认证相关的操作
 */
class AuthController extends Controller
{
    /**
     * 显示注册页面
     * 
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        // 返回注册页面视图（后续实现）
        return view('auth.register');
    }

    /**
     * 处理用户注册
     * 
     * 使用 RegisterRequest 进行数据验证
     * 验证规则在 RegisterRequest 类中定义
     * 
     * @param RegisterRequest $request 注册请求对象（已通过验证）
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        // 获取已验证的数据（RegisterRequest 已经验证过了）
        $validated = $request->validated();

        // 创建新用户
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // 新用户默认为普通用户
        ]);

        // 自动登录新注册的用户
        Auth::login($user);

        // 重定向到首页
        return redirect()->route('books.index')->with('success', 'Registration successful! Welcome to Book Sharing Community');
    }

    /**
     * 显示登录页面
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // 返回登录页面视图（后续实现）
        return view('auth.login');
    }

    /**
     * 处理用户登录
     * 
     * 使用 LoginRequest 进行数据验证
     * 验证规则在 LoginRequest 类中定义
     * 
     * @param LoginRequest $request 登录请求对象（已通过验证）
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        // 获取已验证的数据（LoginRequest 已经验证过了）
        $credentials = $request->validated();

        // 尝试登录
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 根据用户角色重定向到不同页面
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome back, Administrator!');
            }

            return redirect()->route('books.index')->with('success', 'Login successful!');
        }

        // 登录失败，返回错误信息
        throw ValidationException::withMessages([
            'email' => ['Invalid email or password'],
        ]);
    }

    /**
     * 处理用户登出
     * 
     * @param Request $request HTTP请求对象
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // 登出用户
        Auth::logout();

        // 使会话无效
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 重定向到首页
        return redirect()->route('books.index')->with('success', 'Logged out successfully');
    }
}
