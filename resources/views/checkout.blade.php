<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>
                            <div class="space-y-4">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-16 h-16">
                                            @if($item->product->image)
                                                <img class="w-16 h-16 rounded-md object-center object-cover" src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="border-t border-gray-200 mt-6 pt-6">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>Subtotal</p>
                                    <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                                </div>
                                @if(isset($promo) && $promo)
                                    <div class="flex justify-between text-base font-medium text-green-600">
                                        <p>Discount ({{ $promo->code }})</p>
                                        <p>- Rp {{ number_format(($total * $promo->discount) / 100, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <p>Total</p>
                                        <p>Rp {{ number_format($total - (($total * $promo->discount) / 100), 0, ',', '.') }}</p>
                                    </div>
                                @else
                                    <div class="flex justify-between text-lg font-bold text-gray-900">
                                        <p>Total</p>
                                        <p>Rp {{ number_format($total, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                            <form method="POST" action="{{ route('orders.store') }}">
                                @csrf

                                <!-- Address -->
                                <div class="mb-4">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                    <textarea name="address" id="address" rows="4" placeholder="Enter your full shipping address"
                                              class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('address') border-red-500 @enderror" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Payment Method -->
                                <div class="mb-4">
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                    <select name="payment_method" id="payment_method"
                                            class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50 @error('payment_method') border-red-500 @enderror" required>
                                        <option value="">Select payment method</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                        <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                                        <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Promo Code (Optional) -->
                                <div class="mb-4">
                                    <label for="promo_code" class="block text-sm font-medium text-gray-700">Promo Code (Optional)</label>
                                    <div class="flex space-x-2">
                                        <input type="text" name="promo_code" id="promo_code" value="{{ old('promo_code', request('promo_code')) }}"
                                               class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Enter promo code">
                                        <button type="button" onclick="applyPromo()" class="mt-1 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                            Apply
                                        </button>
                                    </div>
                                    @error('promo_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    @if(session('error'))
                                        <p class="mt-1 text-sm text-red-600">{{ session('error') }}</p>
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

                                <!-- Place Order Button -->
                                <div class="mt-6">
                                    <button type="submit" class="w-full bg-orange-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        Place Order
                                    </button>
                                </div>

                                <div class="mt-4 text-center">
                                    <a href="{{ route('cart') }}" class="text-sm text-gray-600 hover:text-gray-500">
                                        ← Back to Cart
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
