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
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>

        :root {
            --seblak-orange: #e67a00;
            --seblak-red: #ff4500;
            --seblak-yellow: #ff6b35;
            --seblak-dark: #1a1a1a;
            --seblak-gray: #374151;
        }

        html, body {
            font-family: 'Figtree', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            overflow-x: hidden;
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
    
    <!-- Navbar Animations CSS -->
    <link rel="stylesheet" href="{{ asset('css/navbar-animations.css') }}">
</head>
<body class="min-h-screen" style="margin: 0; padding: 0; width: 100%;">
    <!-- Background wrapper -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-50 via-orange-50 to-red-50 -z-10"></div>
    <!-- User Navigation -->
    @if(!request()->routeIs('dashboard') && !request()->routeIs('products.*') && !request()->routeIs('orders.*') && !request()->routeIs('cart') && !request()->routeIs('testimonials.*') && !request()->routeIs('checkout'))
        <x-user-navbar />
    @endif

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

    <!-- Main Content (No wrapper, full control to page) -->
    {{ $slot }}

    @stack('scripts')


    <script>


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
