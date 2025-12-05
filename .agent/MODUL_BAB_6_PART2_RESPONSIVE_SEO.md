# BAB VI - PART 2
# KEAMANAN DAN OPTIMASI SISTEM (Lanjutan)

---

## 6.3 Responsive Design

Responsive design memastikan aplikasi tampil optimal di berbagai ukuran layar dan device.

### 6.3.1 Mobile-First Approach

Mobile-first approach adalah strategi design yang dimulai dari mobile screen kemudian scale up ke desktop.

**Prinsip Mobile-First:**

1. **Start with Mobile Layout**
   - Design untuk smallest screen first
   - Focus pada essential content
   - Simplified navigation
   - Touch-friendly interfaces

2. **Progressive Enhancement**
   - Add features untuk larger screens
   - Enhance layout dengan available space
   - Optimize untuk desktop interactions

**Implementation dengan Tailwind CSS:**
```html
<!-- Mobile-first: default styles untuk mobile -->
<div class="p-4 text-sm">
    <!-- Mobile: padding 1rem, text small -->
    
    <!-- Tablet: padding 2rem, text base -->
    <div class="md:p-8 md:text-base">
        
        <!-- Desktop: padding 3rem, text large -->
        <div class="lg:p-12 lg:text-lg">
            Content
        </div>
    </div>
</div>

<!-- Grid Layout: Mobile-first -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Mobile: 1 column -->
    <!-- Tablet: 2 columns -->
    <!-- Desktop: 4 columns -->
    <div class="product-card">Product 1</div>
    <div class="product-card">Product 2</div>
    <div class="product-card">Product 3</div>
    <div class="product-card">Product 4</div>
</div>
```

**Custom CSS Media Queries:**
```css
/* Mobile First - Base styles */
.container {
    width: 100%;
    padding: 1rem;
}

.product-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

/* Tablet - 768px and up */
@media (min-width: 768px) {
    .container {
        padding: 2rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
}

/* Desktop - 1024px and up */
@media (min-width: 1024px) {
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 3rem;
    }
    
    .product-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
}

/* Large Desktop - 1280px and up */
@media (min-width: 1280px) {
    .container {
        max-width: 1400px;
    }
}
```

### 6.3.2 Breakpoint Strategy

Breakpoints menentukan di mana layout berubah berdasarkan screen size.

**Standard Breakpoints:**
```javascript
// tailwind.config.js
module.exports = {
    theme: {
        screens: {
            'sm': '640px',   // Small devices (phones)
            'md': '768px',   // Medium devices (tablets)
            'lg': '1024px',  // Large devices (desktops)
            'xl': '1280px',  // Extra large devices
            '2xl': '1536px', // 2X large devices
        }
    }
}
```

**Custom Breakpoints:**
```javascript
module.exports = {
    theme: {
        screens: {
            'xs': '475px',
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
            'xl': '1280px',
            '2xl': '1536px',
            '3xl': '1920px',
        }
    }
}
```

**Responsive Components:**

**1. Responsive Navigation:**
```blade
<!-- Desktop Navigation -->
<nav class="hidden lg:flex items-center space-x-6">
    <a href="/">Home</a>
    <a href="/products">Products</a>
    <a href="/categories">Categories</a>
    <a href="/testimonials">Testimonials</a>
</nav>

<!-- Mobile Hamburger Menu -->
<button class="lg:hidden" id="mobileMenuToggle">
    <svg class="w-6 h-6"><!-- Hamburger icon --></svg>
</button>

<!-- Mobile Menu (Hidden by default) -->
<div class="lg:hidden hidden" id="mobileMenu">
    <a href="/" class="block py-2">Home</a>
    <a href="/products" class="block py-2">Products</a>
    <a href="/categories" class="block py-2">Categories</a>
    <a href="/testimonials" class="block py-2">Testimonials</a>
</div>
```

**2. Responsive Typography:**
```html
<h1 class="text-2xl md:text-4xl lg:text-5xl font-bold">
    Seblak Umi AI
</h1>

<p class="text-sm md:text-base lg:text-lg">
    Description text that scales with screen size
</p>
```

**3. Responsive Images:**
```html
<!-- Responsive image with different sizes -->
<img src="product-mobile.jpg"
     srcset="product-mobile.jpg 640w,
             product-tablet.jpg 768w,
             product-desktop.jpg 1024w"
     sizes="(max-width: 640px) 100vw,
            (max-width: 768px) 50vw,
            33vw"
     alt="Product">

<!-- Or with Tailwind -->
<img src="product.jpg" 
     class="w-full md:w-1/2 lg:w-1/3"
     alt="Product">
```

### 6.3.3 Touch-Friendly Interface

Interface harus mudah digunakan dengan touch pada mobile devices.

**Touch-Friendly Guidelines:**

**1. Minimum Touch Target Size:**
```css
/* Buttons dan links harus minimal 44x44px */
.btn {
    min-width: 44px;
    min-height: 44px;
    padding: 12px 24px;
}

.touch-target {
    padding: 12px;
    margin: 4px;
}
```

**2. Adequate Spacing:**
```html
<!-- Adequate spacing between clickable elements -->
<div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
    <button class="btn">Button 1</button>
    <button class="btn">Button 2</button>
    <button class="btn">Button 3</button>
</div>
```

**3. Touch Gestures:**
```javascript
// Swipe gesture untuk carousel
let touchStartX = 0;
let touchEndX = 0;

element.addEventListener('touchstart', e => {
    touchStartX = e.changedTouches[0].screenX;
});

element.addEventListener('touchend', e => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    if (touchEndX < touchStartX - 50) {
        // Swipe left - next slide
        nextSlide();
    }
    if (touchEndX > touchStartX + 50) {
        // Swipe right - previous slide
        prevSlide();
    }
}
```

**4. Prevent Zoom on Input Focus (iOS):**
```html
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- Or use font-size >= 16px to prevent zoom -->
<input type="text" class="text-base" placeholder="Search...">
```

**5. Touch-Friendly Forms:**
```html
<form class="space-y-4">
    <!-- Large input fields -->
    <input type="text" 
           class="w-full px-4 py-3 text-base rounded-lg border"
           placeholder="Name">
    
    <!-- Large buttons -->
    <button type="submit" 
            class="w-full py-4 text-lg font-semibold rounded-lg">
        Submit
    </button>
    
    <!-- Adequate spacing between fields -->
    <div class="space-y-4">
        <input type="email" class="w-full px-4 py-3">
        <input type="password" class="w-full px-4 py-3">
    </div>
</form>
```

### 6.3.4 Cross-Browser Compatibility

Aplikasi harus berfungsi dengan baik di berbagai browser.

**Browser Support Strategy:**

**1. Autoprefixer:**
```javascript
// postcss.config.js
module.exports = {
    plugins: {
        autoprefixer: {
            overrideBrowserslist: [
                'last 2 versions',
                '> 1%',
                'not dead'
            ]
        }
    }
}
```

**2. Feature Detection:**
```javascript
// Check browser support
if ('IntersectionObserver' in window) {
    // Use Intersection Observer
} else {
    // Fallback
}

// Modernizr untuk comprehensive detection
if (Modernizr.flexbox) {
    // Use flexbox
} else {
    // Use fallback layout
}
```

**3. CSS Fallbacks:**
```css
/* Fallback untuk browsers yang tidak support CSS Grid */
.grid-container {
    display: flex;
    flex-wrap: wrap;
}

/* Modern browsers dengan Grid support */
@supports (display: grid) {
    .grid-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }
}
```

**4. Polyfills:**
```html
<!-- Polyfill untuk older browsers -->
<script src="https://cdn.polyfill.io/v3/polyfill.min.js"></script>

<!-- Or specific polyfills -->
<script>
if (!Array.prototype.includes) {
    Array.prototype.includes = function(searchElement) {
        return this.indexOf(searchElement) !== -1;
    };
}
</script>
```

**5. Testing:**
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile Safari (iOS)
- Chrome Mobile (Android)

---

## 6.4 SEO Optimization

SEO optimization membantu website ditemukan di search engines.

### 6.4.1 Meta Tags Implementation

Meta tags memberikan informasi tentang halaman ke search engines.

**Essential Meta Tags:**
```blade
<!-- resources/views/layouts/app.blade.php -->
<head>
    <!-- Title -->
    <title>@yield('title', 'Seblak Umi AI - Delicious Seblak Online')</title>
    
    <!-- Meta Description -->
    <meta name="description" content="@yield('description', 'Order delicious seblak online from Seblak Umi AI. Fresh ingredients, authentic taste, delivered to your door.')">
    
    <!-- Meta Keywords -->
    <meta name="keywords" content="@yield('keywords', 'seblak, makanan pedas, online food, delivery')">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Open Graph (Facebook) -->
    <meta property="og:title" content="@yield('og_title', 'Seblak Umi AI')">
    <meta property="og:description" content="@yield('og_description', 'Order delicious seblak online')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-image.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Seblak Umi AI">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', 'Seblak Umi AI')">
    <meta name="twitter:description" content="@yield('twitter_description', 'Order delicious seblak online')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('images/twitter-image.jpg'))">
    
    <!-- Robots -->
    <meta name="robots" content="index, follow">
    
    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Charset -->
    <meta charset="UTF-8">
</head>
```

**Page-Specific Meta Tags:**
```blade
<!-- resources/views/products/show.blade.php -->
@extends('layouts.app')

@section('title', $product->name . ' - Seblak Umi AI')
@section('description', Str::limit($product->description, 160))
@section('keywords', $product->name . ', seblak, ' . $product->category->name)

@section('og_title', $product->name)
@section('og_description', Str::limit($product->description, 200))
@section('og_image', $product->image_url)

@section('content')
    <!-- Product content -->
@endsection
```

### 6.4.2 Semantic HTML

Semantic HTML menggunakan tags yang meaningful untuk search engines.

**Semantic Structure:**
```html
<!-- ✅ GOOD - Semantic HTML -->
<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/products">Products</a></li>
        </ul>
    </nav>
</header>

<main>
    <article>
        <header>
            <h1>Product Name</h1>
            <time datetime="2025-11-29">November 29, 2025</time>
        </header>
        
        <section>
            <h2>Description</h2>
            <p>Product description...</p>
        </section>
        
        <aside>
            <h3>Related Products</h3>
            <!-- Related products -->
        </aside>
    </article>
</main>

<footer>
    <p>&copy; 2025 Seblak Umi AI</p>
</footer>

<!-- ❌ BAD - Non-semantic -->
<div class="header">
    <div class="nav">
        <div class="menu-item">Home</div>
    </div>
</div>
```

**Structured Data (Schema.org):**
```html
<!-- Product Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name }}",
    "image": "{{ $product->image_url }}",
    "description": "{{ $product->description }}",
    "sku": "{{ $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "Seblak Umi AI"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ route('products.show', $product) }}",
        "priceCurrency": "IDR",
        "price": "{{ $product->price }}",
        "availability": "{{ $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "{{ $product->average_rating }}",
        "reviewCount": "{{ $product->reviews_count }}"
    }
}
</script>

<!-- Organization Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Seblak Umi AI",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.png') }}",
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+62-xxx-xxxx-xxxx",
        "contactType": "Customer Service"
    },
    "sameAs": [
        "https://www.facebook.com/seblakumiai",
        "https://www.instagram.com/seblakumiai"
    ]
}
</script>
```

### 6.4.3 URL Structure

Clean dan descriptive URLs lebih baik untuk SEO.

**Good URL Structure:**
```
✅ GOOD URLs:
https://seblakumiai.com/products/seblak-pedas-level-5
https://seblakumiai.com/categories/seblak-original
https://seblakumiai.com/blog/cara-membuat-seblak

❌ BAD URLs:
https://seblakumiai.com/product.php?id=123
https://seblakumiai.com/cat?c=5&page=2
https://seblakumiai.com/index.php?action=view&type=blog&id=45
```

**Laravel Route Implementation:**
```php
// routes/web.php

// Use slugs instead of IDs
Route::get('/products/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');

Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])
    ->name('categories.show');

// Model binding dengan slug
// app/Models/Product.php
public function getRouteKeyName()
{
    return 'slug';
}

// Generate slug automatically
public static function boot()
{
    parent::boot();
    
    static::creating(function ($product) {
        $product->slug = Str::slug($product->name);
    });
}
```

### 6.4.4 Sitemap Generation

Sitemap membantu search engines menemukan semua halaman di website.

**Generate Sitemap:**
```php
// app/Http/Controllers/SitemapController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . url('/') . '</loc>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>';
        
        // Products
        foreach ($products as $product) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . route('products.show', $product) . '</loc>';
            $sitemap .= '<lastmod>' . $product->updated_at->toAtomString() . '</lastmod>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.8</priority>';
            $sitemap .= '</url>';
        }
        
        // Categories
        foreach ($categories as $category) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . route('categories.show', $category) . '</loc>';
            $sitemap .= '<changefreq>weekly</changefreq>';
            $sitemap .= '<priority>0.7</priority>';
            $sitemap .= '</url>';
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)
            ->header('Content-Type', 'text/xml');
    }
}

// routes/web.php
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
```

**robots.txt:**
```
# public/robots.txt
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /cart
Disallow: /checkout
Disallow: /orders

Sitemap: https://seblakumiai.com/sitemap.xml
```

### 6.4.5 Performance Metrics

Page speed adalah ranking factor untuk SEO.

**Core Web Vitals:**

1. **Largest Contentful Paint (LCP)** - Loading performance
   - Target: < 2.5 seconds
   - Optimize images
   - Minimize render-blocking resources

2. **First Input Delay (FID)** - Interactivity
   - Target: < 100 milliseconds
   - Minimize JavaScript execution time
   - Break up long tasks

3. **Cumulative Layout Shift (CLS)** - Visual stability
   - Target: < 0.1
   - Set size attributes on images
   - Avoid inserting content above existing content

**Optimization Techniques:**
```html
<!-- Preload critical resources -->
<link rel="preload" href="/css/app.css" as="style">
<link rel="preload" href="/js/app.js" as="script">

<!-- Preconnect to external domains -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://cdn.jsdelivr.net">

<!-- Defer non-critical JavaScript -->
<script src="/js/analytics.js" defer></script>

<!-- Async for independent scripts -->
<script src="/js/social-widgets.js" async></script>

<!-- Image optimization -->
<img src="product.jpg" 
     width="400" 
     height="300" 
     alt="Product"
     loading="lazy">
```

**Monitoring:**
- Google PageSpeed Insights
- Lighthouse
- GTmetrix
- WebPageTest

---

*Halaman 126-140 selesai. Lanjut ke BAB 7...*
