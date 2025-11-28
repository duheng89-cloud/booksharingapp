<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Book;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 管理员控制器
 * 
 * 处理管理员相关的功能：
 * - 用户管理
 * - 图书审核
 * - 借阅请求管理
 */
class AdminController extends Controller
{
    /**
     * 构造函数
     * 
     * 确保只有管理员可以访问此控制器的所有方法
     */
    public function __construct()
    {
        // 使用中间件确保只有管理员可以访问
        $this->middleware('auth');
        
        // 在每个方法执行前检查是否是管理员
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || !Auth::user()->isAdmin()) {
                abort(403, 'You do not have permission to access admin functions');
            }
            return $next($request);
        });
    }

    /**
     * 显示管理员仪表板
     * 
     * 显示系统概览信息
     * 
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // 获取统计数据
        $stats = [
            'total_users' => User::count(),
            'total_books' => Book::count(),
            'pending_books' => Book::where('status', 'pending')->count(),
            'total_requests' => BorrowRequest::count(),
            'pending_requests' => BorrowRequest::where('status', 'pending')->count(),
        ];

        // 获取待审核的图书
        $pendingBooks = Book::with('owner')
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        // 获取待处理的借阅请求
        $pendingRequests = BorrowRequest::with(['book', 'borrower', 'owner'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        // 返回视图（后续实现）
        return view('admin.dashboard', compact('stats', 'pendingBooks', 'pendingRequests'));
    }

    /**
     * 显示用户列表
     * 
     * @param Request $request HTTP请求对象
     * @return \Illuminate\View\View
     */
    public function users(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $query = User::query();

        // 搜索功能
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 按角色筛选
        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->latest()->paginate(15);

        // 返回视图（后续实现）
        return view('admin.users', compact('users', 'search', 'role'));
    }

    /**
     * 更新用户角色
     * 
     * @param Request $request HTTP请求对象
     * @param User $user 用户模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin.users')
            ->with('success', 'User role updated successfully');
    }

    /**
     * 显示待审核的图书列表
     * 
     * @param Request $request HTTP请求对象
     * @return \Illuminate\View\View
     */
    public function pendingBooks(Request $request)
    {
        $books = Book::with('owner')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        // 返回视图（后续实现）
        return view('admin.pending-books', compact('books'));
    }

    /**
     * 审核图书
     * 
     * @param Request $request HTTP请求对象
     * @param Book $book 图书模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reviewBook(Request $request, Book $book)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        $status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $book->update(['status' => $status]);

        $message = $status === 'approved' ? 'Book approved successfully' : 'Book rejected';
        return redirect()->route('admin.pending-books')
            ->with('success', $message);
    }

    /**
     * 显示所有借阅请求
     * 
     * @param Request $request HTTP请求对象
     * @return \Illuminate\View\View
     */
    public function borrowRequests(Request $request)
    {
        $status = $request->input('status');

        $query = BorrowRequest::with(['book', 'borrower', 'owner']);

        if ($status) {
            $query->where('status', $status);
        }

        $borrowRequests = $query->latest()->paginate(15);

        // 返回视图（后续实现）
        return view('admin.borrow-requests', compact('borrowRequests', 'status'));
    }
}
