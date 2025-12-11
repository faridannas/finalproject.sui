<x-guest-layout>
    {{-- Navbar --}}
    <x-navbar :transparent="false" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Promo Spesial
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Gunakan kode promo di bawah ini saat checkout untuk mendapatkan diskon menarik!
                </p>
            </div>

            @if($promos->count() > 0)
                {{-- Promo Cards Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    @foreach($promos as $promo)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 border-2 border-orange-200">
                            {{-- Promo Header --}}
                            <div class="bg-gradient-to-r from-orange-500 to-red-600 p-6 text-white">
                                <div class="flex items-center justify-between mb-2">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    <span class="text-3xl font-bold">{{ $promo->discount }}%</span>
                                </div>
                                <p class="text-sm opacity-90">Diskon Spesial</p>
                            </div>

                            {{-- Promo Body --}}
                            <div class="p-6">
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide mb-2">Kode Promo</p>
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3 border-2 border-dashed border-orange-300">
                                        <span class="text-2xl font-bold text-gray-900 tracking-wider" id="code-{{ $promo->id }}">{{ $promo->code }}</span>
                                        <button onclick="copyCode('{{ $promo->code }}', {{ $promo->id }})" 
                                                class="text-orange-600 hover:text-orange-700 transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-green-600 mt-2 hidden" id="copied-{{ $promo->id }}">✓ Kode berhasil disalin!</p>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>Berlaku sampai: <strong>{{ $promo->valid_until->format('d M Y') }}</strong></span>
                                    </div>
                                </div>

                                <a href="{{ route('products.index') }}" 
                                   class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-colors duration-200">
                                    Belanja Sekarang
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- How to Use Section --}}
                <div class="bg-white rounded-2xl shadow-lg p-8 max-w-3xl mx-auto">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Cara Menggunakan Kode Promo
                    </h3>
                    <ol class="space-y-4 text-gray-700">
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold mr-4">1</span>
                            <div>
                                <strong class="text-gray-900">Pilih Produk</strong>
                                <p class="text-sm text-gray-600">Tambahkan produk seblak favorit Anda ke keranjang</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold mr-4">2</span>
                            <div>
                                <strong class="text-gray-900">Salin Kode Promo</strong>
                                <p class="text-sm text-gray-600">Klik tombol salin pada kode promo yang ingin digunakan</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="flex-shrink-0 w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold mr-4">3</span>
                            <div>
                                <strong class="text-gray-900">Checkout</strong>
                                <p class="text-sm text-gray-600">Masukkan kode promo saat checkout dan nikmati diskonnya!</p>
                            </div>
                        </li>
                    </ol>
                </div>
            @else
                {{-- No Promos Available --}}
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center max-w-2xl mx-auto">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Promo Aktif</h3>
                    <p class="text-gray-600 mb-6">Saat ini belum ada promo yang tersedia. Pantau terus halaman ini untuk promo menarik!</p>
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition-colors duration-200">
                        Lihat Produk
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Copy to Clipboard Script --}}
    <script>
        function copyCode(code, promoId) {
            // Copy to clipboard
            navigator.clipboard.writeText(code).then(function() {
                // Show success message
                const copiedMsg = document.getElementById('copied-' + promoId);
                copiedMsg.classList.remove('hidden');
                
                // Hide after 2 seconds
                setTimeout(function() {
                    copiedMsg.classList.add('hidden');
                }, 2000);
            }).catch(function(err) {
                alert('Gagal menyalin kode: ' + err);
            });
        }
    </script>

    {{-- Footer --}}
    <x-footer />
</x-guest-layout>
