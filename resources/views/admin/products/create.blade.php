<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-3">
                <button onclick="window.history.back()" class="group inline-flex items-center p-2 sm:px-4 sm:py-2 bg-white border border-gray-200 hover:bg-gray-50 hover:border-orange-200 text-gray-600 hover:text-orange-600 rounded-xl transition-all duration-200 shadow-sm">
                    <svg class="w-5 h-5 sm:mr-2 transform group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="hidden sm:inline font-medium">Back</span>
                </button>
                <div>
                    <h2 class="font-bold text-xl sm:text-2xl text-gray-800 leading-tight">
                        {{ __('Create Product') }}
                    </h2>
                    <p class="text-sm text-gray-500">Add a new item to your menu</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Left Column: Product Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 sm:p-8 space-y-6">
                                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-4">Product Information</h3>
                                
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                           class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 @error('name') border-red-500 @enderror" 
                                           placeholder="e.g. Seblak Original" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Category & Price Row -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Category -->
                                    <div>
                                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <div class="relative">
                                            <select name="category_id" id="category_id"
                                                    class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 appearance-none transition-colors duration-200 px-4 py-2.5 @error('category_id') border-red-500 @enderror" required>
                                                <option value="" disabled selected>Select Category</option>
                                                @foreach(\App\Models\Category::all() as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                        @error('category_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Price -->
                                    <div>
                                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                        <div class="relative rounded-xl shadow-sm">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                <span class="text-gray-500 text-sm font-medium">Rp</span>
                                            </div>
                                            <input type="number" name="price" id="price" value="{{ old('price') }}" min="0" step="100"
                                                   class="w-full rounded-xl border-2 border-gray-300 pl-12 pr-4 py-2.5 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 @error('price') border-red-500 @enderror" 
                                                   placeholder="0" required>
                                        </div>
                                        @error('price')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Stock -->
                                <div>
                                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock Availability</label>
                                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" min="0"
                                           class="w-full sm:w-1/2 rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 @error('stock') border-red-500 @enderror" required>
                                    @error('stock')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="desc" id="desc" rows="6"
                                              class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 @error('desc') border-red-500 @enderror" 
                                              placeholder="Describe your product..." required>{{ old('desc') }}</textarea>
                                    @error('desc')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Media & Actions -->
                    <div class="space-y-6">
                        <!-- Image Upload -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 space-y-4">
                                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-4">Product Image</h3>
                                
                                <div class="space-y-4">
                                    <!-- Preview Image -->
                                    <div class="relative aspect-square w-full bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden group">
                                        <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                                        <div id="image-placeholder" class="text-center p-4">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="mt-1 text-sm text-gray-500">No image uploaded</p>
                                        </div>
                                        
                                        <!-- Overlay for hover -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-200"></div>
                                    </div>

                                    <!-- File Input -->
                                    <div class="relative">
                                        <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(this)">
                                        <label for="image" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 cursor-pointer transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            Upload Image
                                        </label>
                                    </div>
                                    <p class="text-xs text-center text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    @error('image')
                                        <p class="text-sm text-red-600 text-center">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <div class="space-y-4">
                                <button type="submit" class="w-full flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 shadow-lg shadow-orange-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Create Product
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="w-full flex justify-center items-center px-6 py-3 border border-gray-200 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-admin-layout>
