<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Category: ') . $category->name }}
            </h2>
            <a href="{{ route('categories.index') }}" class="btn-seblak">
                ← Kembali ke Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-secondary text-lg">{{ $category->description ?? 'Koleksi seblak premium dari kategori ini' }}</p>
                        <p class="text-primary font-medium mt-2">{{ $category->products->count() }} produk tersedia</p>
                    </div>

                    @if($category->products->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($category->products as $product)
                                <div class="product-card animate-fade-in">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-48 object-cover rounded-t-lg" alt="{{ $product->name }}">
                                     @else
                                        <img src="https://images.unsplash.com/photo-1625944230945-b8e6d1c5dfb3?auto=format&fit=crop&w=600&q=80" class="w-full h-48 object-cover rounded-t-lg" alt="{{ $product->name }}">
                                    @endif

                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="product-title">{{ $product->name }}</h3>
                                            <span class="text-xs text-secondary bg-gray-100 px-2 py-1 rounded">Stok: {{ $product->stock }}</span>
                                        </div>

                                        <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($product->desc, 100) }}</p>

                                        <div class="flex justify-between items-center">
                                            <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('products.show', $product) }}" class="bg-primary text-white px-3 py-2 rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium">
                                                    Detail
                                                </a>
                                                @auth
                                                    <button wire:click="addToCart({{ $product->id }})" class="bg-secondary text-white px-3 py-2 rounded-lg hover:bg-secondary/90 transition-colors text-sm font-medium">
                                                        + Cart
                                                    </button>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-4xl">📦</span>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Produk</h3>
                            <p class="text-secondary mb-6">Kategori ini belum memiliki produk yang tersedia.</p>
                            <a href="{{ route('products.index') }}" class="btn-seblak">
                                Lihat Semua Produk →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
