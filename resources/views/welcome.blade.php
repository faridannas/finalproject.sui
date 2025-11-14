<x-guest-layout>
    {{-- Header / Navbar --}}
    <header class="bg-gradient-to-r from-slate-900 via-orange-900 to-red-900 text-white sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto flex justify-between items-center p-4">
            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 text-2xl font-bold">
                <span>🍲</span>
                <span>Seblak Umi AI</span>
            </a>

            {{-- Search Bar --}}
            <div class="hidden md:flex flex-1 max-w-md mx-8">
                <form action="{{ route('products.index') }}" method="GET" class="w-full">
                    <div class="relative">
                        <div class="absolute left-3 flex items-center pointer-events-none h-full">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" name="search" placeholder="Cari seblak?"
                               class="search-input bg-slate-800/50 border-orange-800/50 text-white placeholder-gray-400 focus:ring-orange-500 focus:border-orange-500 pl-10 pr-4 w-full">
                    </div>
                </form>
            </div>

            <nav class="hidden md:flex items-center space-x-6 font-medium">
                <a href="{{ route('welcome') }}" class="text-white hover:text-orange-300 transition-colors">Home</a>
                <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-orange-400 transition-colors">Products</a>
                <a href="{{ route('categories.index') }}" class="text-gray-300 hover:text-orange-400 transition-colors">Categories</a>
                <a href="#reviews" class="text-gray-300 hover:text-orange-400 transition-colors">Reviews</a>
            </nav>

            <div class="flex-1"></div>

            <div class="flex items-center space-x-4">
                {{-- Cart Icon --}}
                @auth
                    <a href="{{ route('cart') }}" class="relative text-gray-300 hover:text-orange-400 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3">
                            </path>
                        </svg>
                        @if(Auth::check() && \App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                            </span>
                        @endif
                    </a>
                @endauth

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors">Logout</button>
                    </form>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-300 hover:text-orange-400 transition-colors text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-md transition-colors">Register</a>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="md:hidden border-t border-orange-800/50">
            <div class="container mx-auto px-4 py-3">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <div class="absolute left-3 flex items-center pointer-events-none h-full">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <input type="text" name="search" placeholder="Cari seblak?"
                               class="search-input bg-slate-800/50 border-orange-800/50 text-white placeholder-gray-400 focus:ring-orange-500 focus:border-orange-500 pl-10 pr-4 w-full">
                    </div>
                </form>
            </div>
        </div>
    </header>

    {{-- Hero Section with Background Image --}}
    <section id="hero" class="hero-background flex items-center justify-center relative h-[600px]"
             style="background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5)), url('{{ asset('images/seblakpremium.webp') }}'); background-size: cover; background-position: center;">
        <div class="gradient-overlay absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>
        <div class="hero-content container mx-auto px-4 py-20 text-center z-10">
            <p class="text-white font-semibold text-lg mb-2 animate-fade-in">🔥 Lagi Viral!</p>
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 animate-slide-up text-shadow-lg">
                Seblak Premium Autentik &amp; Pedas
            </h1>
            <p class="text-gray-100 mb-8 max-w-2xl mx-auto text-lg animate-fade-in text-shadow">
                Rasakan sensasi pedas yang bikin nagih! Dibuat dengan bahan premium dan resep rahasia turun-temurun.
                🌶️✨
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" class="hero-button">
                    Pesan Sekarang →
                </a>
                <a href="#featured"
                    class="bg-white text-primary px-8 py-3 rounded-lg hover:bg-gray-50 transition transform hover:scale-105 font-semibold">
                    Lihat Menu
                </a>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="bg-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="animate-fade-in">
                    <h3 class="text-3xl md:text-4xl font-bold text-primary mb-2">1000+</h3>
                    <p class="text-secondary">Orders</p>
                </div>
                <div class="animate-fade-in">
                    <h3 class="text-3xl md:text-4xl font-bold text-primary mb-2">4.9★</h3>
                    <p class="text-secondary">Rating</p>
                </div>
                <div class="animate-fade-in">
                    <h3 class="text-3xl md:text-4xl font-bold text-primary mb-2">500+</h3>
                    <p class="text-secondary">Reviews</p>
                </div>
                <div class="animate-fade-in">
                    <h3 class="text-3xl md:text-4xl font-bold text-primary mb-2">24/7</h3>
                    <p class="text-secondary">Service</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Products Section --}}
    <section id="featured" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <span class="inline-block bg-orange-100 text-primary px-4 py-2 rounded-full text-sm font-semibold mb-4">Menu Spesial</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Menu Favorit Pelanggan</h2>
                <p class="text-secondary text-lg max-w-2xl mx-auto">Pilih seblak favoritmu dari koleksi terbaik kami. Dibuat dengan bahan premium dan resep rahasia! 🌟</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $featuredProducts = \App\Models\Product::with('category')->take(6)->get();
                @endphp

                @forelse($featuredProducts as $product)
                    <div class="group relative bg-white rounded-2xl overflow-hidden shadow-lg transition-all duration-300 hover:shadow-xl animate-fade-in">
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
                            $imageSrc = asset('images/seblakpremium.webp'); // Default image

                            foreach ($productImages as $key => $url) {
                                if (strtolower($product->name) === strtolower($key)) {
                                    $imageSrc = $url;
                                    break;
                                }
                            }

                            // If no exact match, check for partial matches
                            if ($imageSrc === asset('images/seblakpremium.webp')) {
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
                                        $imageSrc = $url;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="relative overflow-hidden" style="height: 280px;">
                            <img src="{{ $imageSrc }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                 alt="{{ $product->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent opacity-40 transition-opacity group-hover:opacity-60"></div>
                            @if($product->stock < 10)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        Stok Terbatas
                                    </span>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4">
                                <span class="bg-white/90 backdrop-blur-sm text-primary px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $product->category->name }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-gray-600 mb-4 text-sm line-clamp-2">{{ $product->desc }}</p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-2xl">
                                        @for($i = 0; $i < min((int)str_replace('Level ', '', $product->category->name) ?? 1, 5); $i++)
                                            🌶️
                                        @endfor
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-lg font-bold text-primary">
                                        Rp {{ number_format((float)$product->price, 0, ',', '.') }}
                                    </span>
                                    <span class="text-sm text-gray-500">Tersedia: {{ $product->stock }}</span>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="{{ route('products.show', $product) }}"
                                    class="flex-1 bg-white border-2 border-primary text-primary px-4 py-2 rounded-xl hover:bg-primary hover:text-white transition-colors text-sm font-medium text-center">
                                    Lihat Detail
                                </a>
                                @auth
                                    <button wire:click="addToCart({{ $product->id }})"
                                        class="flex-1 bg-primary text-white px-4 py-2 rounded-xl hover:bg-primary/90 transition-colors text-sm font-medium">
                                        + Keranjang
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Produk sedang dimuat...</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" class="btn-seblak">
                    Lihat Semua Produk →
                </a>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    <section id="categories" class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Kategori Pilihan</h2>
            <p class="text-secondary mb-10 text-lg">Pilih level kepedasan sesuai seleramu! 🌶️</p>

            <div class="flex justify-center flex-wrap gap-4">
                <span class="bg-orange-100 text-primary px-6 py-3 rounded-full font-semibold animate-fade-in">
                    Level 1 - Pedas Manja
                </span>
                <span class="bg-orange-200 text-primary px-6 py-3 rounded-full font-semibold animate-fade-in">
                    Level 2 - Pedas Gila
                </span>
                <span class="bg-orange-300 text-primary px-6 py-3 rounded-full font-semibold animate-fade-in">
                    Level 3 - Pedas Neraka
                </span>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section id="reviews" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Apa Kata Pelanggan</h2>
                <p class="text-secondary text-lg">Pengalaman mereka dengan seblak kami</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                    $testimonials = \App\Models\Testimonial::with('user')->take(3)->get();
                @endphp

                @forelse($testimonials as $testimonial)
                    <div class="card-seblak animate-fade-in">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $testimonial->rating ? 'fill-current' : 'text-gray-300' }}"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700 mb-4 italic">"{{ $testimonial->comment }}"</p>
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($testimonial->user->name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $testimonial->user->name ?? 'Anonymous' }}</p>
                                    <p class="text-sm text-secondary">{{ $testimonial->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Testimonial sedang dimuat...</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('testimonials.index') }}" class="btn-seblak">
                    Lihat Semua Review →
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
   <footer class="bg-orange-600 text-white" style="background-color: #ea580c;">
   <footer class="bg-gradient-to-r from-slate-900 via-orange-900 to-red-900 text-white">
    <div class="container mx-auto px-4 py-12">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <span class="text-2xl">🍲</span>
                    <span class="text-xl font-bold text-white">Seblak UMI AI</span>
                </div>
                <p class="text-orange-50 mb-4 leading-relaxed">
                <p class="text-gray-300 text-sm mb-4 leading-relaxed">
                    Pedasnya bikin nagih! Seblak autentik dengan bahan premium.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-orange-100 hover:text-white transition-colors text-lg">📘</a>
                    <a href="#" class="text-orange-100 hover:text-white transition-colors text-lg">📷</a>
                    <a href="#" class="text-orange-100 hover:text-white transition-colors text-lg">🐦</a>
                    
                </div>
            </div>

            <div>
                
                <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="{{ route('products.index') }}" class="hover:text-orange-400 transition-colors">Products</a></li>
                    <li><a href="{{ route('categories.index') }}" class="hover:text-orange-400 transition-colors">Categories</a></li>
                    <li><a href="{{ route('cart') }}" class="hover:text-orange-400 transition-colors">Cart</a></li>
                    <li><a href="{{ route('testimonials.index') }}" class="hover:text-orange-400 transition-colors">Reviews</a></li>
                </ul>
            </div>

            <div>
               
                <h3 class="font-semibold text-lg mb-4">Support</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition-colors">Terms of Service</a></li>
                </ul>
            </div>

            <div>
                <ul class="space-y-2 text-orange-50">
                <h3 class="font-semibold text-lg mb-4">Contact Info</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li>📍 Jln.Buyut Nasirun</li>
                    <li>📞 +62 878 8031 7075</li>
                    <li>✉️ info@seblakumi.com</li>
                    <li>🕒 Mon-Sun: 08:00 - 22:00</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-orange-400 mt-8 pt-8 text-center">
            <p class="text-orange-50">&copy; {{ date('Y') }} Seblak UMI — Pedasnya Bikin Nagih 🔥</p>
    </div>
    
    </div>
</footer>



</x-guest-layout>
