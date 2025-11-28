@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">User Management</h1>
    
    <!-- Search and Filter -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Search Box -->
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <!-- Role Filter -->
            <select name="role" 
                    class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            
            <!-- Search Button -->
            <button type="submit" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md">
                Search
            </button>
            
            <!-- Clear Button -->
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                    Clear
                </a>
            @endif
        </form>
    </div>
    
    <!-- User List -->
    @if($users->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registered
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.update-role', $user->id) }}" class="inline">
                                        @csrf
                                        <select name="role" 
                                                onchange="this.form.submit()"
                                                class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="text-gray-400">Current User</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg">No users found</p>
        </div>
    @endif
    
    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
