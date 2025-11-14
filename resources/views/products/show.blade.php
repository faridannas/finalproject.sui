<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <!-- SEO Meta Tags -->
    @section('title', $title ?? $product->name . ' - Seblak Premium | Seblak UMI')
    @section('description', $description ?? 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!')
    @section('og_title', $product->name . ' - Seblak Premium | Seblak UMI')
    @section('og_description', $description ?? 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!')
    @section('og_image', $ogImage ?? ($product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-og.jpg')))
    @section('twitter_title', $product->name . ' - Seblak Premium | Seblak UMI')
    @section('twitter_description', $description ?? 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!')
    @section('twitter_image', $ogImage ?? ($product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-twitter.jpg')))

    <!-- Structured Data - Product -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": "{{ $product->name }}",
        "description": "{{ Str::limit($product->desc, 200) }}",
        "image": "{{ $product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-og.jpg') }}",
        "sku": "{{ $product->id }}",
        "brand": {
            "@type": "Brand",
            "name": "Seblak UMI"
        },
        "category": "{{ $product->category?->name ?? 'Seblak' }}",
        "offers": {
            "@type": "Offer",
            "price": "{{ $product->price }}",
            "priceCurrency": "IDR",
            "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@type": "Organization",
                "name": "Seblak UMI"
            }
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ $product->testimonials->avg('rating') ?? 4.9 }}",
            "ratingValue": "{{ $product->testimonials->count() > 0 ? $product->testimonials->avg('rating') : 4.9 }}",
            "reviewCount": "{{ $product->testimonials->count() }}"
        }
    }
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Image gallery -->
                <div class="w-full aspect-w-1 aspect-h-1">
                    @php
                        // Map product names to specific images in public/images folder
                        $productImages = [
                            'Seblak Seafood Special' => asset('images/Seblakseafood.jpg'),
                            'Seblak Kering Manis' => asset('images/seblakkering.jpg'),
                            'Seblak Kuah Komplit' => asset('images/seblakkuahkomplit.webp'),
                            'Seblak Super Pedas' => asset('images/seblaklevel2.webp'),
                            'Seblak Mie Jumbo' => asset('images/seblaklevel1.jpg'),
                            'Seblak Tulang' => asset('images/seblakpremium.webp'),
                        ];

                        // Check for exact matches first
                        $imageUrl = $product->image ? asset('storage/' . $product->image) : null;

                        if (!$imageUrl) {
                            foreach ($productImages as $key => $url) {
                                if (strtolower($product->name) === strtolower($key)) {
                                    $imageUrl = $url;
                                    break;
                                }
                            }
                        }

                        // If no exact match, check for partial matches
                        if (!$imageUrl) {
                            $partialImages = [
                                'Seafood' => asset('images/Seblakseafood.jpg'),
                                'Kering' => asset('images/seblakkering.jpg'),
                                'Kuah Komplit' => asset('images/seblakkuahkomplit.webp'),
                                'Super Pedas' => asset('images/seblaklevel2.webp'),
                                'Mie Jumbo' => asset('images/seblaklevel1.jpg'),
                                'Tulang' => asset('images/seblakpremium.webp'),
                            ];

                            foreach ($partialImages as $key => $url) {
                                if (stripos(strtolower($product->name), strtolower($key)) !== false) {
                                    $imageUrl = $url;
                                    break;
                                }
                            }
                        }

                        // Fallback to default image if no match found
                        if (!$imageUrl) {
                            $imageUrl = asset('images/seblakpremium.webp');
                        }
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }} - Seblak premium autentik dari Seblak UMI" class="w-full h-full object-center object-cover sm:rounded-lg bg-white" loading="lazy" width="600" height="600">
                </div>

                <!-- Product info -->
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-3xl text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Description</h3>
                        <div class="text-base text-gray-700 space-y-6">
                            <p>{{ $product->desc }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex items-center">
                            <h3 class="text-sm font-medium text-gray-900">Category:</h3>
                            <span class="ml-2 text-sm text-gray-500">{{ $product->category?->name ?? 'Seblak' }}</span>
                        </div>
                        <div class="flex items-center mt-2">
                            <h3 class="text-sm font-medium text-gray-900">Stock:</h3>
                            <span class="ml-2 text-sm {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 flex">
                        @auth
                            @if($product->stock > 0)
                                <button wire:click="addToCart({{ $product->id }})" class="flex-1 bg-orange-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Add to Cart
                                </button>
                                <button wire:click="buyNow({{ $product->id }})" class="flex-1 bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ml-4">
                                    Buy Now
                                </button>
                            @else
                                <span class="flex-1 bg-gray-300 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-500 cursor-not-allowed">
                                    Out of Stock
                                </span>
                            @endif
                        @endauth
                        @guest
                            <a href="{{ route('login') }}" class="flex-1 bg-orange-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Login to Add to Cart
                            </a>
                        @endguest
                    </div>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="mt-4 flex space-x-4">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:text-blue-500 font-medium">
                                    Edit Product
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-500 font-medium" onclick="return confirm('Are you sure you want to delete this product?')">
                                        Delete Product
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Testimonials -->
            @if($product->testimonials->count() > 0)
                <div class="mt-16">
                    <h3 class="text-lg font-medium text-gray-900">Customer Reviews</h3>
                    <div class="mt-6 space-y-6">
                        @foreach($product->testimonials as $testimonial)
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-5 w-5 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">{{ $testimonial->user->name }}</span>
                                </div>
                                <p class="mt-2 text-gray-700">{{ $testimonial->comment }}</p>
                                <p class="mt-2 text-sm text-gray-500">{{ $testimonial->created_at->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add Review Form (for authenticated users) -->
            @auth
                <div class="mt-16">
                    <h3 class="text-lg font-medium text-gray-900">Write a Review</h3>
                    <form method="POST" action="{{ route('testimonials.store') }}" class="mt-6">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                            <select name="rating" id="rating" class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                            <textarea name="comment" id="comment" rows="4" class="mt-1 block w-full border rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Share your thoughts about this product..."></textarea>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    {{-- Cart Component --}}
    @auth
        <livewire:cart-component />
    @endauth
</x-app-layout>
