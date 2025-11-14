<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

        @if($cartItems->count() > 0)
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <div class="p-6 flex items-center">
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                                @if($item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">No Image</div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('products.show', $item->product->id) }}">{{ $item->product->name }}</a>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item->product->category->name }}</p>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900">Rp {{ number_format($item->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex items-center">
                                        <label for="quantity-{{ $item->id }}" class="mr-2 text-sm text-gray-600">Qty:</label>
                                        <input type="number" id="quantity-{{ $item->id }}" wire:model.live="quantities.{{ $item->id }}" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="w-16 text-center border border-gray-300 rounded-md" wire:change="updateQuantity({{ $item->id }}, $event.target.value)">
                                    </div>
                                    <button wire:click="removeFromCart({{ $item->id }})" class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-between text-lg font-medium">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('checkout') }}" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-center block">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5H19M7 13v8a2 2 0 002 2h10a2 2 0 002-2v-3"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-1 text-sm text-gray-500">Start adding some products to your cart.</p>
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-500">Continue Shopping</a>
                </div>
            </div>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-md shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>
