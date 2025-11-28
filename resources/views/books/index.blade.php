@extends('layouts.app')

@section('title', 'Books')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- 页面标题和操作按钮 -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Books</h1>
        @auth
            <a href="{{ route('books.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                + Share Book
            </a>
        @else
            <a href="{{ route('login') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                Login to Share Book
            </a>
        @endauth
    </div>
    
    <!-- 搜索和筛选 -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('books.index') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- 搜索框 -->
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by title, author, or ISBN..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- 状态筛选（仅管理员可见） -->
            @auth
                @if(auth()->user()->isAdmin())
                    <select name="status" 
                            class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                @endif
            @endauth
            
            <!-- 搜索按钮 -->
            <button type="submit" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md">
                Search
            </button>
            
            <!-- 清除按钮 -->
            @if(request('search') || request('status'))
                <a href="{{ route('books.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                    Clear
                </a>
            @endif
        </form>
    </div>
    
    <!-- 图书网格 -->
    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($books as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- 图书封面 -->
                    <a href="{{ route('books.show', $book->id) }}">
                        @if($book->cover_image)
                            <img src="{{ Storage::url($book->cover_image) }}" 
                                 alt="{{ $book->title }}"
                                 class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-4xl">📚</span>
                            </div>
                        @endif
                    </a>
                    
                    <!-- 图书信息 -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                            <a href="{{ route('books.show', $book->id) }}" class="hover:text-blue-600">
                                {{ $book->title }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">Author: {{ $book->author }}</p>
                        
                        @if($book->isbn)
                            <p class="text-xs text-gray-500 mb-2">ISBN: {{ $book->isbn }}</p>
                        @endif
                        
                        <!-- 状态标签 -->
                        <div class="flex items-center justify-between mt-3">
                            @if($book->status === 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @elseif($book->status === 'approved')
                                @if($book->is_available)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Available
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Borrowed
                                    </span>
                                @endif
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @endif
                            
                            <span class="text-xs text-gray-500">
                                Shared by {{ $book->owner->name }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- 分页 -->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg">No books found</p>
            @auth
                <a href="{{ route('books.create') }}" 
                   class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Be the first to share a book
                </a>
            @endauth
        </div>
    @endif
</div>
@endsection

