<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage Contents') }}
            </h2>
            <a href="{{ route('admin.contents.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add Content
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <input type="text" id="search" placeholder="Search contents..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    @php
                        $contents = \App\Models\Content::paginate(12);
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="contents-grid">
                        @foreach(\App\Models\Content::paginate(12) as $content)
                        @foreach($contents as $content)
                            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md">
                                <div class="aspect-w-16 aspect-h-9">
                                    @if($content->image)
                                        <img src="{{ asset('storage/' . $content->image) }}" alt="{{ $content->title }}" class="w-full h-48 object-cover">
                                        <img src="{{ Storage::url($content->image) }}" alt="{{ $content->title }}" class="w-full h-48 object-cover">
                                        <img src="{{ Storage::url($content->image) }}" alt="{{ $content->title }}" class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $content->title }}</h3>
                                    <p class="text-sm text-gray-600 mb-2 line-clamp-3">{{ Str::limit($content->body, 100) }}</p>
                                    <p class="text-xs text-gray-500 mb-4">Created: {{ $content->created_at->format('d/m/Y') }}</p>
                                    <div class="flex justify-between items-center">
                                        <a href="{{ route('admin.contents.edit', $content) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                        <form method="POST" action="{{ route('admin.contents.destroy', $content) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure you want to delete this content?')">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(\App\Models\Content::count() == 0)
                    @if($contents->count() == 0)
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No contents</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new content article.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.contents.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Add Content
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6">
                        {{ \App\Models\Content::paginate(12)->links() }}
                        {{ $contents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('search').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const contents = document.querySelectorAll('#contents-grid > div');

            contents.forEach(content => {
                const text = content.textContent.toLowerCase();
                content.style.display = text.includes(query) ? '' : 'none';
            });
        });
    </script>
</x-admin-layout>
