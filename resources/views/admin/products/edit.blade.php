<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Product: ') . $product->name }}
            </h2>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                Back to Products
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('category_id') border-red-500 @enderror" required>
                                <option value="">Select a category</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="desc" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="desc" id="desc" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('desc') border-red-500 @enderror" required>{{ old('desc', $product->desc) }}</textarea>
                            @error('desc')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price (Rp)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" min="0" step="100"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('price') border-red-500 @enderror" required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('stock') border-red-500 @enderror" required>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($product->image)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Current Image</label>
                                <div class="mt-1">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover rounded-md">
                                </div>
                            </div>
                        @endif

                        <!-- Image -->
                        <div class="mb-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">Update Product Image</label>
                            <input type="file" name="image" id="image" accept="image/*"
                                   class="mt-1 block w-full @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Leave empty to keep current image</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
