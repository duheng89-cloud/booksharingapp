<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Book Sharing Community')</title>
    
    <!-- 引入 Tailwind CSS CDN（用于快速构建美观的UI） -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- 自定义样式 -->
    <style>
        /* 自定义样式可以在这里添加 */
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- 导航栏 -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- 左侧：Logo和导航链接 -->
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('books.index') }}" class="text-2xl font-bold text-blue-600">
                            📚 Book Sharing Community
                        </a>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                        <a href="{{ route('books.index') }}" 
                           class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Books
                        </a>
                        @auth
                            <a href="{{ route('books.create') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Share Book
                            </a>
                            <a href="{{ route('borrow-requests.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Borrow Requests
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-red-600 hover:text-red-700 hover:border-red-300">
                                    Admin
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <!-- 右侧：用户菜单 -->
                <div class="flex items-center">
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">
                                Welcome, <span class="font-medium">{{ auth()->user()->name }}</span>
                            </span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('login') }}" 
                               class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            <a href="{{ route('register') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- 消息提示区域 -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative max-w-7xl mx-auto mt-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- 主内容区域 -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- 页脚 -->
    {{-- <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                © {{ date('Y') }} Book Sharing Community. All rights reserved.
            </p>
        </div>
    </footer> --}}

    @stack('scripts')
</body>
</html>

