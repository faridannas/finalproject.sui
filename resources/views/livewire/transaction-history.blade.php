<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Transaction History</h1>

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Order #{{ $order->id }}</h3>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->status == 'done' ? 'bg-green-100 text-green-800' : ($order->status == 'shipped' ? 'bg-blue-100 text-blue-800' : ($order->status == 'paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-4">
                                <div class="space-y-4">
                                    @foreach($order->orderItems as $item)
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-md overflow-hidden">
                                                @if($item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $item->product->name }}</h4>
                                                <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                                            </div>
                                            <div class="text-sm font-medium text-gray-900">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @if($order->address)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Shipping Address</h4>
                                        <p class="text-sm text-gray-600">{{ $order->address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't made any purchases yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-500">Start shopping</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
