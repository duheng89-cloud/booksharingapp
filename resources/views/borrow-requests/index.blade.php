@extends('layouts.app')

@section('title', 'Borrow Requests')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Borrow Requests</h1>
    
    <!-- Tabs (Sent / Received) -->
    @auth
        @if(!auth()->user()->isAdmin())
            <div class="bg-white rounded-lg shadow mb-6 p-4">
                <div class="flex space-x-4">
                    <a href="{{ route('borrow-requests.index', ['type' => 'sent']) }}" 
                       class="px-4 py-2 rounded-md {{ request('type', 'sent') === 'sent' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        My Sent Requests
                    </a>
                    <a href="{{ route('borrow-requests.index', ['type' => 'received']) }}" 
                       class="px-4 py-2 rounded-md {{ request('type') === 'received' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        My Received Requests
                    </a>
                </div>
            </div>
        @endif
    @endauth
    
    <!-- Borrow Requests List -->
    @if($borrowRequests->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($borrowRequests as $request)
                    <li class="p-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
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
                                
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>Author: {{ $request->book->author }}</p>
                                    @if(request('type', 'sent') === 'sent')
                                        <p>Book Owner: {{ $request->owner->name }}</p>
                                    @else
                                        <p>Borrower: {{ $request->borrower->name }}</p>
                                    @endif
                                    <p>Submitted: {{ $request->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                                
                                @if($request->request_message)
                                    <p class="mt-2 text-sm text-gray-700">{{ $request->request_message }}</p>
                                @endif
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                <a href="{{ route('borrow-requests.show', $request->id) }}" 
                                   class="text-blue-600 hover:text-blue-800">
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
            <a href="{{ route('books.index') }}" 
               class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                Browse Books
            </a>
        </div>
    @endif
</div>
@endsection
