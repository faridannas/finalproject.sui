<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Banners') }}
            </h2>
            <a href="{{ route('admin.banners.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Banner
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <input type="text" id="search" placeholder="Search banners..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="banners-grid">
                        @foreach(\App\Models\Banner::paginate(12) as $banner)
                            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md">
                                <div class="aspect-w-16 aspect-h-9">
                                    @if($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $banner->title }}</h3>
                                    @if($banner->link)
                                        <p class="text-sm text-blue-600 mb-2">Link: {{ $banner->link }}</p>
                                    @endif
                                    <p class="text-xs text-gray-500 mb-4">Created: {{ $banner->created_at->format('M d, Y') }}</p>
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('admin.banners.edit', $banner) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                        <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure you want to delete this banner?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(\App\Models\Banner::count() == 0)
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No banners</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new banner.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Add Banner
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        {{ \App\Models\Banner::paginate(12)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</x-app-layout>
