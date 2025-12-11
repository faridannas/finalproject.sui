<x-app-layout>
    <!-- Custom Header for Order Detail -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
            <a href="{{ route('transaction.history') }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-2xl font-bold">Detail Pesanan</h1>
        </div>
    </div>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pesanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500">ID Pesanan</p>
                                <p class="text-sm text-gray-900">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal Pesan</p>
                                <p class="text-sm text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Bayar</p>
                                <p class="text-sm text-gray-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Alamat Pengiriman</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $order->address }}</p>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if($order->payment)
                        <div class="mb-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Info Pembayaran</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Metode Bayar</p>
                                    <p class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Status Bayar</p>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($order->payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->payment->payment_status === 'success') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($order->payment->payment_status) }}
                                    </span>
                                </div>
                                @if($order->payment->transaction_id)
                                    <div class="md:col-span-2">
                                        <p class="text-sm font-medium text-gray-500">ID Transaksi</p>
                                        <p class="text-sm text-gray-900">{{ $order->payment->transaction_id }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Remove this as we already have Midtrans payment button below -->

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pesanan Kamu</h3>
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
                                        <p class="text-sm text-gray-500">Jumlah: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-500">Harga: Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
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

                    <!-- Payment Action -->
                    @if(auth()->user()->role !== 'admin' && $order->payment && $order->payment->payment_method !== 'cod')
                        @if($order->payment->payment_status === 'pending')
                            @if($order->payment->proof_of_payment)
                                <!-- Bukti sudah diupload, menunggu konfirmasi -->
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-yellow-800">Menunggu Konfirmasi Pembayaran</p>
                                            <p class="text-sm text-yellow-700">Bukti transfer Anda sedang diverifikasi oleh admin.</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Belum upload bukti -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-blue-800">Menunggu Pembayaran</p>
                                                <p class="text-sm text-blue-700">Silakan transfer dan upload bukti pembayaran.</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('payment.show', $order->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors shadow-sm">
                                            Upload Bukti
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @elseif($order->payment->payment_status === 'success')
                            <!-- Pembayaran berhasil -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <p class="font-semibold text-green-800">Pembayaran Berhasil</p>
                                        <p class="text-sm text-green-700">Pesanan Anda sedang diproses.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- Actions -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('transaction.history') }}" class="text-blue-600 hover:text-blue-500 font-medium flex items-center gap-2 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Riwayat Transaksi
                        </a>

                        <div class="flex space-x-4">
                            @if(auth()->user()->role === 'admin')
                                @if($order->status === 'pending')
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                            Tandai Sudah Bayar
                                        </button>
                                    </form>
                                @elseif($order->status === 'paid')
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="shipped">
                                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                                            Tandai Sudah Dikirim
                                        </button>
                                    </form>
                                @elseif($order->status === 'shipped')
                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                            Tandai Sudah Sampai
                                        </button>
                                    </form>
                                @endif
                            @else
                                <!-- User can cancel order if status is pending or paid -->
                                @if(in_array($order->status, ['pending', 'paid']))
                                    <form method="POST" action="{{ route('orders.cancel', $order) }}" class="inline" onsubmit="return confirm('Yakin mau batalin pesanan ini?');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @endif
                                <!-- Delete Button for Cancelled/Completed Orders -->
                                @if(in_array($order->status, ['cancelled', 'completed']))
                                    <form method="POST" action="{{ route('orders.destroy', $order) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus Riwayat
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
