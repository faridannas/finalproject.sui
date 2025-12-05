<x-app-layout>
    <!-- Custom Header for Checkout -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
            <a href="{{ route('cart') }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-2xl font-bold">Checkout</h1>
        </div>
    </div>

    <div class="space-y-8">
        <!-- Progress Steps -->
        <div class="modern-card rounded-2xl p-6">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 flex items-center justify-center text-white font-bold shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Keranjang</span>
                </div>
                <div class="w-16 h-1 bg-gradient-to-r from-green-500 to-orange-500"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-red-500 flex items-center justify-center text-white font-bold shadow-lg">
                        2
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Checkout</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold">
                        3
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-500">Pembayaran</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-2">
                <div class="modern-card rounded-2xl p-4 sm:p-6 lg:p-8">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ringkasan Pesanan
                    </h3>
                    <div class="space-y-3 mb-6">
                        @foreach($cartItems as $item)
                            <div class="flex items-center gap-3 p-3 sm:p-4 bg-gradient-to-br from-orange-50 to-red-50 rounded-xl border border-orange-200/50">
                                <!-- Product Image - Smaller for mobile -->
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-white shadow-sm">
                                    @if($item->product->image)
                                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-orange-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm sm:text-base font-bold text-gray-900 truncate">{{ $item->product->name }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                            {{ $item->product->category->name }}
                                        </span>
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-2 ml-2">
                                            <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                                    class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center hover:bg-orange-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                            </button>
                                            <span class="text-sm font-bold text-gray-900 w-6 text-center" id="item-quantity-{{ $item->id }}">{{ $item->quantity }}</span>
                                            <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                                    class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center hover:bg-orange-200 transition-colors">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Price -->
                                <div class="text-right flex-shrink-0">
                                    <div class="text-sm sm:text-lg font-bold text-gray-900" id="item-total-{{ $item->id }}">
                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Shipping Information Form -->
                    <div class="border-t border-gray-200 pt-6 sm:pt-8 mt-6 sm:mt-8">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6 flex items-center">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Informasi Pengiriman
                        </h3>
                        <form method="POST" action="{{ route('orders.store') }}" id="checkout-form">
                            @csrf

                            <!-- Address -->
                            <div class="mb-6">
                                <label for="address" class="block text-sm font-bold text-gray-700 mb-2">
                                    Alamat Pengiriman <span class="text-red-500">*</span>
                                </label>
                                <textarea name="address" id="address" rows="3" placeholder="Masukkan alamat pengiriman Anda..."
                                          class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-200 @error('address') border-red-500 @enderror" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-6">
                                <label for="payment_method" class="block text-sm font-bold text-gray-700 mb-2">
                                    Metode Pembayaran <span class="text-red-500">*</span>
                                </label>
                                <select name="payment_method" id="payment_method"
                                        class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-200 bg-white @error('payment_method') border-red-500 @enderror" required>
                                    <option value="">Pilih metode pembayaran</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                        🏦 Transfer Bank
                                    </option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                        💳 Kartu Kredit
                                    </option>
                                    <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>
                                        📱 E-Wallet (GoPay, OVO, Dana)
                                    </option>
                                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>
                                        💵 Bayar di Tempat (COD)
                                    </option>
                                </select>
                                @error('payment_method')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Promo Code -->
                            <div class="mb-6">
                                <label for="promo_code" class="block text-sm font-bold text-gray-700 mb-2">
                                    Kode Promo (Opsional)
                                </label>
                                <div class="flex gap-3">
                                    <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code', request('promo_code')) }}"
                                           class="flex-1 px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all duration-200" 
                                           placeholder="Masukkan kode promo">
                                    <button type="button" onclick="applyPromo()" 
                                            class="px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                        Terapkan
                                    </button>
                                </div>
                                @error('promo_code')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                @if(session('error'))
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ session('error') }}
                                    </p>
                                @endif
                            </div>

                            <script>
                                function applyPromo() {
                                    const promoCode = document.getElementById('promo_code').value;
                                    if (promoCode) {
                                        window.location.href = '{{ route("checkout") }}?promo_code=' + encodeURIComponent(promoCode);
                                    }
                                }
                            </script>

                            <!-- Action Buttons -->
                            <div class="flex gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('cart') }}" 
                                   class="flex-1 px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-colors text-center flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali ke Keranjang
                                </a>
                                <button type="submit" 
                                        class="flex-1 px-6 py-4 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Buat Pesanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Price Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="modern-card rounded-2xl p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Rincian Harga</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal (<span id="item-count">{{ $cartItems->count() }}</span> item)</span>
                            <span class="font-semibold" id="checkout-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <div id="promo-container" class="{{ isset($promo) && $promo ? '' : 'hidden' }}">
                            <div class="flex justify-between text-green-600 bg-green-50 -mx-6 px-6 py-3 rounded-lg">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Diskon <span id="promo-code-display">{{ isset($promo) ? '('.$promo->code.')' : '' }}</span>
                                </span>
                                <span class="font-bold" id="checkout-discount">- Rp {{ isset($promo) ? number_format(($total * $promo->discount) / 100, 0, ',', '.') : '0' }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>Ongkos Kirim</span>
                            <span class="font-semibold text-green-600">GRATIS</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total Pembayaran</span>
                                <div class="text-right">
                                    <div class="text-sm text-gray-400 line-through {{ isset($promo) ? '' : 'hidden' }}" id="checkout-original-total">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </div>
                                    <div class="text-2xl font-bold bg-gradient-to-r from-orange-600 to-red-500 bg-clip-text text-transparent" id="checkout-grand-total">
                                        Rp {{ isset($promo) ? number_format($total - (($total * $promo->discount) / 100), 0, ',', '.') : number_format($total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Badge -->
                    <div class="mt-6 p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200/50">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Transaksi Aman</p>
                                <p class="text-xs text-gray-600 mt-1">Data Anda dilindungi dengan enkripsi SSL</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helper to format currency
        function formatRupiah(number) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        }

        // Update Quantity Function
        function updateQuantity(cartId, newQuantity) {
            if (newQuantity < 1) return;

            // Get current promo code
            const promoCodeInput = document.getElementById('promo_code');
            const promoCode = promoCodeInput ? promoCodeInput.value : '';

            // Show loading state on the specific item controls only (optional, for better UX)
            // But user asked for speed, so maybe just do it silently or minimal indicator
            // Let's use a very subtle indicator or just disable buttons temporarily
            const buttons = document.querySelectorAll(`button[onclick*="updateQuantity(${cartId}"]`);
            buttons.forEach(btn => btn.disabled = true);

            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: newQuantity,
                    promo_code: promoCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update DOM elements directly
                    
                    // 1. Update item quantity display
                    const quantitySpan = document.getElementById(`item-quantity-${cartId}`);
                    if (quantitySpan) {
                        quantitySpan.innerText = newQuantity;
                    }

                    // Update minus button state
                    const minusBtn = document.querySelector(`button[onclick*="updateQuantity(${cartId}, ${newQuantity - 1})"]`); // This selector is tricky because onclick value changes.
                    // Better approach: find the button relative to the span or re-render buttons.
                    // For simplicity, let's just update the onclick attributes of the buttons next to the span
                    if (quantitySpan) {
                         const container = quantitySpan.parentElement;
                         const buttons = container.querySelectorAll('button');
                         if (buttons.length >= 2) {
                             // Minus button
                             buttons[0].setAttribute('onclick', `updateQuantity(${cartId}, ${newQuantity - 1})`);
                             if (newQuantity <= 1) {
                                 buttons[0].disabled = true;
                                 buttons[0].classList.add('opacity-50', 'cursor-not-allowed');
                             } else {
                                 buttons[0].disabled = false;
                                 buttons[0].classList.remove('opacity-50', 'cursor-not-allowed');
                             }
                             
                             // Plus button
                             buttons[1].setAttribute('onclick', `updateQuantity(${cartId}, ${newQuantity + 1})`);
                         }
                    }
                    
                    // 2. Update prices
                    if (document.getElementById(`item-total-${cartId}`)) {
                        document.getElementById(`item-total-${cartId}`).innerText = formatRupiah(data.item_total);
                    }
                    
                    if (document.getElementById('checkout-subtotal')) {
                        document.getElementById('checkout-subtotal').innerText = formatRupiah(data.subtotal);
                    }
                    
                    if (document.getElementById('checkout-discount') && data.discount > 0) {
                        document.getElementById('checkout-discount').innerText = '- ' + formatRupiah(data.discount);
                        document.getElementById('promo-container').classList.remove('hidden');
                        document.getElementById('checkout-original-total').classList.remove('hidden');
                        document.getElementById('checkout-original-total').innerText = formatRupiah(data.subtotal);
                    } else {
                        const promoContainer = document.getElementById('promo-container');
                        if (promoContainer) promoContainer.classList.add('hidden');
                        
                        const originalTotal = document.getElementById('checkout-original-total');
                        if (originalTotal) originalTotal.classList.add('hidden');
                    }
                    
                    if (document.getElementById('checkout-grand-total')) {
                        document.getElementById('checkout-grand-total').innerText = formatRupiah(data.grand_total);
                    }
                    
                } else {
                    alert(data.message || 'Gagal mengupdate keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // alert('Terjadi kesalahan');
            })
            .finally(() => {
                 buttons.forEach(btn => btn.disabled = false);
            });
        }

        // Form validation and loading state
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5 mr-2 animate-spin inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Memproses pesanan...
                `;
            }
        });
    </script>
</x-app-layout>

