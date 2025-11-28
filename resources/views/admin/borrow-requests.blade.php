@extends('layouts.app')

@section('title', 'Borrow Request Management')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Borrow Request Management</h1>
    
    <!-- Status Filter -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" action="{{ route('admin.borrow-requests') }}" class="flex flex-col sm:flex-row gap-4">
            <select name="status" 
                    class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
            </select>
            
            <button type="submit" 
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-md">
                Filter
            </button>
            
            @if(request('status'))
                <a href="{{ route('admin.borrow-requests') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                    Clear
                </a>
            @endif
        </form>
    </div>
    
    <!-- Borrow Requests List -->
    @if($borrowRequests->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($borrowRequests as $request)
                    <li class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        <a href="{{ route('borrow-requests.show', $request->id) }}" class="hover:text-blue-600">
                                            {{ $request->book->title }}
                                        </a>
                                    </h3>
                                    <!-- Status Badge -->
                                    <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full
                                        @if($request->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'approved') bg-green-100 text-green-800
                                        @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        @if($request->status === 'pending') Pending
                                        @elseif($request->status === 'approved') Approved
                                        @elseif($request->status === 'rejected') Rejected
                                        @else Returned
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="text-sm text-gray-600 space-y-1">
                                    <p>Author: {{ $request->book->author }}</p>
                                    <p>Borrower: {{ $request->borrower->name }} ({{ $request->borrower->email }})</p>
                                    <p>Book Owner: {{ $request->owner->name }} ({{ $request->owner->email }})</p>
                                    <p>Submitted: {{ $request->created_at->format('Y-m-d H:i:s') }}</p>
                                    @if($request->borrow_date)
                                        <p>Borrow Date: {{ $request->borrow_date }}</p>
                                    @endif
                                    @if($request->return_date)
                                        <p>Return Date: {{ $request->return_date }}</p>
                                    @endif
                                </div>
                                
                                @if($request->request_message)
                                    <div class="mt-3 p-3 bg-gray-50 rounded-md">
                                        <p class="text-sm text-gray-700">
                                            <strong>Message:</strong>{{ $request->request_message }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <a href="{{ route('borrow-requests.show', $request->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    View Details →
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $borrowRequests->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg">No borrow requests found</p>
            <a href="{{ route('admin.dashboard') }}" 
               class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                Back to Dashboard
            </a>
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
