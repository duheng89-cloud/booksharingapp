@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold mb-6">Edit Book</h2>
    
    <form method="POST" action="{{ route('books.update', $book->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Book Title -->
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                Book Title <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $book->title) }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Author -->
        <div class="mb-4">
            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                Author <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="author" 
                   name="author" 
                   value="{{ old('author', $book->author) }}"
                   required
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('author') border-red-500 @enderror">
            @error('author')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- ISBN -->
        <div class="mb-4">
            <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                ISBN (Optional)
            </label>
            <input type="text" 
                   id="isbn" 
                   name="isbn" 
                   value="{{ old('isbn', $book->isbn) }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('isbn') border-red-500 @enderror">
            @error('isbn')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Description -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Book Description (Optional)
            </label>
            <textarea id="description" 
                      name="description" 
                      rows="4"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $book->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Current Cover -->
        @if($book->cover_image)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Current Cover
                </label>
                <img src="{{ Storage::url($book->cover_image) }}" 
                     alt="Current Cover"
                     class="w-32 h-48 object-cover rounded-md border border-gray-300">
            </div>
        @endif
        
        <!-- Cover Image -->
        <div class="mb-6">
            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                Update Cover Image (Optional)
            </label>
            <input type="file" 
                   id="cover_image" 
                   name="cover_image" 
                   accept="image/jpeg,image/png,image/jpg,image/gif"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cover_image') border-red-500 @enderror">
            @error('cover_image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Supports JPG, PNG, GIF format, max 2MB</p>
        </div>
        
        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('books.show', $book->id) }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
