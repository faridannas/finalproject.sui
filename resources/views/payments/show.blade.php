<x-app-layout>
    <!-- Custom Header for Payment -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
            <a href="{{ route('orders.show', $order->id) }}" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <h1 class="text-2xl font-bold">Pembayaran</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Informasi Rekening -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Selesaikan Pembayaran untuk Order #{{ $order->id }}</h3>
                        <p class="text-sm text-gray-500 mt-1">Silakan transfer total pembayaran ke rekening di bawah ini</p>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Dana -->
                        <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center shadow-sm mr-4">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/1200px-Logo_dana_blue.svg.png" alt="DANA" class="w-8 h-8 object-contain">
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">DANA E-Wallet</p>
                                <p class="text-xl font-bold text-gray-900 tracking-wide">0877 8192 8445</p>
                                <p class="text-xs text-gray-500">a.n. Seblak Umi</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-orange-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                        <p class="text-xs text-red-500">*Mohon transfer sesuai nominal hingga 3 digit terakhir.</p>
                    </div>
                </div>
            </div>

            <!-- Form Upload Bukti -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Pembayaran</h3>
                    
                    @if($order->payment->payment_status === 'success')
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-green-600">Pembayaran Berhasil!</h4>
                            <p class="text-gray-500 mt-2">Terima kasih, pesanan Anda sedang diproses.</p>
                            <a href="{{ route('orders.show', $order->id) }}" class="inline-block mt-6 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                Lihat Pesanan
                            </a>
                        </div>
                    @elseif($order->payment->proof_of_payment && $order->payment->payment_status === 'pending')
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-yellow-600">Menunggu Konfirmasi</h4>
                            <p class="text-gray-500 mt-2">Bukti pembayaran Anda sudah kami terima dan sedang diverifikasi oleh admin.</p>
                            <a href="{{ route('orders.show', $order->id) }}" class="inline-block mt-6 px-6 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                                Lihat Pesanan
                            </a>
                        </div>
                    @else
                        <form action="{{ route('payments.upload', $order->payment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank Pengirim (Opsional)</label>
                                <input type="text" name="bank_name" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Contoh: BCA, Mandiri, Dana">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening (Opsional)</label>
                                <input type="text" name="account_name" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500" placeholder="Nama pengirim">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-orange-500 transition-colors cursor-pointer" onclick="document.getElementById('proof_of_payment').click()">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <span class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                                <span>Upload file</span>
                                                <input id="proof_of_payment" name="proof_of_payment" type="file" class="sr-only" accept="image/*" required onchange="previewImage(this)">
                                            </span>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                                <div id="image-preview" class="mt-4 hidden">
                                    <img src="" alt="Preview" class="max-h-48 rounded-lg mx-auto shadow-md">
                                </div>
                            </div>

                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all transform hover:scale-[1.02]">
                                Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const previewImg = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
