<x-guest-layout>
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h1 class="text-base text-orange-600 font-semibold tracking-wide uppercase">Testimonials</h1>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    What Our Customers Say
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Read authentic reviews from our satisfied customers about their Seblak Umi AI experience.
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach(\App\Models\Testimonial::with('user', 'product')->get() as $testimonial)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center mb-4">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-gray-600 mb-4">{{ $testimonial->comment }}</p>
                        <div class="border-t pt-4">
                            <div class="text-sm font-medium text-gray-900">{{ $testimonial->user->name }}</div>
                            <div class="text-sm text-gray-500">About: {{ $testimonial->product->name }}</div>
                            <div class="text-xs text-gray-400">{{ $testimonial->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            @auth
                <div class="mt-12 bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Tambahkan Review Anda</h2>
                    <form action="{{ route('testimonials.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <!-- Product Selection -->
                            <div>
                                <label for="product_id" class="block text-sm font-medium text-gray-700">Pilih Menu</label>
                                <select name="product_id" id="product_id" required
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                    <option value="">Pilih menu yang ingin direview</option>
                                    @foreach(\App\Models\Product::all() as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Rating -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Rating</label>
                                <div class="mt-2 flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <label class="rating-label cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                                            <svg class="h-8 w-8 text-gray-300 hover:text-yellow-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Review Text -->
                            <div>
                                <label for="comment" class="block text-sm font-medium text-gray-700">Review Anda</label>
                                <textarea id="comment" name="comment" rows="4" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"
                                        placeholder="Bagaimana pengalaman Anda dengan menu ini?"></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit"
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Kirim Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="mt-10 text-center">
                    <div class="bg-white rounded-lg shadow-md p-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Login untuk Memberi Review</h3>
                        <p class="mt-1 text-sm text-gray-500">Silakan login untuk memberikan review tentang pengalaman Anda!</p>
                        <div class="mt-6">
                            <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                Login Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle star rating selection
            const ratingLabels = document.querySelectorAll('.rating-label');
            
            ratingLabels.forEach((label, index) => {
                const input = label.querySelector('input');
                const star = label.querySelector('svg');
                
                // Handle hover
                label.addEventListener('mouseenter', () => {
                    // Fill in this star and all previous stars
                    for (let i = 0; i <= index; i++) {
                        ratingLabels[i].querySelector('svg').classList.add('text-yellow-400');
                        ratingLabels[i].querySelector('svg').classList.remove('text-gray-300');
                    }
                    // Clear all next stars
                    for (let i = index + 1; i < ratingLabels.length; i++) {
                        ratingLabels[i].querySelector('svg').classList.remove('text-yellow-400');
                        ratingLabels[i].querySelector('svg').classList.add('text-gray-300');
                    }
                });

                // Handle click
                input.addEventListener('change', () => {
                    // Clear all stars
                    ratingLabels.forEach(label => {
                        label.querySelector('svg').classList.remove('text-yellow-400');
                        label.querySelector('svg').classList.add('text-gray-300');
                    });
                    // Fill in selected stars
                    for (let i = 0; i <= index; i++) {
                        ratingLabels[i].querySelector('svg').classList.add('text-yellow-400');
                        ratingLabels[i].querySelector('svg').classList.remove('text-gray-300');
                    }
                });
            });

            // Reset stars when mouse leaves the container
            const ratingContainer = document.querySelector('.rating-label').parentElement;
            ratingContainer.addEventListener('mouseleave', () => {
                const selectedRating = document.querySelector('input[name="rating"]:checked');
                if (selectedRating) {
                    const selectedIndex = parseInt(selectedRating.value) - 1;
                    ratingLabels.forEach((label, index) => {
                        const star = label.querySelector('svg');
                        if (index <= selectedIndex) {
                            star.classList.add('text-yellow-400');
                            star.classList.remove('text-gray-300');
                        } else {
                            star.classList.remove('text-yellow-400');
                            star.classList.add('text-gray-300');
                        }
                    });
                } else {
                    ratingLabels.forEach(label => {
                        label.querySelector('svg').classList.remove('text-yellow-400');
                        label.querySelector('svg').classList.add('text-gray-300');
                    });
                }
            });
        });
    </script>
    @endpush
</x-guest-layout>
