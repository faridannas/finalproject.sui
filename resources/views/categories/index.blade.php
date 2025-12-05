<x-guest-layout>
    {{-- Header / Navbar - Same as Welcome Page --}}
    {{-- Navbar --}}
    <x-navbar />

    {{-- Page Content --}}
    <div class="min-h-screen bg-gradient-to-b from-orange-50 via-white to-yellow-50 py-8 md:py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                {{-- Header Section --}}
                <div class="text-center mb-10 md:mb-16 animate-fade-in">
                    <span class="inline-block bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-2 rounded-full text-sm font-semibold mb-4 shadow-lg">
                        🌶️ Level Kepedasan
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold bg-gradient-to-r from-orange-600 via-red-600 to-orange-700 bg-clip-text text-transparent mb-4">
                        Kategori Seblak
                    </h1>
                    <p class="text-gray-600 text-base md:text-lg max-w-2xl mx-auto">
                        Pilih level kepedasan sesuai seleramu! Dari yang manja sampai yang bikin nangis 🔥
                    </p>
                </div>

                {{-- Categories Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8">
                    @forelse($categories as $category)
                        <div class="group relative bg-white rounded-2xl md:rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border-2 border-transparent hover:border-orange-400">
                            {{-- Card Content --}}
                            <div class="relative p-6 md:p-8 text-center bg-gradient-to-br from-white to-orange-50 group-hover:from-orange-50 group-hover:to-red-50 transition-all duration-300">
                                {{-- Icon with Animation --}}
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 md:w-24 md:h-24 mx-auto bg-gradient-to-br from-orange-400 via-red-500 to-orange-600 rounded-full flex items-center justify-center shadow-xl transform group-hover:scale-110 group-hover:rotate-12 transition-all duration-500">
                                        <span class="text-3xl md:text-4xl animate-pulse">🌶️</span>
                                    </div>
                                    {{-- Decorative Ring --}}
                                    <div class="absolute inset-0 w-20 h-20 md:w-24 md:h-24 mx-auto border-4 border-orange-200 rounded-full animate-ping opacity-20"></div>
                                </div>

                                {{-- Category Name --}}
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors duration-300">
                                    {{ $category->name }}
                                </h3>

                                {{-- Description --}}
                                <p class="text-gray-600 text-sm md:text-base mb-6 min-h-[3rem] leading-relaxed">
                                    {{ $category->description ?? 'Kategori seblak premium dengan cita rasa autentik' }}
                                </p>

                                {{-- Product Count Badge --}}
                                <div class="inline-flex items-center bg-orange-100 text-orange-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                                    </svg>
                                    {{ $category->products_count }} produk tersedia
                                </div>

                                {{-- CTA Button --}}
                                @auth
                                    <a href="{{ route('categories.show', $category) }}" 
                                       class="block w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-3 md:py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <span class="flex items-center justify-center">
                                            Lihat Produk
                                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </span>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="block w-full bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-3 md:py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                            </svg>
                                            Masuk untuk lihat produk
                                        </span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16 md:py-24">
                            <div class="text-6xl md:text-8xl mb-6">🌶️</div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-700 mb-2">Belum Ada Kategori</h3>
                            <p class="text-gray-500">Kategori seblak akan segera hadir!</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($categories->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white rounded-xl shadow-lg p-4">
                            {{ $categories->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Animations --}}
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
        
        @media (max-width: 640px) {
            .animate-fade-in {
                animation: fade-in 0.4s ease-out;
            }
        }
    </style>



    {{-- Footer --}}
    <x-footer />
</x-guest-layout>
