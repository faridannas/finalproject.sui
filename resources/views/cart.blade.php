<x-app-layout>


    <!-- Custom Header for Cart -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
            <button onclick="smartBack()" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <h1 class="text-2xl font-bold">Keranjang Belanja</h1>
        </div>
    </div>

    <script>
        function smartBack() {
            // Check if previous page was checkout
            const referrer = document.referrer;
            if (referrer && referrer.includes('/checkout')) {
                // If coming from checkout, go to dashboard
                window.location.href = '{{ route('dashboard') }}';
            } else {
                // Otherwise use browser back
                window.history.back();
            }
        }
    </script>

    @livewire('cart-component')
</x-app-layout>

