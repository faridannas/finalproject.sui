<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Banners') }}
            </h2>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Banner
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-4 md:p-6">
                    {{-- Search Bar --}}
                    <div class="mb-6">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   id="search" 
                                   placeholder="Search banners by title..." 
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        </div>
                    </div>

                    {{-- Banners Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="banners-grid">
                        @forelse($banners as $banner)
                            <div class="group bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                                {{-- Banner Image --}}
                                <div class="relative overflow-hidden bg-gray-100" style="height: 200px;">
                                    @if($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}" 
                                             alt="{{ $banner->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    {{-- Link Badge --}}
                                    @if($banner->link)
                                        <div class="absolute top-3 right-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                Linked
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Banner Info --}}
                                <div class="p-4">
                                    <h3 class="text-base font-bold text-gray-900 mb-2 line-clamp-1 group-hover:text-purple-600 transition-colors">
                                        {{ $banner->title }}
                                    </h3>
                                    
                                    @if($banner->link)
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-500 mb-1">Link to:</p>
                                            <a href="{{ $banner->link }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 hover:underline line-clamp-1">
                                                {{ $banner->link }}
                                            </a>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center text-xs text-gray-500 mb-4">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Created {{ $banner->created_at->format('M d, Y') }}
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" 
                                           class="flex-1 inline-flex justify-center items-center px-3 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 text-sm font-medium rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center px-3 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-sm font-medium rounded-lg transition-colors" 
                                                    onclick="return confirm('Are you sure you want to delete this banner? This will also delete the image file.')">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-16">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No banners yet</h3>
                                    <p class="text-gray-500 mb-6">Get started by creating your first promotional banner.</p>
                                    <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create First Banner
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($banners->hasPages())
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            {{ $banners->links() }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Card --}}
            @if($banners->count() > 0)
                <div class="mt-6 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h4 class="text-sm font-semibold text-purple-900 mb-2">Banner Management Tips</h4>
                            <ul class="space-y-1 text-sm text-purple-800">
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Banners are displayed on your homepage to promote products, sales, or announcements</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Use high-quality images (1920x600px recommended) for best results</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Add links to drive traffic to specific product pages or promotions</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="mr-2">•</span>
                                    <span>Keep file sizes under 2MB for faster page loading</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Search Script --}}
    <script>
        document.getElementById('search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const banners = document.querySelectorAll('#banners-grid > div');

            banners.forEach(banner => {
                const text = banner.textContent.toLowerCase();
                banner.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
</x-admin-layout>
