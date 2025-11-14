<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Order ID</p>
                                <p class="text-sm text-gray-900">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Order Date</p>
                                <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'done') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Amount</p>
                                <p class="text-sm text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Shipping Information</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $order->address }}</p>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($order->payment)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Payment Method</p>
                                    <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Payment Status</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($order->payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment->payment_status === 'success') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($order->payment->payment_status) }}
                                    </span>
                                </div>
                                @if($order->payment->transaction_id)
                                    <div class="md:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">Transaction ID</p>
                                        <p class="text-sm text-gray-900">{{ $order->payment->transaction_id }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Remove this as we already have Midtrans payment button below -->

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>
                        <div class="space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center space-x-4 border border-gray-200 rounded-lg p-4">
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
                                        <p class="text-sm text-gray-500">Price: Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <div class="flex justify-between text-base font-medium text-gray-900">
                                <p>Total</p>
                                <p>Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    @if(auth()->user()->role !== 'admin' && (!$order->payment || $order->payment->payment_status === 'pending'))
                        <x-payment-button :order="$order" />
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('transaction.history') }}" class="text-blue-600 hover:text-blue-500 font-medium">
                            ← Back to Transaction History
                        </a>

                        @if(auth()->user()->role === 'admin')
                            <div class="flex space-x-4">
                                @if($order->status === 'pending')
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                            Mark as Paid
                                        </button>
                                    </form>
                                @elseif($order->status === 'paid')
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="shipped">
                                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                                            Mark as Shipped
                                        </button>
                                    </form>
                                @elseif($order->status === 'shipped')
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="done">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                            Mark as Delivered
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
