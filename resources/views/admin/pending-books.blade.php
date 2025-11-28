@extends('layouts.app')

@section('title', 'Pending Books')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Pending Books</h1>
    
    <!-- Book List -->
    @if($books->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Book Cover -->
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
                    
                    <!-- Book Information -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('books.show', $book->id) }}" class="hover:text-blue-600">
                                {{ $book->title }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-2">Author: {{ $book->author }}</p>
                        <p class="text-xs text-gray-500 mb-4">Submitted by {{ $book->owner->name }}</p>
                        
                        @if($book->description)
                            <p class="text-sm text-gray-700 mb-4 line-clamp-3">{{ $book->description }}</p>
                        @endif
                        
                        <!-- Review Actions -->
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('admin.books.review', $book->id) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.books.review', $book->id) }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                                        onclick="return confirm('Are you sure you want to reject this book?');">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $books->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500 text-lg">No pending books</p>
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
