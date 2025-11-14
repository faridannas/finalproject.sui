<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-600 via-red-500 to-yellow-500 bg-clip-text text-transparent">
                    Menu Seblak Premium
                </h1>
                <p class="text-gray-600 mt-1">Temukan cita rasa autentik Indonesia</p>
            </div>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.products.create') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Menu Baru
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Filters and Search -->
        <div class="modern-card rounded-2xl p-6">
            <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    <!-- Category Filter -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select id="category-filter" class="w-full sm:w-48 px-4 py-3 rounded-xl border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all duration-200 bg-white shadow-sm">
                            <option value="">Semua Kategori</option>
                            @foreach(\App\Models\Category::all() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Input -->
                    <div class="relative flex-1 max-w-md">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Menu</label>
                        <div class="relative">
                            <input type="text" id="search-input" placeholder="Cari nama menu..." value="{{ request('search') }}"
                                   class="w-full px-4 py-3 pl-12 rounded-xl border border-gray-200 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 transition-all duration-200 bg-white shadow-sm">
                            <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button id="search-btn" class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold rounded-xl hover:from-orange-600 hover:to-red-600 transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
                <div class="modern-card rounded-2xl overflow-hidden hover-lift group animate-scale-in will-change-transform"
                     style="animation-delay: {{ $loop->index * 0.1 }}s">
                    <!-- Product Image -->
                    <div class="relative overflow-hidden h-64 bg-gradient-to-br from-orange-100 to-red-100">
                        @php
                            // Map product names to specific images in public/images folder
                            $productImages = [
                                'Seblak Seafood Special' => asset('images/Seblakseafood.jpg'),
                                'Seblak Kering Manis' => asset('images/seblakkering.jpg'),
                                'Seblak Kuah Komplit' => asset('images/seblakkuahkomplit.webp'),
                                'Seblak Super Pedas' => asset('images/seblaklevel2.webp'),
                                'Seblak Mie Jumbo' => asset('images/seblaklevel1.jpg'),
                                'Seblak Tulang' => asset('images/seblakpremium.webp'),
                            ];

                            // Check for exact matches first
                            $imageUrl = $product->image ? asset('storage/' . $product->image) : null;

                            if (!$imageUrl) {
                                foreach ($productImages as $key => $url) {
                                    if (strtolower($product->name) === strtolower($key)) {
                                        $imageUrl = $url;
                                        break;
                                    }
                                }
                            }

                            // If no exact match, check for partial matches
                            if (!$imageUrl) {
                                $partialImages = [
                                    'Seafood' => asset('images/Seblakseafood.jpg'),
                                    'Kering' => asset('images/seblakkering.jpg'),
                                    'Kuah Komplit' => asset('images/seblakkuahkomplit.webp'),
                                    'Super Pedas' => asset('images/seblaklevel2.webp'),
                                    'Mie Jumbo' => asset('images/seblaklevel1.jpg'),
                                    'Tulang' => asset('images/seblakpremium.webp'),
                                ];

                                foreach ($partialImages as $key => $url) {
                                    if (stripos(strtolower($product->name), strtolower($key)) !== false) {
                                        $imageUrl = $url;
                                        break;
                                    }
                                }
                            }

                            // Fallback to default image if no match found
                            if (!$imageUrl) {
                                $imageUrl = asset('images/seblakpremium.webp');
                            }
                        @endphp
                        <img src="{{ $imageUrl }}"
                             alt="{{ $product->name }} - Seblak premium dengan level kepedasan {{ $product->category->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 lazy loaded"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Stock Badge -->
                        @if($product->stock <= 0)
                            <div class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg animate-slide-down">
                                Habis
                            </div>
                        @elseif($product->stock <= 5)
                            <div class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg animate-slide-down">
                                Stok Terbatas
                            </div>
                        @else
                            <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg animate-slide-down">
                                Tersedia
                            </div>
                        @endif

                        <!-- Category Badge -->
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-sm font-medium shadow-lg animate-slide-down">
                            {{ $product->category->name }}
                        </div>
                    </div>

                <!-- Product Content -->
                <div class="p-6 space-y-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-orange-600 transition-colors duration-200 mb-2">
                            {{ $product->name }}
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            {{ Str::limit($product->desc, 120) }}
                        </p>
                    </div>

                    <!-- Price and Rating -->
                    <div class="flex items-center justify-between">
                        <div class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-500 bg-clip-text text-transparent">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>
                        <div class="flex items-center space-x-1">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="text-yellow-400 text-sm">⭐</span>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-500 ml-1">(4.9)</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4">
                        <a href="{{ route('products.show', $product) }}"
                           class="flex-1 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200 text-center group-hover:shadow-md">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Lihat Detail
                        </a>

                        @auth
                            @if($product->stock > 0)
                                <button wire:click="buyNow({{ $product->id }})"
                                        class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13l-1.1 5M7 13l1.1-5"></path>
                                    </svg>
                                    Beli Sekarang
                                </button>
                            @else
                                <div class="flex-1 px-4 py-3 bg-gray-300 text-gray-500 font-semibold rounded-xl cursor-not-allowed text-center">
                                    Stok Habis
                                </div>
                            @endif
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"
                               class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg text-center">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Masuk untuk Order
                            </a>
                        @endguest
                    </div>

                    <!-- Admin Actions -->
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="border-t border-gray-200 pt-4 mt-4">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="flex-1 px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium rounded-lg transition-colors duration-200 text-center text-sm">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-full px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 font-medium rounded-lg transition-colors duration-200 text-sm"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            @empty
                <div class="col-span-full">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v2m0 0v2m0-2h2m-2 0h-2"></path>
                            </svg>
                        </div>
                        <h3 class="empty-state-title">Menu Tidak Ditemukan</h3>
                        <p class="empty-state-description">Coba ubah kata kunci pencarian atau filter kategori Anda untuk menemukan menu favorit Anda.</p>
                        <button onclick="document.getElementById('search-input').value=''; document.getElementById('category-filter').value=''; document.getElementById('search-btn').click();"
                                class="empty-state-action focus-ring">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset Pencarian
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="flex justify-center pt-8">
                <div class="modern-card rounded-2xl p-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>

    <script>
        // Enhanced search functionality with loading states
        function performSearch() {
            const search = document.getElementById('search-input').value;
            const category = document.getElementById('category-filter').value;
            const searchBtn = document.getElementById('search-btn');
            const productsGrid = document.getElementById('products-grid');

            // Show loading state
            searchBtn.innerHTML = '<svg class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Cari...';
            searchBtn.disabled = true;

            // Add loading skeleton to products grid
            if (productsGrid) {
                productsGrid.innerHTML = `
                    <div class="col-span-full md:col-span-2 lg:col-span-3">
                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                            ${Array(6).fill().map(() => `
                                <div class="product-skeleton animate-pulse">
                                    <div class="image-skeleton"></div>
                                    <div class="content-skeleton">
                                        <div class="title-skeleton"></div>
                                        <div class="text-skeleton"></div>
                                        <div class="text-skeleton short"></div>
                                        <div class="flex gap-2 mt-4">
                                            <div class="button-skeleton flex-1"></div>
                                            <div class="button-skeleton flex-1"></div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            let url = '{{ route("products.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (category) url += 'category=' + category;

            window.location.href = url;
        }

        document.getElementById('search-btn').addEventListener('click', performSearch);

        document.getElementById('category-filter').addEventListener('change', performSearch);

        // Enhanced search input with debouncing
        let searchTimeout;
        document.getElementById('search-input').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    performSearch();
                }
            }, 500);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K to focus search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('search-input').focus();
            }
            // Escape to clear search
            if (e.key === 'Escape') {
                document.getElementById('search-input').value = '';
                performSearch();
            }
        });

        // Add loading states to Livewire actions
        document.addEventListener('livewire:loading', function () {
            // Add loading state to buttons that trigger Livewire actions
            const buttons = document.querySelectorAll('button[wire\\:click]');
            buttons.forEach(btn => {
                if (!btn.disabled) {
                    btn.innerHTML = '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
                    btn.disabled = true;
                }
            });
        });

        document.addEventListener('livewire:loaded', function () {
            // Restore button states
            const buttons = document.querySelectorAll('button[wire\\:click]');
            buttons.forEach(btn => {
                btn.disabled = false;
                // Restore original content (this is a simplified approach)
                if (btn.textContent.includes('animate-spin')) {
                    btn.innerHTML = btn.textContent.replace(/<svg[^>]*>.*?<\/svg>/, '').trim();
                }
            });
        });
    </script>

    {{-- Cart Component --}}
    @auth
        <livewire:cart-component />
    @endauth
</x-app-layout>
