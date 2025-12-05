<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <button onclick="window.history.back()" class="inline-flex items-center p-2 sm:px-3 sm:py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline font-semibold">Back</span>
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-100 last:border-0">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                @if($order->discount > 0)
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Subtotal:</span>
                                        <span>Rp {{ number_format($order->total_price + $order->discount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-green-600 mb-2">
                                        <span>Discount ({{ $order->promo_code }}):</span>
                                        <span>- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Delivery Address</h3>
                            <p class="text-gray-700">{{ $order->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Info Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Info</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Name</p>
                                    <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $order->user->email }}</p>
                                </div>
                                @if($order->user->phone)
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="font-semibold text-gray-900">{{ $order->user->phone }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status</h3>
                            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Update Status
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500">Current Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-2
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'processing') bg-indigo-100 text-indigo-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    @if($order->payment)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Info</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Method</p>
                                        <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Payment Status</p>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-1
                                            @if($order->payment->payment_status === 'success') bg-green-100 text-green-800
                                            @elseif($order->payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($order->payment->payment_status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Amount</p>
                                        <p class="font-semibold text-gray-900">Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Bukti Transfer -->
                                @if($order->payment->proof_of_payment)
                                    <div class="mt-6 pt-6 border-t border-gray-100">
                                        <p class="text-sm font-bold text-gray-900 mb-3">Bukti Transfer</p>
                                        <a href="{{ asset('storage/' . $order->payment->proof_of_payment) }}" target="_blank" class="block">
                                            <img src="{{ asset('storage/' . $order->payment->proof_of_payment) }}" alt="Bukti Transfer" class="w-full rounded-lg border border-gray-200 hover:opacity-90 transition-opacity">
                                        </a>
                                        
                                        @if($order->payment->bank_name)
                                            <p class="text-sm text-gray-600 mt-2"><span class="font-medium">Bank:</span> {{ $order->payment->bank_name }}</p>
                                        @endif
                                        @if($order->payment->account_name)
                                            <p class="text-sm text-gray-600"><span class="font-medium">A.n:</span> {{ $order->payment->account_name }}</p>
                                        @endif

                                        <!-- Action Buttons -->
                                        @if($order->payment->payment_status === 'pending')
                                            <div class="mt-4 grid grid-cols-2 gap-3">
                                                <form action="{{ route('admin.payments.confirm', $order->payment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-2 rounded-lg transition-colors shadow-sm">
                                                        Konfirmasi
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.payments.reject', $order->payment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-2 rounded-lg transition-colors shadow-sm" onclick="return confirm('Yakin ingin menolak pembayaran ini?')">
                                                        Tolak
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Order Timeline -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Created</p>
                                    <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Last Updated</p>
                                    <p class="font-semibold text-gray-900">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
