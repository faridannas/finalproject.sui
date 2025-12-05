  <x-app-layout
    :title="$title ?? $product->name . ' - Seblak Premium | Seblak UMI'"
    :description="$description ?? 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!'"
    :ogTitle="$product->name . ' - Seblak Premium | Seblak UMI'"
    :ogDescription="$description ?? 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!'"
    :ogImage="$ogImage ?? ($product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-og.jpg'))"
    :twitterTitle="$product->name . ' - Seblak Premium | Seblak UMI'"
    :twitterDescription="$description ?? 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!'"
    :twitterImage="$ogImage ?? ($product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-twitter.jpg'))"
>


    <!-- Structured Data - Product -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Product",
        "name": "{{ $product->name }}",
        "description": "{{ Str::limit($product->desc, 200) }}",
        "image": "{{ $product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-og.jpg') }}",
        "sku": "{{ $product->id }}",
        "brand": {
            "@@type": "Brand",
            "name": "Seblak UMI"
        },
        "category": "{{ $product->category?->name ?? 'Seblak' }}",
        "offers": {
            "@@type": "Offer",
            "price": "{{ $product->price }}",
            "priceCurrency": "IDR",
            "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
            "seller": {
                "@@type": "Organization",
                "name": "Seblak UMI"
            }
        },
        "aggregateRating": {
            "@@type": "AggregateRating",
            "ratingValue": "{{ $product->testimonials->count() > 0 ? $product->testimonials->avg('rating') : 4.9 }}",
            "reviewCount": "{{ $product->testimonials->count() }}"
        }
    }
    </script>

    <!-- Custom Header for Product Detail -->
    <div class="bg-gradient-to-r from-orange-500 to-red-600 pb-8 pt-6 shadow-lg rounded-b-[2rem] mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center gap-4 text-white">
            <button onclick="window.history.back()" class="bg-white/20 p-2 rounded-xl hover:bg-white/30 transition-colors backdrop-blur-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <h1 class="text-2xl font-bold">Detail Produk</h1>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Image gallery -->
                <div class="w-full aspect-w-1 aspect-h-1">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/seblakpremium.webp') }}" 
                         alt="{{ $product->name }} - Seblak premium autentik dari Seblak UMI" 
                         class="w-full h-full object-center object-cover sm:rounded-lg bg-white" 
                         loading="lazy" 
                         width="600" 
                         height="600">
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

                    <div class="mt-8 flex gap-4">
                        @auth
                            @if($product->stock > 0)
                                <form method="POST" action="{{ route('cart.add') }}" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-orange-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        Add to Cart
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('cart.buy-now') }}" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Buy Now
                                    </button>
                                </form>
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
                        @if(auth()->user() && auth()->user()->role === 'admin')
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

            <!-- Testimonials Section -->
            @if($product->testimonials->count() > 0)
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Ulasan Pembeli</h3>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center">
                                @php
                                    $avgRating = $product->testimonials->avg('rating');
                                @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $avgRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ number_format($avgRating, 1) }}</span>
                            <span class="text-sm text-gray-500">({{ $product->testimonials->count() }} ulasan)</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach($product->testimonials as $testimonial)
                            <div class="modern-card rounded-xl p-6 hover:shadow-lg transition-all duration-200">
                                <div class="flex items-start gap-4">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                        </div>
                                    </div>

                                    <!-- Review Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-bold text-gray-900">{{ $testimonial->user->name }}</h4>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <div class="flex">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                    <span class="text-sm text-gray-500">{{ $testimonial->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-700 mt-2 leading-relaxed">{{ $testimonial->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add Review Form (for authenticated users) -->
            @auth
                <div class="mt-16">
                    <div class="modern-card rounded-xl p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Tulis Ulasan</h3>
                                <p class="text-sm text-gray-600">Bagikan pengalaman Anda dengan produk ini</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('testimonials.store') }}" class="space-y-6">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <!-- Star Rating -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-3">Rating Produk</label>
                                <div class="flex gap-2">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" class="hidden peer/rating-{{ $i }}" {{ $i == 5 ? 'checked' : '' }}>
                                        <label for="rating-{{ $i }}" class="cursor-pointer group">
                                            <div class="flex items-center gap-2 px-4 py-3 rounded-xl border-2 border-gray-200 peer-checked/rating-{{ $i }}:border-orange-500 peer-checked/rating-{{ $i }}:bg-orange-50 hover:border-orange-300 transition-all">
                                                <div class="flex">
                                                    @for($j = 1; $j <= 5; $j++)
                                                        <svg class="w-5 h-5 {{ $j <= $i ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm font-semibold text-gray-700 peer-checked/rating-{{ $i }}:text-orange-600">{{ $i == 5 ? 'Sangat Puas' : ($i == 4 ? 'Puas' : ($i == 3 ? 'Cukup' : ($i == 2 ? 'Kurang' : 'Sangat Kurang'))) }}</span>
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <!-- Comment -->
                            <div>
                                <label for="comment" class="block text-sm font-bold text-gray-700 mb-2">Ulasan Anda</label>
                                <textarea name="comment" id="comment" rows="5" 
                                          class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 focus:border-orange-400 focus:ring-4 focus:ring-orange-100 transition-all resize-none" 
                                          placeholder="Ceritakan pengalaman Anda dengan produk ini... (minimal 10 karakter)"
                                          required></textarea>
                                <p class="mt-2 text-xs text-gray-500">Ulasan Anda akan membantu pembeli lain membuat keputusan yang lebih baik</p>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex gap-3">
                                <button type="submit" 
                                        class="flex-1 px-6 py-3.5 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endauth

            @guest
                <div class="mt-16">
                    <div class="modern-card rounded-xl p-8 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Ingin Memberikan Ulasan?</h3>
                        <p class="text-gray-600 mb-6">Silakan login terlebih dahulu untuk menulis ulasan produk</p>
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login Sekarang
                        </a>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</x-app-layout>

