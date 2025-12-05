<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                {{ __('Manage Products') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center shadow-sm text-sm font-medium whitespace-nowrap">
                <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="hidden sm:inline">Add New Product</span>
                <span class="sm:hidden">Add</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Toolbar Section (Compact Mobile & Unified Desktop) -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <!-- Desktop Layout (Hidden on Mobile) -->
                <div class="hidden lg:flex flex-wrap items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative flex-grow min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input-desktop" placeholder="Search products..." value="{{ request('search') }}"
                            class="pl-10 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2">
                    </div>

                    <!-- Category Filter -->
                    <select id="category-filter-desktop" class="w-48 rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Search Button -->
                    <button id="search-btn-desktop" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium shadow-sm">
                        Search
                    </button>

                    <div class="h-8 w-px bg-gray-200 mx-1"></div>

                    <!-- Export Controls -->
                    <select id="stock-export-filter-desktop" class="w-40 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2">
                        <option value="">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>

                    <button id="export-products-btn-desktop" class="bg-white text-blue-600 border border-blue-200 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors flex items-center justify-center text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export
                    </button>
                </div>

                <!-- Mobile Layout (Visible only on Mobile/Tablet) -->
                <div class="lg:hidden flex flex-col gap-3">
                    <!-- Row 1: Search & Action -->
                    <div class="flex gap-2">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search-input-mobile" placeholder="Search..." value="{{ request('search') }}"
                                class="pl-10 w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2">
                        </div>
                        <button id="search-btn-mobile" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium shadow-sm">
                            Go
                        </button>
                    </div>

                    <!-- Row 2: Filters (Grid 2 Cols) -->
                    <div class="grid grid-cols-2 gap-2">
                        <select id="category-filter-mobile" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2">
                            <option value="">Category</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <select id="stock-export-filter-mobile" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2">
                            <option value="">Stock</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                        </select>
                    </div>

                    <!-- Row 3: Export Button -->
                    <button id="export-products-btn-mobile" class="w-full bg-white text-blue-600 border border-blue-200 px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors flex items-center justify-center text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export to PDF
                    </button>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                @if($product->image)
                                                    <img class="h-12 w-12 rounded-lg object-cover border border-gray-200" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                                @else
                                                    <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($product->desc, 40) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $product->stock }} {{ $product->stock <= 10 && $product->stock > 0 ? '(Low)' : '' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-3">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 flex items-center" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">No products found</p>
                                            <p class="text-gray-400 text-sm">Try adjusting your search or filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function performSearch(isMobile) {
            const searchInputId = isMobile ? 'search-input-mobile' : 'search-input-desktop';
            const categoryFilterId = isMobile ? 'category-filter-mobile' : 'category-filter-desktop';
            
            const search = document.getElementById(searchInputId).value;
            const category = document.getElementById(categoryFilterId).value;
            
            let url = '{{ route("admin.products.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (category) url += 'category=' + category;
            window.location.href = url;
        }

        function performExport(isMobile) {
            const categoryFilterId = isMobile ? 'category-filter-mobile' : 'category-filter-desktop';
            const stockFilterId = isMobile ? 'stock-export-filter-mobile' : 'stock-export-filter-desktop';

            const categoryId = document.getElementById(categoryFilterId).value;
            const stockStatus = document.getElementById(stockFilterId).value;

            let url = '{{ route("admin.reports.products.export") }}?';
            if (categoryId) url += 'category_id=' + categoryId + '&';
            if (stockStatus) url += 'stock_status=' + stockStatus;

            window.location.href = url;
        }

        // Desktop Events
        document.getElementById('search-btn-desktop')?.addEventListener('click', () => performSearch(false));
        document.getElementById('category-filter-desktop')?.addEventListener('change', () => performSearch(false));
        document.getElementById('export-products-btn-desktop')?.addEventListener('click', (e) => {
            e.preventDefault();
            performExport(false);
        });

        // Mobile Events
        document.getElementById('search-btn-mobile')?.addEventListener('click', () => performSearch(true));
        document.getElementById('category-filter-mobile')?.addEventListener('change', () => performSearch(true));
        document.getElementById('export-products-btn-mobile')?.addEventListener('click', (e) => {
            e.preventDefault();
            performExport(true);
        });

        // Enter key support for search inputs
        document.getElementById('search-input-desktop')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') performSearch(false);
        });
        document.getElementById('search-input-mobile')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') performSearch(true);
        });
    </script>
</x-admin-layout>
