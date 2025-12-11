@props(['transparent' => false])

<header 
    class="navbar-sticky {{ $transparent ? 'bg-slate-900/95' : 'bg-slate-900' }} backdrop-blur-md text-white sticky top-0 z-50 shadow-lg transition-all duration-300 border-b border-white/10" 
    style="width: 100%; margin: 0; padding: 0;">
    <div class="w-full px-4 py-3 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            {{-- Logo --}}
            <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group">
                <div class="relative">
                    <div class="absolute inset-0 bg-orange-500 rounded-lg blur opacity-25 group-hover:opacity-50 transition-opacity"></div>
                    <img src="{{ asset('images/logoseblak.jpeg') }}" alt="Seblak Umi AI Logo" class="relative h-10 w-10 md:h-12 md:w-12 object-contain rounded-lg shadow-md transform group-hover:scale-105 transition-transform duration-300">
                </div>
                <span class="font-bold text-lg md:text-xl tracking-tight group-hover:text-orange-400 transition-colors">Seblak UMI AI</span>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex items-center space-x-1 bg-white/5 rounded-full px-2 py-1 border border-white/10 backdrop-blur-sm">
                <a href="{{ route('welcome') }}" wire:navigate class="nav-link px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:text-orange-400 {{ request()->routeIs('welcome') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-gray-300' }}">Home</a>
                <a href="{{ route('products.index') }}" wire:navigate class="nav-link px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:text-orange-400 {{ request()->routeIs('products.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-gray-300' }}">Menu</a>
                <a href="{{ route('promos.public') }}" wire:navigate class="nav-link px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:text-orange-400 {{ request()->routeIs('promos.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-gray-300' }}">Promo</a>
                <a href="{{ route('testimonials.index') }}" wire:navigate class="nav-link px-5 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:text-orange-400 {{ request()->routeIs('testimonials.*') ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-gray-300' }}">Reviews</a>
            </nav>

            {{-- Desktop Auth & Cart --}}
            <div class="hidden md:flex items-center space-x-5">
                {{-- Cart Icon --}}
                @auth
                    <a href="{{ route('cart') }}" class="relative group p-2">
                        <div class="absolute inset-0 bg-orange-500/20 rounded-full scale-0 group-hover:scale-100 transition-transform duration-300"></div>
                        <svg class="h-6 w-6 text-gray-300 group-hover:text-orange-400 transition-colors relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if(Auth::check() && \App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-sm border-2 border-slate-900">
                                {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                            </span>
                        @endif
                    </a>
                @endauth

                @auth
                    <div class="flex items-center gap-3 pl-3 border-l border-white/10">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Hi, {{ Auth::user()->name }}</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button class="px-4 py-2 bg-white/10 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-all duration-300 border border-white/10 hover:border-red-500 hover:shadow-lg hover:shadow-red-600/20">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-3 pl-3 border-l border-white/10">
                        <a href="{{ route('login') }}" wire:navigate class="text-gray-300 hover:text-white transition-colors text-sm font-medium px-2">Login</a>
                        <a href="{{ route('register') }}" wire:navigate class="px-5 py-2 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 text-white text-sm font-bold rounded-lg transition-all duration-300 shadow-lg shadow-orange-600/30 hover:shadow-orange-600/50 transform hover:-translate-y-0.5">Register</a>
                    </div>
                @endauth
            </div>

            {{-- Mobile Hamburger Button --}}
            <div class="md:hidden flex items-center gap-4">
                @auth
                    <a href="{{ route('cart') }}" class="relative text-gray-300 hover:text-orange-400 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if(Auth::check() && \App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center border border-slate-900">
                                {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                            </span>
                        @endif
                    </a>
                @endauth
                
                <button type="button" class="relative group p-2 rounded-lg bg-white/5 border border-white/10" id="mobile-menu-button">
                    <div class="absolute inset-0 bg-orange-500/20 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <svg class="w-6 h-6 text-white transition-transform duration-200 relative z-10" id="mobile-menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu (Redesigned) --}}
    <div id="mobile-menu" class="mobile-menu hidden md:hidden bg-slate-900/95 backdrop-blur-xl border-t border-white/10 absolute w-full left-0 shadow-2xl">
        <div class="px-4 py-6 space-y-6">
            {{-- Search Bar --}}
            <form action="{{ route('products.index') }}" method="GET" class="relative">
                <input type="text" name="search" placeholder="Mau makan apa hari ini?"
                       class="w-full bg-white/5 border border-white/10 text-white placeholder-gray-400 rounded-xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>

            {{-- Navigation Links --}}
            <nav class="grid grid-cols-4 gap-3">
                <a href="{{ route('welcome') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-white/5 hover:bg-orange-600/20 border border-white/5 hover:border-orange-500/30 transition-all group">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Home</span>
                </a>
                <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-white/5 hover:bg-orange-600/20 border border-white/5 hover:border-orange-500/30 transition-all group">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Menu</span>
                </a>
                <a href="{{ route('promos.public') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-white/5 hover:bg-orange-600/20 border border-white/5 hover:border-orange-500/30 transition-all group">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Promo</span>
                </a>
                <a href="{{ route('testimonials.index') }}" class="flex flex-col items-center justify-center p-4 rounded-xl bg-white/5 hover:bg-orange-600/20 border border-white/5 hover:border-orange-500/30 transition-all group">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-500 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-300 group-hover:text-white">Reviews</span>
                </a>
            </nav>

            {{-- Mobile User Menu --}}
            <div class="pt-6 border-t border-white/10">
                @auth
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-white font-semibold">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="text-sm text-red-400 hover:text-red-300 font-medium">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('login') }}" wire:navigate class="flex items-center justify-center px-4 py-3 rounded-xl bg-white/5 text-white font-medium hover:bg-white/10 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}" wire:navigate class="flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-orange-600 to-red-600 text-white font-bold shadow-lg shadow-orange-600/30 hover:shadow-orange-600/50 transition-all">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
