<x-app-layout>

    <div class="bg-gray-50 min-h-screen pb-20">
        <!-- 1. User Profile Card (Full Width) -->
        <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-12 pt-8 text-white shadow-lg relative overflow-hidden rounded-b-[2.5rem]">
            <!-- Decorative Circles -->
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

            <div class="relative z-10 px-6 max-w-7xl mx-auto">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-16 h-16 bg-white rounded-full p-1 shadow-md flex-shrink-0">
                        @if(Auth::user()->avatar)
                             <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover" alt="Avatar">
                        @else
                            <div class="w-full h-full rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-xl font-bold truncate">{{ Auth::user()->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-medium backdrop-blur-sm border border-white/30">
                                Member Setia
                            </span>
                            <span class="text-xs opacity-90 truncate hidden sm:inline">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Access Menu -->
                <div class="grid grid-cols-4 gap-4">
                    <!-- Menu Seblak -->
                    <a href="{{ route('products.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="p-3 rounded-2xl bg-white/10 group-hover:bg-white/20 transition-all backdrop-blur-sm border border-white/20 shadow-sm group-hover:scale-105 transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-xs font-medium opacity-90">Menu</span>
                    </a>

                    <!-- Reviews -->
                    <a href="{{ route('testimonials.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="p-3 rounded-2xl bg-white/10 group-hover:bg-white/20 transition-all backdrop-blur-sm border border-white/20 shadow-sm group-hover:scale-105 transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <span class="text-xs font-medium opacity-90">Ulasan</span>
                    </a>

                    <!-- Cart -->
                    <a href="{{ route('cart') }}" class="flex flex-col items-center gap-2 group relative">
                        <div class="p-3 rounded-2xl bg-white/10 group-hover:bg-white/20 transition-all backdrop-blur-sm border border-white/20 shadow-sm group-hover:scale-105 transform duration-200 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            @if(Auth::check() && \App\Models\Cart::where('user_id', Auth::id())->count() > 0)
                                <span class="absolute -top-1 -right-1 bg-yellow-400 text-red-600 text-[10px] font-bold rounded-full h-4 w-4 flex items-center justify-center shadow-sm">
                                    {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs font-medium opacity-90">Keranjang</span>
                    </a>

                    <!-- Setting -->
                    <a href="{{ route('profile.edit') }}" class="flex flex-col items-center gap-2 group">
                        <div class="p-3 rounded-2xl bg-white/10 group-hover:bg-white/20 transition-all backdrop-blur-sm border border-white/20 shadow-sm group-hover:scale-105 transform duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="text-xs font-medium opacity-90">Pengaturan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10 space-y-6">
            
            <!-- 2. Order Status Tracker (The "Shopee" Bar) -->
            @php
                $pendingOrders = Auth::user()->orders()->where('status', 'pending')->count();
                $processingOrders = Auth::user()->orders()->where('status', 'processing')->count();
                $completedOrders = Auth::user()->orders()->where('status', 'completed')->count();
                $cancelledOrders = Auth::user()->orders()->where('status', 'cancelled')->count();
            @endphp

            <div class="bg-white rounded-2xl shadow-lg p-5">
                <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                    <h4 class="font-bold text-gray-800">Pesanan Saya</h4>
                    <a href="{{ route('orders.index') }}" class="text-xs text-orange-600 font-medium hover:text-orange-700 flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                <div class="grid grid-cols-4 gap-2 text-center">
                    <!-- Belum Bayar -->
                    <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="group flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-orange-50 transition-colors relative">
                        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            @if($pendingOrders > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $pendingOrders }}</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 font-medium group-hover:text-orange-600 leading-tight mt-1">Belum Bayar</span>
                    </a>

                    <!-- Dikemas/Diproses -->
                    <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="group flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-blue-50 transition-colors relative">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            @if($processingOrders > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $processingOrders }}</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 font-medium group-hover:text-blue-600 leading-tight mt-1">Dikemas</span>
                    </a>

                    <!-- Selesai -->
                    <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="group flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-green-50 transition-colors relative">
                        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @if($completedOrders > 0)
                                <span class="absolute -top-1 -right-1 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $completedOrders }}</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 font-medium group-hover:text-green-600 leading-tight mt-1">Selesai</span>
                    </a>

                    <!-- Dibatalkan -->
                    <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="group flex flex-col items-center gap-2 p-2 rounded-xl hover:bg-red-50 transition-colors relative">
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            @if($cancelledOrders > 0)
                                <span class="absolute -top-1 -right-1 bg-gray-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white shadow-sm">{{ $cancelledOrders }}</span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-600 font-medium group-hover:text-red-600 leading-tight mt-1">Dibatalkan</span>
                    </a>
                </div>
            </div>

            <!-- 3. Promo Banner (Carousel Mockup) -->
            <div class="relative rounded-2xl overflow-hidden shadow-lg group">
                <img src="{{ asset('images/seblakpremium.webp') }}" class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-700" alt="Promo">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent flex items-center px-8">
                    <div>
                        <span class="bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded mb-2 inline-block uppercase tracking-wider animate-pulse">Promo Spesial</span>
                        <h3 class="text-white text-2xl sm:text-3xl font-bold leading-tight mb-1">Gratis Ongkir!</h3>
                        <p class="text-gray-200 text-sm mb-4">Untuk pembelian di atas Rp 50rb</p>
                        <a href="{{ route('products.index') }}" class="bg-white text-orange-600 px-5 py-2 rounded-full text-sm font-bold hover:bg-orange-50 transition-colors inline-flex items-center gap-2 shadow-lg">
                            Cek Menu Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. Recent Orders (Simplified) -->
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Transaksi Terakhir
                </h4>
                
                @php
                    $recentOrders = Auth::user()->orders()->latest()->take(3)->get();
                @endphp

                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Order #{{ $order->id }}</p>
                                            <p class="text-xs text-gray-500 mb-1">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                            <div>
                                                @if($order->status === 'pending')
                                                    <span class="text-[10px] bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded-full font-medium">Menunggu Pembayaran</span>
                                                @elseif($order->status === 'paid')
                                                    <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-medium">Dibayar</span>
                                                @elseif($order->status === 'processing')
                                                    <span class="text-[10px] bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-medium">Diproses</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="text-[10px] bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-medium">Selesai</span>
                                                @elseif($order->status === 'cancelled')
                                                    <span class="text-[10px] bg-red-100 text-red-800 px-2 py-0.5 rounded-full font-medium">Dibatalkan</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-xs text-gray-500 hover:text-gray-900 mt-1 inline-block font-medium">Detail ></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <p class="text-gray-500 text-sm">Belum ada transaksi</p>
                        <a href="{{ route('products.index') }}" class="text-orange-600 text-sm font-bold hover:underline mt-2 inline-block">Mulai Belanja Sekarang</a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
