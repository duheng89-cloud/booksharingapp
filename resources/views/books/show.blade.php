@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="md:flex">
            <!-- Left: Book Cover -->
            <div class="md:w-1/3">
                @if($book->cover_image)
                    <img src="{{ Storage::url($book->cover_image) }}" 
                         alt="{{ $book->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-8xl">📚</span>
                    </div>
                @endif
            </div>
            
            <!-- Right: Book Information -->
            <div class="md:w-2/3 p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $book->title }}</h1>
                
                <div class="space-y-4 mb-6">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Author:</span>
                        <span class="text-lg text-gray-900">{{ $book->author }}</span>
                    </div>
                    
                    @if($book->isbn)
                        <div>
                            <span class="text-sm font-medium text-gray-500">ISBN:</span>
                            <span class="text-lg text-gray-900">{{ $book->isbn }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Shared by:</span>
                        <span class="text-lg text-gray-900">{{ $book->owner->name }}</span>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        @if($book->status === 'pending')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @elseif($book->status === 'approved')
                            @if($book->is_available)
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    Available
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Borrowed
                                </span>
                            @endif
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                Rejected
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($book->description)
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $book->description }}</p>
                    </div>
                @endif
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if($book->user_id === auth()->id())
                            <!-- Book owner can edit and delete -->
                            <a href="{{ route('books.edit', $book->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('books.destroy', $book->id) }}" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this book?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                                    Delete
                                </button>
                            </form>
                        @elseif($book->isAvailable())
                            <!-- Other users can request to borrow -->
                            <a href="{{ route('books.borrow', $book->id) }}" 
                               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md">
                                Request to Borrow
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                            Login to Request Borrow
                        </a>
                    @endauth
                    
                    <a href="{{ route('books.index') }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
