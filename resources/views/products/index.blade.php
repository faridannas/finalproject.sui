@auth
    <x-app-layout>
        <!-- Custom Header for User Menu -->
        <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
                <button onclick="window.history.back()" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <h1 class="text-2xl font-bold">Menu Seblak</h1>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{-- Search & Filter for User --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- Category Filter --}}
                        <div class="w-full md:w-1/4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select id="category-filter-user" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm" onchange="performSearch('user')">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Search Input --}}
                        <div class="w-full md:w-3/4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Menu</label>
                            <div class="relative">
                                <input type="text" id="search-input-user" 
                                       class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm pl-10" 
                                       placeholder="Cari seblak favoritmu..." 
                                       value="{{ request('search') }}"
                                       onkeyup="debounceSearch('user')">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products Grid for User --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="group bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                            <div class="relative h-48 bg-gray-200 overflow-hidden">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/seblakpremium.webp') }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                
                                {{-- Hover Overlay --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                {{-- Stock Badges --}}
                                @if($product->stock <= 0)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Habis</div>
                                @elseif($product->stock <= 5)
                                    <div class="absolute top-2 right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded animate-pulse">Sisa {{ $product->stock }}</div>
                                @endif
                                
                                {{-- Quick Action Button (Desktop) --}}
                                <div class="absolute bottom-4 left-0 right-0 px-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden md:block">
                                    <a href="{{ route('products.show', $product) }}" class="block w-full bg-white text-slate-900 text-center py-2 rounded-xl font-bold hover:bg-orange-50 transition-colors">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-full">{{ $product->category->name }}</span>
                                        <h3 class="text-lg font-bold text-gray-900 mt-2 group-hover:text-orange-600 transition-colors">{{ $product->name }}</h3>
                                    </div>
                                    <div class="flex items-center bg-gray-50 px-2 py-1 rounded">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="text-sm font-bold text-gray-700">4.9</span>
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-1">{{ $product->desc }}</p>
                                
                                <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                                    <span class="text-xl font-bold text-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.buy-now') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all transform hover:scale-105 flex items-center shadow-lg">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13l-1.1 5M7 13l1.1-5"></path></svg>
                                                Pesan
                                            </button>
                                        </form>
                                    @else
                                        <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm font-semibold cursor-not-allowed">
                                            Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada menu ditemukan</h3>
                            <p class="mt-1 text-sm text-gray-500">Coba cari dengan kata kunci lain.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </x-app-layout>
@else
    <x-guest-layout>
        <x-navbar />

        <div class="container mx-auto px-4 py-8">
            <div class="mb-8 text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Menu Seblak Premium</h1>
                <p class="text-gray-600">Temukan cita rasa autentik Indonesia</p>
            </div>

            {{-- Filters and Search for Guest --}}
            <div class="modern-card rounded-2xl p-6 mb-8">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="w-full sm:w-48">
                        <select id="category-filter-guest" class="w-full rounded-xl border-gray-200 focus:border-orange-400 focus:ring-orange-100" onchange="performSearch('guest')">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 flex gap-2">
                        <input type="text" id="search-input-guest" 
                               class="w-full rounded-xl border-gray-200 focus:border-orange-400 focus:ring-orange-100" 
                               placeholder="Cari menu..." 
                               value="{{ request('search') }}"
                               onkeyup="debounceSearch('guest')">
                        <button onclick="performSearch('guest')" class="px-6 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                            Cari
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid for Guest -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($products as $product)
                    <div class="modern-card rounded-2xl overflow-hidden hover-lift group">
                        <div class="relative h-64 bg-gray-100">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/seblakpremium.webp') }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-medium">
                                {{ $product->category->name }}
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->desc }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-orange-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">
                                    Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Menu tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 flex justify-center">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>

        <x-footer />
    </x-guest-layout>
@endauth

<script>
    let searchTimeout;

    function debounceSearch(type) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(type);
        }, 500);
    }

    function performSearch(type) {
        const searchInput = document.getElementById(`search-input-${type}`);
        const categorySelect = document.getElementById(`category-filter-${type}`);
        
        if (!searchInput || !categorySelect) return;

        const search = searchInput.value;
        const category = categorySelect.value;

        let url = '{{ route("products.index") }}?';
        if (search) url += 'search=' + encodeURIComponent(search) + '&';
        if (category) url += 'category=' + category;

        window.location.href = url;
    }
</script>
