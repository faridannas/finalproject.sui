<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ $title ?? config('app.name', 'Seblak UMI') }} - Seblak Premium Autentik</title>
    <meta name="description" content="{{ $description ?? 'Rasakan sensasi pedas yang bikin nagih! Seblak autentik dengan bahan premium dan resep turun-temurun. Pesan sekarang!' }}">
    <meta name="keywords" content="seblak, seblak umi, seblak pedas, kuliner indonesia, makanan pedas, seblak jakarta, seblak autentik">
    <meta name="author" content="Seblak UMI">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'Seblak UMI') }}">
    <meta property="og:description" content="{{ $description ?? 'Rasakan sensasi pedas yang bikin nagih! Seblak autentik dengan bahan premium.' }}">
    <meta property="og:image" content="{{ asset('images/seblak-umi-og.jpg') }}">
    <meta property="og:site_name" content="Seblak UMI">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? config('app.name', 'Seblak UMI') }}">
    <meta property="twitter:description" content="{{ $description ?? 'Rasakan sensasi pedas yang bikin nagih! Seblak autentik dengan bahan premium.' }}">
    <meta property="twitter:image" content="{{ asset('images/seblak-umi-twitter.jpg') }}">

    <!-- Performance & Mobile -->
    <meta name="theme-color" content="#ea580c">
    <meta name="msapplication-navbutton-color" content="#ea580c">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Seblak UMI">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.bunny.net">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --seblak-orange: #e67a00;
            --seblak-red: #ff4500;
            --seblak-yellow: #ff6b35;
            --seblak-dark: #1a1a1a;
            --seblak-gray: #374151;
        }

        body {
            font-family: 'Figtree', sans-serif;
        }

        .seblak-gradient {
            background: linear-gradient(135deg, var(--seblak-orange) 0%, var(--seblak-red) 100%);
        }

        .seblak-text {
            color: var(--seblak-orange);
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .modern-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-orange-50 to-red-50 min-h-screen">
    <!-- Modern Navigation -->
    <nav class="sticky top-0 z-50 glassmorphism border-b border-orange-200/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl seblak-gradient flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">🥘</span>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-orange-600 via-red-500 to-yellow-500 bg-clip-text text-transparent">
                            Seblak UMI
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 relative group px-3 py-2 rounded-lg hover:bg-orange-50">
                        Beranda
                        <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-orange-500 to-red-500 group-hover:w-3/4 transition-all duration-300"></span>
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 relative group px-3 py-2 rounded-lg hover:bg-orange-50">
                        Menu
                        <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-orange-500 to-red-500 group-hover:w-3/4 transition-all duration-300"></span>
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 relative group px-3 py-2 rounded-lg hover:bg-orange-50">
                            Dashboard
                            <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-0 h-0.5 bg-gradient-to-r from-orange-500 to-red-500 group-hover:w-3/4 transition-all duration-300"></span>
                        </a>
                        <a href="{{ route('cart') }}" class="relative p-2 text-gray-700 hover:text-orange-600 transition-colors duration-200 rounded-lg hover:bg-orange-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                            </svg>
                            @if(Auth::check() && \App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                    {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                                </span>
                            @endif
                        </a>
                    @endauth
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-12">
                    @auth
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Hi, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-orange-600 font-medium transition-colors duration-200 rounded-lg hover:bg-orange-50">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 transform hover:scale-105 shadow-lg">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-orange-600 p-2 transition-colors duration-200" id="mobile-menu-button">
                        <svg class="w-6 h-6 transition-transform duration-200" id="mobile-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-2 bg-white/90 backdrop-blur-md rounded-lg mt-2 shadow-lg border border-orange-200/20">
                    <a href="{{ route('welcome') }}" class="block px-3 py-2 text-gray-700 hover:text-orange-600 font-medium transition-colors rounded-lg hover:bg-orange-50">
                        Beranda
                    </a>
                    <a href="{{ route('products.index') }}" class="block px-3 py-2 text-gray-700 hover:text-orange-600 font-medium transition-colors rounded-lg hover:bg-orange-50">
                        Menu
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-orange-600 font-medium transition-colors rounded-lg hover:bg-orange-50">
                            Dashboard
                        </a>
                        <div class="px-3 py-2">
                            <a href="{{ route('cart') }}" class="relative p-2 text-gray-700 hover:text-orange-600 transition-colors duration-200 rounded-lg hover:bg-orange-50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                                </svg>
                                @if(Auth::check() && \App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                                    </span>
                                @endif
                            </a>
                        </div>
                        <div class="px-3 py-2 border-t border-gray-200 mt-3 pt-3">
                            <span class="text-sm text-gray-600 block mb-3">Hi, {{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="px-3 py-2 space-y-6">
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 hover:text-orange-600 font-medium transition-colors rounded-lg hover:bg-orange-50">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="block px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white font-medium rounded-lg text-center hover:from-orange-600 hover:to-red-600 transition-all duration-200">
                                Daftar
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    @if (isset($header))
        <header class="bg-white/80 backdrop-blur-md shadow-lg border-b border-orange-200/20">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-orange-600 via-red-500 to-yellow-500 bg-clip-text text-transparent">
                        {{ $header }}
                    </h1>
                    @if(isset($breadcrumb))
                        <nav class="text-sm text-gray-500">
                            {{ $breadcrumb }}
                        </nav>
                    @endif
                </div>
            </div>
        </header>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="modern-card rounded-2xl p-6 sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </main>

    @stack('scripts')

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-slate-900 via-orange-900 to-red-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-lg seblak-gradient flex items-center justify-center">
                            <span class="text-white font-bold">🥘</span>
                        </div>
                        <span class="text-lg font-bold">Seblak UMI</span>
                    </div>
                    <p class="text-gray-300 text-sm leading-relaxed">
                        Cita rasa Indonesia autentik dalam setiap gigitan. Dibuat dengan resep tradisional dan bahan pilihan terbaik.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Menu</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="{{ route('products.index') }}" class="hover:text-orange-400 transition-colors">Semua Menu</a></li>
                        <li><a href="{{ route('products.index', ['category' => 1]) }}" class="hover:text-orange-400 transition-colors">Level 1-3</a></li>
                        <li><a href="{{ route('products.index', ['category' => 4]) }}" class="hover:text-orange-400 transition-colors">Level 4-5</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Layanan</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Pengiriman</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Customer Service</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Lokasi</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li>📍 Tanggungsari, Brebes, Indonesia</li>
                        <li>📞 +62 87880317075</li>
                        <li>⏰ 10:00 - 22:00 WIB</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-orange-800/50 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2024 Seblak UMI AI. Dibuat dengan ❤️ untuk pecinta kuliner Indonesia.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle with animations
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('mobile-menu-icon');

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.style.maxHeight = '0px';
                menu.style.opacity = '0';
                setTimeout(() => {
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                    menu.style.opacity = '1';
                }, 10);

                // Animate hamburger to X
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            } else {
                menu.style.maxHeight = '0px';
                menu.style.opacity = '0';
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);

                // Animate X to hamburger
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = document.getElementById('mobile-menu-button');
            if (!menu.contains(event.target) && !button.contains(event.target) && !menu.classList.contains('hidden')) {
                menu.style.maxHeight = '0px';
                menu.style.opacity = '0';
                setTimeout(() => {
                    menu.classList.add('hidden');
                }, 300);

                const icon = document.getElementById('mobile-menu-icon');
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>';
            }
        });

        // Performance: Lazy load images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        });

        // Add loading states for forms
        document.addEventListener('submit', function(e) {
            const submitBtn = e.target.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Memproses...';
            }
        });
    </script>
</body>
</html>
