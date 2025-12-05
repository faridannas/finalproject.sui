<x-guest-layout>
    {{-- Header / Navbar --}}
    <x-navbar :transparent="true" />

    {{-- Hero Section --}}
    <section id="hero" class="relative min-h-[600px] md:h-[700px] flex items-center justify-center overflow-hidden">
        {{-- Background Image with Parallax Effect --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/seblakpremium.webp') }}" alt="Seblak Premium" class="w-full h-full object-cover scale-105 animate-slow-zoom">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-slate-900/30"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 pt-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-500/20 border border-orange-500/30 backdrop-blur-sm mb-6 animate-fade-in-up">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                    </span>
                    <span class="text-orange-300 font-semibold text-sm tracking-wide uppercase">Lagi Viral Di TikTok 🔥</span>
                </div>

                <h1 class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight animate-fade-in-up delay-100 drop-shadow-2xl">
                    Pedasnya Bikin <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-red-500">Ketagihan!</span>
                </h1>

                <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed animate-fade-in-up delay-200">
                    Rasakan sensasi seblak autentik dengan bumbu rahasia dan topping premium melimpah. Siap menggoyang lidahmu?
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up delay-300">
                    <a href="{{ route('products.index') }}" class="group relative px-8 py-4 bg-gradient-to-r from-orange-600 to-red-600 rounded-full text-white font-bold text-lg shadow-xl shadow-orange-600/30 hover:shadow-orange-600/50 transition-all transform hover:-translate-y-1 overflow-hidden w-full sm:w-auto">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative flex items-center justify-center gap-2">
                            Pesan Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </span>
                    </a>
                    <a href="#featured" class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white font-semibold text-lg hover:bg-white/20 transition-all w-full sm:w-auto">
                        Lihat Menu
                    </a>
                </div>
            </div>
        </div>

        {{-- Floating Badge (Desktop Only) --}}
        <div class="hidden lg:block absolute bottom-10 right-10 animate-float">
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl shadow-2xl flex items-center gap-4 max-w-xs">
                <div class="bg-green-500/20 p-3 rounded-xl">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-lg">1000+ Terjual</p>
                    <p class="text-gray-400 text-sm">Minggu ini</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section (Modernized) --}}
    <section class="relative -mt-10 z-20 px-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 text-center transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3 text-orange-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800">1000+</h3>
                    <p class="text-slate-500 text-sm font-medium">Pesanan Selesai</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 text-center transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3 text-yellow-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800">4.9</h3>
                    <p class="text-slate-500 text-sm font-medium">Rating Rata-rata</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 text-center transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800">500+</h3>
                    <p class="text-slate-500 text-sm font-medium">Review Positif</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-xl shadow-slate-200/50 text-center transform hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-bold text-slate-800">24/7</h3>
                    <p class="text-slate-500 text-sm font-medium">Siap Melayani</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Products Section --}}
    <section id="featured" class="py-20 bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <span class="inline-block bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-sm font-bold mb-4 tracking-wide uppercase">Menu Spesial</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-4">Favorit Pelanggan</h2>
                <p class="text-slate-600 text-lg max-w-2xl mx-auto">Pilihan terbaik yang paling banyak dicari minggu ini. Jangan sampai kehabisan!</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($featuredProducts as $product)
                    <div class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 border border-slate-100 flex flex-col h-full">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/seblakpremium.webp') }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" 
                                 alt="{{ $product->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            {{-- Badges --}}
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product->stock < 10)
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg animate-pulse">
                                        Sisa {{ $product->stock }}!
                                    </span>
                                @endif
                            </div>
                            
                            <div class="absolute top-4 right-4">
                                <span class="bg-white/90 backdrop-blur-md text-slate-800 px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                                    {{ $product->category->name }}
                                </span>
                            </div>

                            {{-- Quick Action (Desktop) --}}
                            <div class="absolute bottom-4 left-0 right-0 px-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 hidden md:block">
                                <a href="{{ route('products.show', $product) }}" class="block w-full bg-white text-slate-900 text-center py-2 rounded-xl font-bold hover:bg-orange-50 transition-colors">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-slate-900 group-hover:text-orange-600 transition-colors line-clamp-1">
                                    {{ $product->name }}
                                </h3>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                    <span class="text-sm font-semibold text-slate-600">4.8</span>
                                </div>
                            </div>
                            
                            <p class="text-slate-500 text-sm mb-4 line-clamp-2 flex-1">{{ $product->desc }}</p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-slate-100">
                                <div>
                                    <span class="text-xs text-slate-400 block mb-1">Harga</span>
                                    <span class="text-lg font-black text-orange-600">
                                        Rp {{ number_format((float)$product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                @auth
                                    <button wire:click="addToCart({{ $product->id }})" class="bg-slate-900 text-white p-3 rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-slate-900/20 hover:shadow-orange-600/30">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                                        </svg>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="bg-slate-900 text-white p-3 rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-slate-900/20 hover:shadow-orange-600/30">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                                        </svg>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20">
                        <div class="inline-block p-6 bg-orange-50 rounded-full mb-4">
                            <svg class="w-12 h-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </div>
                        <p class="text-slate-500 text-lg">Belum ada produk yang tersedia.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white border border-slate-200 rounded-full text-slate-900 font-bold hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm hover:shadow-md">
                    Lihat Semua Menu
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Categories Section --}}
    <section id="categories" class="py-24 bg-white relative overflow-hidden">
        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-5 pointer-events-none">
            <div class="absolute top-0 left-0 w-full h-full" style="background-image: radial-gradient(#ea580c 1px, transparent 1px); background-size: 30px 30px;"></div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">
            <span class="text-orange-600 font-bold tracking-wider uppercase text-sm mb-2 block">Tantangan Pedas</span>
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">Pilih Level Pedasmu 🔥</h2>
            <p class="text-slate-600 mb-16 max-w-xl mx-auto text-lg">Sesuaikan tingkat kepedasan dengan keberanian lidahmu. Dari yang santai sampai yang bikin nangis!</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {{-- Level 1 --}}
                <div class="group relative p-8 rounded-[2rem] bg-white border-2 border-slate-100 hover:border-orange-400 transition-all duration-300 hover:shadow-2xl hover:shadow-orange-500/10">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-2 rounded-full border-2 border-slate-100 group-hover:border-orange-400 transition-colors">
                        <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform duration-300">
                            🥵
                        </div>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-orange-600 transition-colors">Level 1</h3>
                        <div class="inline-block px-3 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold mb-4">PEMULA</div>
                        <p class="text-slate-500 leading-relaxed">
                            Pedas nikmat yang masih sopan di lidah. Cocok buat kamu yang mau menikmati rasa seblak tanpa tersiksa.
                        </p>
                    </div>
                </div>

                {{-- Level 2 --}}
                <div class="group relative p-8 rounded-[2rem] bg-gradient-to-b from-orange-50 to-white border-2 border-orange-200 hover:border-red-500 transition-all duration-300 hover:shadow-2xl hover:shadow-red-500/20 transform md:-translate-y-4">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-2 rounded-full border-2 border-orange-200 group-hover:border-red-500 transition-colors">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-4xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                            🔥
                        </div>
                    </div>
                    <div class="mt-10">
                        <h3 class="text-3xl font-black text-slate-900 mb-2 group-hover:text-red-600 transition-colors">Level 2</h3>
                        <div class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold mb-4">MENANTANG</div>
                        <p class="text-slate-600 leading-relaxed font-medium">
                            Mulai bikin keringetan! Sensasi pedas nendang yang bikin nagih terus suapan demi suapan.
                        </p>
                    </div>
                </div>

                {{-- Level 3 --}}
                <div class="group relative p-8 rounded-[2rem] bg-white border-2 border-slate-100 hover:border-red-600 transition-all duration-300 hover:shadow-2xl hover:shadow-red-600/10">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white p-2 rounded-full border-2 border-slate-100 group-hover:border-red-600 transition-colors">
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform duration-300">
                            ☠️
                        </div>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-2xl font-black text-slate-900 mb-2 group-hover:text-red-700 transition-colors">Level 3</h3>
                        <div class="inline-block px-3 py-1 rounded-full bg-red-900 text-white text-xs font-bold mb-4">NERAKA</div>
                        <p class="text-slate-500 leading-relaxed">
                            Khusus para sultan pedas! Siapkan mental, tisu, dan minuman dingin. Berani coba?
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Section --}}
    <section id="reviews" class="py-20 bg-slate-900 relative overflow-hidden">
        {{-- Decorative Elements --}}
        <div class="absolute top-0 left-0 w-64 h-64 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-red-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Kata Mereka</h2>
                <p class="text-slate-400">Apa kata pelanggan setia Seblak UMI tentang pengalaman mereka.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse($testimonials as $testimonial)
                    <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-8 rounded-3xl hover:bg-white/10 transition-colors">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($testimonial->user?->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-white font-bold">{{ $testimonial->user?->name ?? 'Pelanggan' }}</h4>
                                <div class="flex text-yellow-400 text-xs">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($testimonial->rating ?? 5))
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-300 italic leading-relaxed">"{{ $testimonial->comment }}"</p>
                        <p class="text-slate-500 text-xs mt-4">{{ $testimonial->created_at?->diffForHumans() }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center text-slate-500">
                        Belum ada review. Jadilah yang pertama!
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('testimonials.index') }}" class="text-orange-400 hover:text-orange-300 font-semibold inline-flex items-center gap-2 transition-colors">
                    Baca Semua Review
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <x-footer />

    {{-- Custom Animations --}}
    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.8s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        
        @keyframes slow-zoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .animate-slow-zoom {
            animation: slow-zoom 20s linear infinite alternate;
        }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</x-guest-layout>