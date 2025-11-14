<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($categories as $category)
                            <div class="card-seblak hover:shadow-bagisto-lg transition-all duration-300">
                                <div class="text-center p-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-orange-400 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="text-2xl">🌶️</span>
                                    </div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                                    <p class="text-secondary mb-4">{{ $category->description ?? 'Kategori seblak premium' }}</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-primary font-medium">{{ $category->products_count }} produk</span>
                                        <a href="{{ route('categories.show', $category) }}" class="btn-seblak text-sm">
                                            Lihat Produk →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500">Belum ada kategori yang tersedia.</p>
                            </div>
                        @endforelse
                    </div>

                    @if($categories->hasPages())
                        <div class="mt-8">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
