<x-guest-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($content->image)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $content->image) }}" alt="{{ $content->title }}" class="w-full h-64 md:h-80 object-cover rounded-lg">
                        </div>
                    @endif

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $content->title }}</h1>

                    <div class="prose prose-lg max-w-none">
                        {!! $content->body !!}
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Published on {{ $content->created_at->format('F j, Y') }}
                        </p>
                    </div>

                    <div class="mt-6">
                        <a href="{{ url('/') }}" class="text-orange-600 hover:text-orange-500 font-medium">
                            ← Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
