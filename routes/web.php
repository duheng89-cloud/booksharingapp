<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/**
 * Web 路由文件
 * 
 * 这里定义所有通过浏览器访问的路由
 * 这些路由会被 web 中间件组保护（包括 CSRF 保护、会话等）
 */

// ==================== 公开路由 ====================

// 首页重定向到图书列表
Route::get('/', function () {
    return redirect()->route('books.index');
});

// ==================== 认证路由 ====================

// 显示注册页面
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// 处理注册
Route::post('/register', [AuthController::class, 'register']);

// 显示登录页面
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
// 处理登录
Route::post('/login', [AuthController::class, 'login']);

// ==================== 图书路由（公开部分）====================

// 图书列表页面 - 未登录用户也可以访问
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// ==================== 需要认证的路由 ====================

Route::middleware('auth')->group(function () {
    
    // 处理登出
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // ==================== 图书路由（需要登录的操作）====================
    
    // 创建、编辑、删除图书需要登录
    // 注意：这些路由必须在 /books/{book} 之前定义，避免路由冲突
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::patch('/books/{book}', [BookController::class, 'update']); // 支持 PATCH 方法
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    
    // ==================== 借阅请求路由 ====================
    
    // 借阅请求资源路由（RESTful）- 所有操作都需要登录
    Route::resource('borrow-requests', BorrowRequestController::class);
    
    // 为特定图书创建借阅请求
    Route::get('/books/{book}/borrow', [BorrowRequestController::class, 'create'])
        ->name('books.borrow');
    
    // 处理借阅请求（同意/拒绝）
    Route::post('/borrow-requests/{borrowRequest}/handle', [BorrowRequestController::class, 'handle'])
        ->name('borrow-requests.handle');
    
    // ==================== 管理员路由 ====================
    
    Route::prefix('admin')->name('admin.')->group(function () {
        // 管理员仪表板
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // 用户管理
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])
            ->name('users.update-role');
        
        // 图书审核
        Route::get('/pending-books', [AdminController::class, 'pendingBooks'])
            ->name('pending-books');
        Route::post('/books/{book}/review', [AdminController::class, 'reviewBook'])
            ->name('books.review');
        
        // 借阅请求管理
        Route::get('/borrow-requests', [AdminController::class, 'borrowRequests'])
            ->name('borrow-requests');
    });
});

// ==================== 图书路由（公开部分 - 必须在最后）====================

// 图书详情页面 - 未登录用户也可以访问
// 注意：这个路由必须在 /books/create 之后定义，避免路由冲突
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
