@extends('layouts.app')

@section('title', 'Borrow Request Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Borrow Request Details</h1>
        
        <!-- Book Information -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">{{ $borrowRequest->book->title }}</h2>
            <p class="text-gray-600">Author: {{ $borrowRequest->book->author }}</p>
        </div>
        
        <!-- Request Information -->
        <div class="space-y-4 mb-6">
            <div>
                <span class="text-sm font-medium text-gray-500">Status:</span>
                <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full
                    @if($borrowRequest->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($borrowRequest->status === 'approved') bg-green-100 text-green-800
                    @elseif($borrowRequest->status === 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    @if($borrowRequest->status === 'pending') Pending
                    @elseif($borrowRequest->status === 'approved') Approved
                    @elseif($borrowRequest->status === 'rejected') Rejected
                    @else Returned
                    @endif
                </span>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">Borrower:</span>
                <span class="text-gray-900">{{ $borrowRequest->borrower->name }}</span>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">Book Owner:</span>
                <span class="text-gray-900">{{ $borrowRequest->owner->name }}</span>
            </div>
            
            <div>
                <span class="text-sm font-medium text-gray-500">Submitted:</span>
                <span class="text-gray-900">{{ $borrowRequest->created_at->format('Y-m-d H:i:s') }}</span>
            </div>
            
            @if($borrowRequest->request_message)
                <div>
                    <span class="text-sm font-medium text-gray-500">Request Message:</span>
                    <p class="mt-1 text-gray-900">{{ $borrowRequest->request_message }}</p>
                </div>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center space-x-4">
            @auth
                @if($borrowRequest->owner_id === auth()->id() && $borrowRequest->status === 'pending')
                    <!-- Book owner can process request -->
                    <form method="POST" action="{{ route('borrow-requests.handle', $borrowRequest->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                            Approve Request
                        </button>
                    </form>
                    <form method="POST" action="{{ route('borrow-requests.handle', $borrowRequest->id) }}" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md"
                                onclick="return confirm('Are you sure you want to reject this borrow request?');">
                            Reject Request
                        </button>
                    </form>
                @endif
                
                @if($borrowRequest->borrower_id === auth()->id() && $borrowRequest->status === 'pending')
                    <!-- Borrower can cancel request -->
                    <form method="POST" action="{{ route('borrow-requests.destroy', $borrowRequest->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md"
                                onclick="return confirm('Are you sure you want to cancel this borrow request?');">
                            Cancel Request
                        </button>
                    </form>
                @endif
            @endauth
            
            <a href="{{ route('borrow-requests.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
