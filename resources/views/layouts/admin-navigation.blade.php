<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    @php
        $siteName = \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'Seblak UMI AI';
        $siteLogo = \App\Models\Setting::where('key', 'site_logo')->value('value');
    @endphp
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Logo + Nav Links (Desktop) OR Hamburger + Logo (Mobile) -->
            <div class="flex">
                <!-- Hamburger (Mobile Only - Leftmost) -->
                <div class="flex items-center sm:hidden mr-3">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Logo (Always Left, after hamburger on mobile) -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center space-x-2">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }} Logo" class="block h-10 w-auto object-contain rounded-lg">
                        @else
                            <img src="{{ asset('images/logoseblak.jpeg') }}" alt="{{ $siteName }} Logo" class="block h-10 w-10 object-contain rounded-lg">
                        @endif
                        <span class="font-bold text-gray-800 text-sm sm:text-base">{{ $siteName }}</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop Only) -->
                <div class="hidden space-x-4 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                        {{ __('Orders') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                        {{ __('Testimonials') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.banners.index')" :active="request()->routeIs('admin.banners.*')">
                        {{ __('Banners') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.promos.index')" :active="request()->routeIs('admin.promos.*')">
                        {{ __('Promos') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side: Notifications + User (Desktop & Mobile) -->
            <div class="flex items-center ml-6 space-x-3">
                <!-- Notifications Dropdown (Desktop & Mobile) -->
                @php
                    $pendingOrders = \App\Models\Order::with(['user', 'orderItems.product'])
                        ->where('status', 'pending')
                        ->latest()
                        ->take(5)
                        ->get();
                    $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                @endphp
                
                <x-dropdown align="right" width="w-80 sm:w-96">
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center px-2 sm:px-3 py-2 text-sm font-medium text-gray-700 hover:text-orange-600 transition-colors focus:outline-none">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            @if($pendingCount > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Header -->
                        <div class="px-4 py-3 bg-gradient-to-r from-orange-50 to-red-50 border-b border-orange-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-gray-800">Pesanan Baru</h3>
                                @if($pendingCount > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold text-orange-700 bg-orange-100 rounded-full">
                                        {{ $pendingCount }} pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Notifications List -->
                        <div class="max-h-96 overflow-y-auto">
                            @forelse($pendingOrders as $order)
                                <a href="{{ route('admin.orders.index') }}" wire:navigate class="block hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
                                    <div class="px-4 py-3">
                                        <!-- Header: Name & Time -->
                                        <div class="flex items-center justify-between gap-3 mb-2">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $order->user->name }}</p>
                                                    <p class="text-xs text-gray-500">Order #{{ $order->id }}</p>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">{{ $order->created_at->diffForHumans() }}</span>
                                        </div>
                                        
                                        <!-- Items -->
                                        <div class="mb-2">
                                            <p class="text-xs text-gray-600 line-clamp-2">
                                                @foreach($order->orderItems->take(2) as $item)
                                                    <span class="inline-flex items-center">
                                                        <svg class="w-3 h-3 mr-1 text-orange-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                                        </svg>
                                                        {{ $item->product->name }} ({{ $item->quantity }}x)
                                                    </span>{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                                @if($order->orderItems->count() > 2)
                                                    <span class="text-gray-400"> +{{ $order->orderItems->count() - 2 }} item</span>
                                                @endif
                                            </p>
                                        </div>
                                        
                                        <!-- Total -->
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Pending
                                            </span>
                                            <span class="text-sm font-bold text-orange-600">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="px-4 py-8 text-center">
                                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500 font-medium">Tidak ada pesanan baru</p>
                                </div>
                            @endforelse
                        </div>
                        
                        <!-- Footer -->
                        @if($pendingCount > 5)
                            <div class="border-t border-gray-100">
                                <a href="{{ route('admin.orders.index') }}" wire:navigate class="block px-4 py-3 text-center text-sm font-medium text-orange-600 hover:text-orange-700 hover:bg-orange-50 transition-colors">
                                    Lihat Semua Pesanan ({{ $pendingCount }}) →
                                </a>
                            </div>
                        @endif
                    </x-slot>
                </x-dropdown>

                <!-- User Dropdown (Desktop Only) -->
                <div class="hidden sm:block">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('admin.profile.edit')" class="text-sm">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile Only) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                {{ __('Products') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">
                {{ __('Orders') }}
                @php
                    $unread = \App\Models\Order::where('status', 'pending')->count();
                @endphp
                @if($unread > 0)
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-600 text-white">
                        {{ $unread }}
                    </span>
                @endif
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.testimonials.index')" :active="request()->routeIs('admin.testimonials.*')">
                {{ __('Testimonials') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.banners.index')" :active="request()->routeIs('admin.banners.*')">
                {{ __('Banners') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.promos.index')" :active="request()->routeIs('admin.promos.*')">
                {{ __('Promos') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>