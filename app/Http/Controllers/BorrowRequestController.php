<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBorrowRequestRequest;
use App\Models\BorrowRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 借阅请求控制器
 * 
 * 处理借阅请求相关的CRUD操作
 */
class BorrowRequestController extends Controller
{
    /**
     * 显示借阅请求列表
     * 
     * 根据用户角色显示不同的借阅请求列表
     * - 普通用户：显示自己提交的借阅请求
     * - 图书所有者：显示自己收到的借阅请求
     * - 管理员：显示所有借阅请求
     * 
     * @param Request $request HTTP请求对象
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->input('type', 'sent'); // sent=我提交的，received=我收到的

        if ($user->isAdmin()) {
            // 管理员可以查看所有借阅请求
            $borrowRequests = BorrowRequest::with(['book', 'borrower', 'owner'])
                ->latest()
                ->paginate(15);
        } elseif ($type === 'received') {
            // 显示用户收到的借阅请求（作为图书所有者）
            $borrowRequests = BorrowRequest::with(['book', 'borrower'])
                ->where('owner_id', $user->id)
                ->latest()
                ->paginate(15);
        } else {
            // 显示用户提交的借阅请求（作为借阅者）
            $borrowRequests = BorrowRequest::with(['book', 'owner'])
                ->where('borrower_id', $user->id)
                ->latest()
                ->paginate(15);
        }

        // 返回视图（后续实现）
        return view('borrow-requests.index', compact('borrowRequests', 'type'));
    }

    /**
     * 显示创建借阅请求表单
     * 
     * @param Book $book 图书模型实例
     * @return \Illuminate\View\View
     */
    public function create(Book $book)
    {
        // 检查图书是否可借阅
        if (!$book->isAvailable()) {
            return redirect()->route('books.show', $book->id)
                ->with('error', 'This book is not available for borrowing');
        }

        // 检查用户是否已经提交过借阅请求
        $existingRequest = BorrowRequest::where('book_id', $book->id)
            ->where('borrower_id', Auth::id())
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingRequest) {
            return redirect()->route('books.show', $book->id)
                ->with('error', 'You have already submitted a borrow request');
        }

        // 返回创建借阅请求表单视图（后续实现）
        return view('borrow-requests.create', compact('book'));
    }

    /**
     * 保存新创建的借阅请求
     * 
     * 使用 StoreBorrowRequestRequest 进行数据验证和授权检查
     * 验证规则、授权逻辑和重复请求检查都在 StoreBorrowRequestRequest 类中定义
     * 
     * @param StoreBorrowRequestRequest $request 创建借阅请求对象（已通过验证和授权）
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBorrowRequestRequest $request)
    {
        // 获取已验证的数据（StoreBorrowRequestRequest 已经验证过了）
        // 授权检查和重复请求检查也在 StoreBorrowRequestRequest 中完成
        $validated = $request->validated();

        // 获取图书
        $book = Book::findOrFail($validated['book_id']);

        // 创建借阅请求
        $borrowRequest = BorrowRequest::create([
            'book_id' => $book->id,
            'borrower_id' => Auth::id(),
            'owner_id' => $book->user_id,
            'status' => 'pending',
            'request_message' => $validated['request_message'] ?? null,
        ]);

        // 重定向到借阅请求详情页
        return redirect()->route('borrow-requests.show', $borrowRequest->id)
            ->with('success', 'Borrow request submitted successfully, waiting for book owner to process');
    }

    /**
     * 显示指定借阅请求的详细信息
     * 
     * @param BorrowRequest $borrowRequest 借阅请求模型实例
     * @return \Illuminate\View\View
     */
    public function show(BorrowRequest $borrowRequest)
    {
        // 检查权限：只有借阅者、图书所有者或管理员可以查看
        $user = Auth::user();
        if ($borrowRequest->borrower_id !== $user->id 
            && $borrowRequest->owner_id !== $user->id 
            && !$user->isAdmin()) {
            abort(403, 'You do not have permission to view this borrow request');
        }

        // 预加载关联数据
        $borrowRequest->load('book', 'borrower', 'owner');

        // 返回视图（后续实现）
        return view('borrow-requests.show', compact('borrowRequest'));
    }

    /**
     * 显示编辑借阅请求表单
     * 
     * 注意：借阅请求通常不允许编辑，只有管理员可以修改状态
     * 
     * @param BorrowRequest $borrowRequest 借阅请求模型实例
     * @return \Illuminate\View\View
     */
    public function edit(BorrowRequest $borrowRequest)
    {
        // 只有管理员可以编辑借阅请求
        if (!Auth::user()->isAdmin()) {
            abort(403, 'You do not have permission to edit this borrow request');
        }

        // 预加载关联数据
        $borrowRequest->load('book', 'borrower', 'owner');

        // 返回编辑表单视图（后续实现）
        return view('borrow-requests.edit', compact('borrowRequest'));
    }

    /**
     * 更新指定借阅请求的信息
     * 
     * @param Request $request HTTP请求对象
     * @param BorrowRequest $borrowRequest 借阅请求模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, BorrowRequest $borrowRequest)
    {
        $user = Auth::user();

        // 图书所有者可以处理借阅请求（同意/拒绝）
        if ($borrowRequest->owner_id === $user->id) {
            $validated = $request->validate([
                'status' => 'required|in:approved,rejected',
            ]);

            $borrowRequest->update([
                'status' => $validated['status'],
            ]);

            // 如果同意借阅，更新图书的可用状态
            if ($validated['status'] === 'approved') {
                $borrowRequest->book->update(['is_available' => false]);
            }

            return redirect()->route('borrow-requests.show', $borrowRequest->id)
                ->with('success', 'Borrow request processed successfully');
        }

        // 管理员可以修改借阅请求的所有信息
        if ($user->isAdmin()) {
            $validated = $request->validate([
                'status' => 'required|in:pending,approved,rejected,returned',
                'request_message' => 'nullable|string|max:1000',
                'borrow_date' => 'nullable|date',
                'return_date' => 'nullable|date',
            ]);

            $borrowRequest->update($validated);

            return redirect()->route('borrow-requests.show', $borrowRequest->id)
                ->with('success', 'Borrow request updated successfully');
        }

        abort(403, '无权更新此借阅请求');
    }

    /**
     * 删除指定借阅请求
     * 
     * @param BorrowRequest $borrowRequest 借阅请求模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(BorrowRequest $borrowRequest)
    {
        $user = Auth::user();

        // 只有借阅者本人或管理员可以删除借阅请求
        if ($borrowRequest->borrower_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You do not have permission to delete this borrow request');
        }

        // 如果借阅请求已同意，删除时需要恢复图书的可用状态
        if ($borrowRequest->status === 'approved' && $borrowRequest->book) {
            $borrowRequest->book->update(['is_available' => true]);
        }

        // 删除借阅请求
        $borrowRequest->delete();

        // 重定向到借阅请求列表
        return redirect()->route('borrow-requests.index')
            ->with('success', 'Borrow request deleted successfully');
    }

    /**
     * 处理借阅请求（同意或拒绝）
     * 
     * 这是图书所有者处理借阅请求的专用方法
     * 
     * @param Request $request HTTP请求对象
     * @param BorrowRequest $borrowRequest 借阅请求模型实例
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, BorrowRequest $borrowRequest)
    {
        // 检查权限：只有图书所有者可以处理
        if ($borrowRequest->owner_id !== Auth::id()) {
            abort(403, 'You do not have permission to process this borrow request');
        }

        // 验证输入
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        // 更新借阅请求状态
        $status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $borrowRequest->update(['status' => $status]);

        // 如果同意借阅，更新图书的可用状态
        if ($status === 'approved') {
            $borrowRequest->book->update(['is_available' => false]);
        }

        // 重定向到借阅请求详情页
        $message = $status === 'approved' ? 'Borrow request approved' : 'Borrow request rejected';
        return redirect()->route('borrow-requests.show', $borrowRequest->id)
            ->with('success', $message);
    }
}
