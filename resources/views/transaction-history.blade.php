<x-app-layout>
    <!-- Custom Header for Transaction History -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
            <button onclick="window.location.href='{{ route('dashboard') }}'" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <h1 class="text-2xl font-bold">Riwayat Pesanan</h1>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @livewire('transaction-history')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
