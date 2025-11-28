<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * 图书控制器
 * 
 * 处理图书相关的CRUD操作（创建、读取、更新、删除）
 */
class BookController extends Controller
{
    /**
     * 显示图书列表
     * 
     * 显示所有已审核通过的图书，供用户浏览
     * 
     * @param Request $request HTTP请求对象
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // 获取查询参数
        $search = $request->input('search'); // 搜索关键词
        $status = $request->input('status', 'approved'); // 默认只显示已审核的图书

        // 构建查询
        $query = Book::with('owner'); // 预加载图书所有者信息，提高查询效率

        // 如果用户是管理员，可以查看所有状态的图书
        if (Auth::check() && Auth::user()->isAdmin()) {
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
        } else {
            // 普通用户只能看到已审核通过的图书
            $query->where('status', 'approved')->where('is_available', true);
        }

        // 搜索功能
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        // 分页获取图书列表
        $books = $query->latest()->paginate(12);

        // 返回视图（后续实现）
        return view('books.index', compact('books', 'search', 'status'));
    }

    /**
     * 显示创建图书表单
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // 返回创建图书表单视图（后续实现）
        return view('books.create');
    }

    /**
     * 保存新创建的图书
     * 
     * 使用 StoreBookRequest 进行数据验证和授权检查
     * 验证规则和授权逻辑在 StoreBookRequest 类中定义
     * 
     * @param StoreBookRequest $request 创建图书请求对象（已通过验证和授权）
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBookRequest $request)
    {
        // 获取已验证的数据（StoreBookRequest 已经验证过了）
        $validated = $request->validated();

        // 处理图片上传
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        // 创建图书，状态默认为待审核
        $book = Book::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'author' => $validated['author'],
            'isbn' => $validated['isbn'] ?? null,
            'description' => $validated['description'] ?? null,
            'cover_image' => $validated['cover_image'] ?? null,
            'status' => 'pending', // 新图书需要管理员审核
            'is_available' => true,
        ]);

        // 重定向到图书详情页
        return redirect()->route('books.show', $book->id)->with('success', 'Book submitted successfully, waiting for admin review');
    }

    /**
     * 显示指定图书的详细信息
     * 
     * @param Book $book 图书模型实例
     * @return \Illuminate\View\View
     */
    public function show(Book $book)
    {
        // 检查权限：未登录用户只能查看已审核通过的图书
        if (!Auth::check()) {
            // 未登录用户只能查看已审核且可借阅的图书
            if ($book->status !== 'approved' || !$book->is_available) {
                abort(404, 'Book not found or not accessible');
            }
        } elseif (!Auth::user()->isAdmin()) {
            // 普通登录用户只能查看已审核通过的图书（无论是否可借阅）
            if ($book->status !== 'approved') {
                abort(404, 'Book not found or not accessible');
            }
        }
        // 管理员可以查看所有状态的图书

        // 预加载关联数据
        $book->load('owner', 'borrowRequests.borrower');

        // 返回图书详情视图
        return view('books.show', compact('book'));
    }

    /**
     * 显示编辑图书表单
     * 
     * @param Book $book 图书模型实例
     * @return \Illuminate\View\View
     */
    public function edit(Book $book)
    {
        // 检查权限：只有图书所有者或管理员可以编辑
        if ($book->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to edit this book');
        }

        // 返回编辑表单视图（后续实现）
        return view('books.edit', compact('book'));
    }

    /**
     * 更新指定图书的信息
     * 
     * 使用 UpdateBookRequest 进行数据验证和授权检查
     * 验证规则和授权逻辑在 UpdateBookRequest 类中定义
     * 
     * @param UpdateBookRequest $request 更新图书请求对象（已通过验证和授权）
     * @param Book $book 图书模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        // 获取已验证的数据（UpdateBookRequest 已经验证过了）
        // 授权检查也在 UpdateBookRequest 的 authorize() 方法中完成
        $validated = $request->validated();

        // 处理图片上传
        if ($request->hasFile('cover_image')) {
            // 删除旧图片
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('book-covers', 'public');
        }

        // 更新图书信息
        $book->update($validated);

        // 重定向到图书详情页
        return redirect()->route('books.show', $book->id)->with('success', 'Book information updated successfully');
    }

    /**
     * 删除指定图书
     * 
     * @param Book $book 图书模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Book $book)
    {
        // 检查权限：只有图书所有者或管理员可以删除
        if ($book->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to delete this book');
        }

        // 删除图书封面图片
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        // 删除图书（关联的借阅请求也会被删除，因为我们在模型中设置了关联）
        $book->delete();

        // 重定向到图书列表
        return redirect()->route('books.index')->with('success', 'Book deleted successfully');
    }
}
