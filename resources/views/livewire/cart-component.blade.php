<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($cartItems->isEmpty())
            <!-- Empty Cart State -->
            <div class="modern-card rounded-2xl p-12 text-center">
                <div class="w-32 h-32 mx-auto mb-6 bg-gradient-to-br from-orange-100 to-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
                <p class="text-gray-600 mb-6">Yuk, mulai belanja dan temukan produk favorit kamu!</p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @else
            <!-- Cart Items Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Cart Items List -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cartItems as $item)
                        <div class="modern-card rounded-xl p-4 hover:shadow-lg transition-all duration-200">
                            <div class="flex gap-4">
                                <!-- Product Image - Smaller Size -->
                                <div class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden bg-gray-100">
                                    @if($item->product->image)
                                        <img class="w-full h-full object-cover" 
                                             src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-bold text-gray-900 mb-1 truncate">
                                                {{ $item->product->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mb-2">
                                                {{ $item->product->category->name }}
                                            </p>
                                            
                                            <!-- Price -->
                                            <div class="flex items-baseline gap-2 mb-3">
                                                <span class="text-lg font-bold text-orange-600">
                                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <!-- Stock Info -->
                                            @if($item->product->stock > 0)
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Stok: {{ $item->product->stock }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                                    Stok Habis
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Remove Button -->
                                        <button wire:click="removeFromCart({{ $item->id }})" 
                                                class="flex-shrink-0 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Quantity Controls -->
                                    <div class="flex items-center gap-3 mt-3">
                                        <span class="text-sm text-gray-600">Jumlah:</span>
                                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                            <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold transition-colors {{ $item->quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <span class="px-4 py-1.5 bg-white text-gray-900 font-semibold min-w-[3rem] text-center">
                                                {{ $item->quantity }}
                                            </span>
                                            <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    class="px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold transition-colors {{ $item->quantity >= $item->product->stock ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <!-- Subtotal -->
                                        <div class="ml-auto">
                                            <span class="text-sm text-gray-500">Subtotal:</span>
                                            <span class="ml-2 text-lg font-bold text-gray-900">
                                                Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary Sidebar - Sticky -->
                <div class="lg:col-span-1">
                    <div class="modern-card rounded-xl p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-4 border-b border-gray-200">
                            Ringkasan Belanja
                        </h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Total Harga ({{ $cartItems->count() }} item)</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Ongkos Kirim</span>
                                <span class="font-semibold text-green-600">GRATIS</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900">Total Pembayaran</span>
                                <div class="text-right">
                                    <div class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-500 bg-clip-text text-transparent">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <a href="{{ route('checkout') }}" 
                               class="block w-full px-6 py-3.5 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg text-center">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Lanjut ke Checkout
                            </a>
                            <a href="{{ route('products.index') }}" 
                               class="block w-full px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors text-center">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Lanjut Belanja
                            </a>
                        </div>

                        <!-- Security Badge -->
                        <div class="mt-6 p-3 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-lg border border-blue-200/50">
                            <div class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <div>
                                    <p class="text-xs font-semibold text-gray-900">Belanja Aman</p>
                                    <p class="text-xs text-gray-600 mt-0.5">100% Jaminan Uang Kembali</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Toast Notifications -->
    @if (session()->has('success'))
        <div class="fixed bottom-8 right-8 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3" 
             style="animation: slideUp 0.3s ease-out;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-8 right-8 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3"
             style="animation: slideUp 0.3s ease-out;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif
</div>


