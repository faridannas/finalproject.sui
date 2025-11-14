<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Products') }}
            </h2>
            <div class="flex space-x-2">
                <div class="flex flex-col sm:flex-row gap-4">
                    <select id="category-export-filter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <select id="stock-export-filter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <option value="">All Stock Status</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock (≤10)</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                    <a href="#" id="export-products-btn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Export Products PDF
                    </a>
                </div>
                <a href="{{ route('admin.products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Add New Product
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="text" id="search-input" placeholder="Search products..." value="{{ request('search') }}"
                           class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                    <select id="category-filter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <button id="search-btn" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                        Search
                    </button>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex-shrink-0 w-16 h-16">
                                                @if($product->image)
                                                    <img class="w-16 h-16 rounded-md object-center object-cover" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                                @else
                                                    <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($product->desc, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $product->category->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No products found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="px-6 py-4">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('search-btn').addEventListener('click', function() {
            const search = document.getElementById('search-input').value;
            const category = document.getElementById('category-filter').value;
            let url = '{{ route("admin.products.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (category) url += 'category=' + category;
            window.location.href = url;
        });

        document.getElementById('category-filter').addEventListener('change', function() {
            const search = document.getElementById('search-input').value;
            const category = this.value;
            let url = '{{ route("admin.products.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (category) url += 'category=' + category;
            window.location.href = url;
        });

        document.getElementById('export-products-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const categoryId = document.getElementById('category-export-filter').value;
            const stockStatus = document.getElementById('stock-export-filter').value;

            let url = '{{ route("admin.reports.products.export") }}?';
            if (categoryId) url += 'category_id=' + categoryId + '&';
            if (stockStatus) url += 'stock_status=' + stockStatus;

            window.location.href = url;
        });
    </script>
</x-app-layout>
