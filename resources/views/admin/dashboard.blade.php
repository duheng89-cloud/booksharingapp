@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Users</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Books</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_books'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Pending Books</div>
            <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['pending_books'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Requests</div>
            <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_requests'] }}</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Pending Requests</div>
            <div class="mt-2 text-3xl font-bold text-yellow-600">{{ $stats['pending_requests'] }}</div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pending Books -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Pending Books</h2>
            </div>
            <div class="p-6">
                @if($pendingBooks->count() > 0)
                    <ul class="space-y-4">
                        @foreach($pendingBooks as $book)
                            <li class="flex items-center justify-between">
                                <div>
                                    <a href="{{ route('books.show', $book->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                        {{ $book->title }}
                                    </a>
                                    <p class="text-sm text-gray-500">Submitted by {{ $book->owner->name }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <form method="POST" action="{{ route('admin.books.review', $book->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.books.review', $book->id) }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Reject</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('admin.pending-books') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 text-sm">
                        View All →
                    </a>
                @else
                    <p class="text-gray-500">No pending books</p>
                @endif
            </div>
        </div>
        
        <!-- Pending Borrow Requests -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Pending Borrow Requests</h2>
            </div>
            <div class="p-6">
                @if($pendingRequests->count() > 0)
                    <ul class="space-y-4">
                        @foreach($pendingRequests as $request)
                            <li>
                                <a href="{{ route('borrow-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                    {{ $request->book->title }}
                                </a>
                                <p class="text-sm text-gray-500">
                                    {{ $request->borrower->name }} requested from {{ $request->owner->name }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('admin.borrow-requests') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 text-sm">
                        View All →
                    </a>
                @else
                    <p class="text-gray-500">No pending borrow requests</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users') }}" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center">
                <div class="text-blue-600 font-semibold">User Management</div>
            </a>
            <a href="{{ route('admin.pending-books') }}" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center">
                <div class="text-yellow-600 font-semibold">Book Review</div>
            </a>
            <a href="{{ route('admin.borrow-requests') }}" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center">
                <div class="text-green-600 font-semibold">Borrow Request Management</div>
            </a>
        </div>
    </div>
</div>
@endsection
