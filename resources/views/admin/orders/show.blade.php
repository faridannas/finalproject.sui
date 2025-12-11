<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-xl text-gray-800">Detail Pesanan #{{ $order->id }}</h2>
            <span class="text-sm text-gray-500 ml-4">{{ $order->created_at->format('d M Y, H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white overflow-hidden shadow-md sm:rounded-xl border border-gray-200">
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-6">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <h3 class="text-lg font-bold text-gray-900">Item Pesanan</h3>
                            </div>
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                        @else
                                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-lg text-gray-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total -->
                            <div class="mt-6 pt-6 border-t-2 border-gray-200 bg-gray-50 -mx-6 -mb-6 px-6 py-4 rounded-b-xl">
                                @if($order->discount > 0)
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Subtotal:</span>
                                        <span class="font-semibold">Rp {{ number_format($order->total_price + $order->discount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-green-600 mb-3">
                                        <span class="font-medium">Diskon ({{ $order->promo_code }}):</span>
                                        <span class="font-semibold">- Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-xl font-bold text-gray-900">
                                    <span>Total:</span>
                                    <span class="text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat Pengiriman</h3>
                            <p class="text-gray-700">{{ $order->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Order Info Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Pelanggan</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500">Nama</p>
                                    <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-semibold text-gray-900">{{ $order->user->email }}</p>
                                </div>
                                @if($order->user->phone)
                                    <div>
                                        <p class="text-sm text-gray-500">Telepon</p>
                                        <p class="font-semibold text-gray-900">{{ $order->user->phone }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Pesanan</h3>
                            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Dibayar</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Diproses</option>
                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors">
                                        Perbarui Status
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500">Status Saat Ini</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-2
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'processing') bg-indigo-100 text-indigo-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($order->status === 'pending') Menunggu
                                    @elseif($order->status === 'paid') Dibayar
                                    @elseif($order->status === 'processing') Diproses
                                    @elseif($order->status === 'shipped') Dikirim
                                    @elseif($order->status === 'completed') Selesai
                                    @elseif($order->status === 'completed') Selesai
                                    @elseif($order->status === 'cancelled') Dibatalkan
                                    @else {{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    @if($order->payment)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Info Pembayaran</h3>
                                <div class="space-y-3">
                                    <div>
                                        <p class="text-sm text-gray-500">Metode Pembayaran</p>
                                        <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold mt-1
                                            @if($order->payment->payment_status === 'success') bg-green-100 text-green-800
                                            @elseif($order->payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($order->payment->payment_status === 'success') Berhasil
                                            @elseif($order->payment->payment_status === 'pending') Menunggu
                                            @elseif($order->payment->payment_status === 'failed') Gagal
                                            @elseif($order->payment->payment_status === 'cancelled') Dibatalkan
                                            @else {{ ucfirst($order->payment->payment_status) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Jumlah</p>
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Pesanan</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <p class="text-gray-500">Dibuat</p>
                                    <p class="font-semibold text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Terakhir Diperbarui</p>
                                    <p class="font-semibold text-gray-900">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Back Button - Bottom Left -->
    <button onclick="window.history.back()" class="fixed bottom-8 left-8 inline-flex items-center px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-50">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span class="font-semibold">Kembali</span>
    </button>
</x-admin-layout>
