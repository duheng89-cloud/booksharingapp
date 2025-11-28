@extends('layouts.app')

@section('title', 'Request to Borrow')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6">Request to Borrow</h2>
    
    <!-- Book Information -->
    <div class="bg-gray-50 rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $book->title }}</h3>
        <p class="text-gray-600">Author: {{ $book->author }}</p>
        <p class="text-gray-600">Shared by: {{ $book->owner->name }}</p>
    </div>
    
    <form method="POST" action="{{ route('borrow-requests.store') }}">
        @csrf
        <!-- Hidden field: Pass book ID -->
        <input type="hidden" name="book_id" value="{{ $book->id }}">
        
        <!-- Request Message -->
        <div class="mb-6">
            <label for="request_message" class="block text-sm font-medium text-gray-700 mb-2">
                Request Message (Optional)
            </label>
            <textarea id="request_message" 
                      name="request_message" 
                      rows="4"
                      placeholder="You can explain your reason for borrowing or provide contact information here..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('request_message') border-red-500 @enderror">{{ old('request_message') }}</textarea>
            @error('request_message')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('books.show', $book->id) }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md">
                Submit Request
            </button>
        </div>
    </form>
</div>
@endsection
