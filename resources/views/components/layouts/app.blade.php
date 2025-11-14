<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Seblak Umi AI' }}</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        {{-- Custom CSS --}}
        <link rel="stylesheet" href="{{ asset('custom/css/custom-auth.css') }}">
        <link rel="stylesheet" href="{{ asset('css/additional-styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('css/hero-styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth-styles.css') }}">
        
        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-50 font-sans antialiased">
        {{-- Header / Navbar --}}
        <header class="bg-white shadow-bagisto sticky top-0 z-50">
            <div class="container mx-auto flex justify-between items-center p-4">
                <a href="{{ route('welcome') }}" class="flex items-center space-x-2 text-2xl font-bold text-primary">
                    <span>🍲</span>
                    <span>Seblak Umi AI</span>
                </a>

                <nav class="hidden md:flex space-x-6 text-secondary font-medium">
                    <a href="{{ route('welcome') }}" class="nav-link">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link">Products</a>
                    <a href="{{ route('categories.index') }}" class="nav-link">Categories</a>
                    <a href="#reviews" class="nav-link">Reviews</a>
                </nav>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('cart') }}" class="relative text-secondary hover:text-primary transition-colors">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3">
                                </path>
                            </svg>
                            <livewire:cart-component :show-count="true" />
                        </a>
                        
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="btn-seblak">Logout</button>
                        </form>
                    @else
                        <div class="space-x-3">
                            <a href="{{ route('login') }}" class="text-primary hover:underline">Login</a>
                            <a href="{{ route('register') }}" class="btn-seblak">Register</a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-orange-600 text-white mt-auto">
            <div class="container mx-auto px-4 py-8">
                <div class="text-center">
                    <p>&copy; {{ date('Y') }} Seblak UMI — Pedasnya Bikin Nagih 🔥</p>
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>