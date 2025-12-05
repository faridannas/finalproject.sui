# BAB VI
# KEAMANAN DAN OPTIMASI SISTEM

---

## 6.1 Keamanan Aplikasi

Keamanan adalah aspek krusial dalam pengembangan aplikasi web, terutama untuk e-commerce yang menangani data sensitif pengguna dan transaksi keuangan. Aplikasi Seblak Umi AI mengimplementasikan berbagai mekanisme keamanan untuk melindungi data dan mencegah serangan.

### 6.1.1 CSRF Protection (Cross-Site Request Forgery)

CSRF adalah serangan yang memaksa pengguna yang sudah terautentikasi untuk melakukan aksi yang tidak diinginkan pada aplikasi web.

**Implementasi CSRF Protection di Laravel:**

Laravel secara otomatis menyediakan CSRF protection melalui middleware `VerifyCsrfToken`. Setiap form harus menyertakan CSRF token untuk validasi.

**Penggunaan CSRF Token:**
```blade
<!-- Blade Template -->
<form method="POST" action="/orders">
    @csrf
    <!-- Form fields -->
    <button type="submit">Submit</button>
</form>
```

**CSRF Token di AJAX Request:**
```javascript
// Setup CSRF token untuk semua AJAX requests
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Atau menggunakan Fetch API
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
});
```

**Meta Tag di Layout:**
```blade
<!-- resources/views/layouts/app.blade.php -->
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
```

**Exclude Routes dari CSRF Protection:**

Beberapa routes seperti webhook payment notification perlu di-exclude dari CSRF protection:

```php
// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'payments/notification',
    'webhooks/*',
];
```

**Manfaat CSRF Protection:**
- Mencegah unauthorized commands dari trusted users
- Melindungi form submissions
- Validasi request authenticity
- Prevent session hijacking attacks

### 6.1.2 SQL Injection Prevention

SQL Injection adalah teknik serangan yang memanfaatkan input user untuk mengeksekusi SQL query berbahaya.

**Pencegahan SQL Injection di Laravel:**

Laravel menggunakan PDO parameter binding yang secara otomatis mencegah SQL injection.

**Contoh Query yang Aman:**
```php
// ✅ AMAN - Menggunakan Eloquent ORM
$user = User::where('email', $request->email)->first();

// ✅ AMAN - Query Builder dengan parameter binding
$users = DB::table('users')
    ->where('email', $request->email)
    ->get();

// ✅ AMAN - Raw query dengan parameter binding
$users = DB::select('SELECT * FROM users WHERE email = ?', [$request->email]);

// ❌ TIDAK AMAN - Raw query tanpa binding
$users = DB::select("SELECT * FROM users WHERE email = '{$request->email}'");
```

**Validasi Input:**
```php
// Selalu validasi input sebelum digunakan
$validated = $request->validate([
    'email' => 'required|email',
    'name' => 'required|string|max:255',
    'age' => 'required|integer|min:1|max:150',
]);

// Gunakan validated data
$user = User::create($validated);
```

**Sanitasi Input:**
```php
// Sanitize string input
$cleanInput = strip_tags($request->input('description'));
$cleanInput = htmlspecialchars($cleanInput, ENT_QUOTES, 'UTF-8');

// Atau gunakan Laravel's e() helper
$safeOutput = e($userInput);
```

### 6.1.3 XSS Protection (Cross-Site Scripting)

XSS adalah serangan yang menginjeksi script berbahaya ke halaman web yang dilihat oleh user lain.

**Pencegahan XSS di Laravel:**

**1. Automatic Escaping di Blade:**
```blade
<!-- ✅ AMAN - Blade otomatis escape output -->
<p>{{ $user->name }}</p>
<!-- Output: &lt;script&gt;alert('XSS')&lt;/script&gt; -->

<!-- ❌ TIDAK AMAN - Raw output tanpa escaping -->
<p>{!! $user->name !!}</p>
<!-- Output: <script>alert('XSS')</script> -->

<!-- ✅ AMAN - Gunakan {!! !!} hanya untuk trusted content -->
<div class="content">
    {!! $article->body !!} <!-- Hanya jika sudah di-sanitize -->
</div>
```

**2. Content Security Policy (CSP):**
```php
// app/Http/Middleware/ContentSecurityPolicy.php
public function handle($request, Closure $next)
{
    $response = $next($request);
    
    $response->headers->set('Content-Security-Policy', 
        "default-src 'self'; " .
        "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
        "img-src 'self' data: https:; " .
        "font-src 'self' https://fonts.gstatic.com;"
    );
    
    return $response;
}
```

**3. Sanitize Rich Text Input:**
```php
use HTMLPurifier;
use HTMLPurifier_Config;

public function sanitizeHtml($html)
{
    $config = HTMLPurifier_Config::createDefault();
    $config->set('HTML.Allowed', 'p,b,i,u,a[href],ul,ol,li,strong,em');
    
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}

// Usage
$cleanContent = $this->sanitizeHtml($request->input('content'));
```

**4. HTTP Headers untuk XSS Protection:**
```php
// config/secure-headers.php
return [
    'x-content-type-options' => 'nosniff',
    'x-frame-options' => 'SAMEORIGIN',
    'x-xss-protection' => '1; mode=block',
];
```

### 6.1.4 Password Hashing

Password harus selalu di-hash sebelum disimpan di database, tidak pernah dalam plain text.

**Laravel Password Hashing:**

Laravel menggunakan bcrypt algorithm yang sangat aman untuk hashing password.

**Hash Password:**
```php
use Illuminate\Support\Facades\Hash;

// Saat registrasi atau update password
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
]);

// Atau menggunakan mutator di Model
// app/Models/User.php
public function setPasswordAttribute($value)
{
    $this->attributes['password'] = Hash::make($value);
}
```

**Verify Password:**
```php
// Saat login
if (Hash::check($request->password, $user->password)) {
    // Password correct
    Auth::login($user);
} else {
    // Password incorrect
    return back()->withErrors(['password' => 'Invalid credentials']);
}
```

**Password Validation Rules:**
```php
$request->validate([
    'password' => [
        'required',
        'string',
        'min:8',              // Minimum 8 characters
        'confirmed',          // Must match password_confirmation
        'regex:/[a-z]/',      // Must contain lowercase
        'regex:/[A-Z]/',      // Must contain uppercase
        'regex:/[0-9]/',      // Must contain number
        'regex:/[@$!%*#?&]/', // Must contain special character
    ],
]);
```

**Password Strength Indicator (Frontend):**
```javascript
function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[@$!%*#?&]/.test(password)) strength++;
    
    const levels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const colors = ['#ff0000', '#ff4500', '#ffa500', '#ffd700', '#9acd32', '#00ff00'];
    
    return {
        level: levels[strength],
        color: colors[strength],
        score: strength
    };
}
```

### 6.1.5 Secure Session Management

Session management yang aman mencegah session hijacking dan fixation attacks.

**Session Configuration:**
```php
// config/session.php
return [
    'driver' => env('SESSION_DRIVER', 'file'),
    'lifetime' => 120, // Session lifetime in minutes
    'expire_on_close' => false,
    'encrypt' => true, // Encrypt session data
    'secure' => env('SESSION_SECURE_COOKIE', true), // HTTPS only
    'http_only' => true, // Prevent JavaScript access
    'same_site' => 'lax', // CSRF protection
];
```

**Session Security Best Practices:**

**1. Regenerate Session ID:**
```php
// Saat login
Auth::login($user);
$request->session()->regenerate();

// Saat logout
Auth::logout();
$request->session()->invalidate();
$request->session()->regenerateToken();
```

**2. Session Timeout:**
```php
// Middleware untuk check session timeout
public function handle($request, Closure $next)
{
    if (auth()->check()) {
        $lastActivity = session('last_activity');
        $timeout = config('session.lifetime') * 60; // Convert to seconds
        
        if ($lastActivity && (time() - $lastActivity > $timeout)) {
            Auth::logout();
            session()->invalidate();
            return redirect('/login')->with('message', 'Session expired');
        }
        
        session(['last_activity' => time()]);
    }
    
    return $next($request);
}
```

**3. Prevent Session Fixation:**
```php
// Laravel otomatis regenerate session ID setelah login
// Pastikan selalu gunakan Auth::login() method

Auth::login($user);
// Session ID otomatis di-regenerate
```

**4. Secure Cookie Settings:**
```env
SESSION_SECURE_COOKIE=true  # HTTPS only
SESSION_HTTP_ONLY=true      # No JavaScript access
SESSION_SAME_SITE=lax       # CSRF protection
```

### 6.1.6 File Upload Security

File upload adalah vektor serangan yang umum jika tidak di-handle dengan benar.

**Secure File Upload Implementation:**

**1. Validasi File Type:**
```php
$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    'document' => 'required|mimes:pdf,doc,docx|max:5120',
]);
```

**2. Validasi File Size:**
```php
// Di validation rules
'file' => 'max:2048', // KB (2MB)

// Atau custom validation
if ($request->file('upload')->getSize() > 2048000) {
    return back()->withErrors(['upload' => 'File too large']);
}
```

**3. Rename File:**
```php
// Generate unique filename
$filename = time() . '_' . Str::random(10) . '.' . $request->file('image')->extension();

// Atau menggunakan hash
$filename = hash('sha256', time() . $request->file('image')->getClientOriginalName()) 
    . '.' . $request->file('image')->extension();

$path = $request->file('image')->storeAs('uploads', $filename, 'public');
```

**4. Store Outside Web Root:**
```php
// Store di storage/app/private
$path = $request->file('document')->store('documents', 'private');

// Access via controller dengan authorization
public function download($filename)
{
    // Check authorization
    if (!auth()->user()->canDownload($filename)) {
        abort(403);
    }
    
    return Storage::disk('private')->download('documents/' . $filename);
}
```

**5. Scan for Malware (Optional):**
```php
use Illuminate\Support\Facades\Storage;

public function scanFile($file)
{
    // Menggunakan ClamAV atau service lain
    $scanner = new \Xenolope\Quahog\Client('unix:///var/run/clamav/clamd.ctl');
    $result = $scanner->scanFile($file->getRealPath());
    
    if ($result['status'] !== 'OK') {
        throw new \Exception('File contains malware');
    }
}
```

**6. Prevent Directory Traversal:**
```php
// Validasi filename
$filename = basename($request->input('filename'));

// Prevent path traversal
if (strpos($filename, '..') !== false || strpos($filename, '/') !== false) {
    abort(400, 'Invalid filename');
}
```

**7. Image Validation (Prevent Image Bombs):**
```php
public function validateImage($file)
{
    $image = getimagesize($file->getRealPath());
    
    if (!$image) {
        throw new \Exception('Invalid image file');
    }
    
    // Check dimensions
    if ($image[0] > 5000 || $image[1] > 5000) {
        throw new \Exception('Image dimensions too large');
    }
    
    // Check file size vs image size (detect image bombs)
    $fileSize = $file->getSize();
    $expectedSize = $image[0] * $image[1] * 3; // RGB
    
    if ($fileSize < $expectedSize / 100) {
        throw new \Exception('Suspicious image file');
    }
    
    return true;
}
```

---

## 6.2 Optimasi Performa

Optimasi performa memastikan aplikasi berjalan cepat dan efisien, memberikan user experience yang baik.

### 6.2.1 Database Query Optimization

Query optimization adalah kunci untuk performa aplikasi yang baik.

**1. Eager Loading (N+1 Problem):**
```php
// ❌ BAD - N+1 Query Problem
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->user->name; // Query untuk setiap order
}
// Total queries: 1 + N (dimana N = jumlah orders)

// ✅ GOOD - Eager Loading
$orders = Order::with('user')->get();
foreach ($orders as $order) {
    echo $order->user->name; // Tidak ada additional query
}
// Total queries: 2 (1 untuk orders, 1 untuk users)

// ✅ BETTER - Nested Eager Loading
$orders = Order::with(['user', 'items.product'])->get();
```

**2. Select Only Needed Columns:**
```php
// ❌ BAD - Select all columns
$users = User::all();

// ✅ GOOD - Select specific columns
$users = User::select('id', 'name', 'email')->get();
```

**3. Use Indexes:**
```php
// Migration - Add indexes
Schema::table('orders', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('status');
    $table->index('created_at');
    $table->index(['user_id', 'status']); // Composite index
});
```

**4. Pagination:**
```php
// ❌ BAD - Load all records
$products = Product::all();

// ✅ GOOD - Use pagination
$products = Product::paginate(20);

// ✅ BETTER - Simple pagination (faster)
$products = Product::simplePaginate(20);
```

**5. Query Caching:**
```php
// Cache query results
$products = Cache::remember('featured_products', 3600, function () {
    return Product::where('is_featured', true)->get();
});
```

**6. Chunk Large Datasets:**
```php
// ❌ BAD - Load all records into memory
$orders = Order::all();
foreach ($orders as $order) {
    // Process order
}

// ✅ GOOD - Process in chunks
Order::chunk(100, function ($orders) {
    foreach ($orders as $order) {
        // Process order
    }
});
```

**7. Use Database Transactions:**
```php
DB::transaction(function () {
    $order = Order::create([...]);
    
    foreach ($items as $item) {
        $order->items()->create($item);
        Product::find($item['product_id'])->decrement('stock', $item['quantity']);
    }
});
```

### 6.2.2 Caching Strategy

Caching mengurangi load pada database dan mempercepat response time.

**1. Configuration Cache:**
```bash
# Cache configuration files
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

**2. Data Caching:**
```php
use Illuminate\Support\Facades\Cache;

// Store data in cache
Cache::put('key', 'value', $seconds);

// Store forever
Cache::forever('key', 'value');

// Remember (get or store)
$value = Cache::remember('users', 3600, function () {
    return DB::table('users')->get();
});

// Check if exists
if (Cache::has('key')) {
    $value = Cache::get('key');
}

// Remove from cache
Cache::forget('key');

// Clear all cache
Cache::flush();
```

**3. Cache Tags (Redis/Memcached):**
```php
// Tag cache items
Cache::tags(['products', 'featured'])->put('featured_products', $products, 3600);

// Retrieve tagged cache
$products = Cache::tags(['products', 'featured'])->get('featured_products');

// Flush specific tags
Cache::tags(['products'])->flush();
```

**4. Model Caching:**
```php
// app/Models/Product.php
public static function getFeatured()
{
    return Cache::remember('products.featured', 3600, function () {
        return static::where('is_featured', true)->get();
    });
}

// Clear cache saat update
public static function boot()
{
    parent::boot();
    
    static::saved(function () {
        Cache::forget('products.featured');
    });
    
    static::deleted(function () {
        Cache::forget('products.featured');
    });
}
```

**5. HTTP Caching:**
```php
// Set cache headers
return response($content)
    ->header('Cache-Control', 'public, max-age=3600')
    ->header('Expires', now()->addHour()->toRfc7231String());

// ETags
$etag = md5($content);
$requestEtag = $request->header('If-None-Match');

if ($requestEtag === $etag) {
    return response('', 304); // Not Modified
}

return response($content)
    ->header('ETag', $etag);
```

### 6.2.3 Lazy Loading Images

Lazy loading menunda loading gambar sampai user scroll ke area tersebut.

**1. Native Lazy Loading:**
```html
<img src="product.jpg" alt="Product" loading="lazy">
```

**2. JavaScript Lazy Loading:**
```html
<!-- Placeholder image -->
<img data-src="product.jpg" alt="Product" class="lazy">

<script>
document.addEventListener("DOMContentLoaded", function() {
    const lazyImages = document.querySelectorAll('img.lazy');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
});
</script>
```

**3. Progressive Image Loading:**
```html
<!-- Low quality placeholder -->
<img src="product-thumb.jpg" 
     data-src="product-full.jpg" 
     alt="Product" 
     class="progressive">

<script>
function loadFullImage(img) {
    const fullImage = new Image();
    fullImage.src = img.dataset.src;
    
    fullImage.onload = function() {
        img.src = fullImage.src;
        img.classList.add('loaded');
    };
}
</script>
```

### 6.2.4 Asset Minification

Minification mengurangi ukuran file CSS dan JavaScript.

**1. Laravel Mix Configuration:**
```javascript
// webpack.mix.js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .minify('public/js/app.js')
   .minify('public/css/app.css')
   .version(); // Cache busting
```

**2. Compile Assets:**
```bash
# Development
npm run dev

# Production (with minification)
npm run production
```

**3. Combine Files:**
```javascript
// Combine multiple CSS files
mix.styles([
    'public/css/vendor.css',
    'public/css/app.css'
], 'public/css/all.css');

// Combine multiple JS files
mix.scripts([
    'public/js/vendor.js',
    'public/js/app.js'
], 'public/js/all.js');
```

**4. Versioning for Cache Busting:**
```blade
<!-- Use mix() helper untuk auto-versioning -->
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
<script src="{{ mix('js/app.js') }}"></script>
```

### 6.2.5 CDN Implementation

CDN (Content Delivery Network) mempercepat delivery static assets.

**1. Configure CDN:**
```env
# .env
CDN_URL=https://cdn.example.com
ASSET_URL=https://cdn.example.com
```

**2. Use CDN for Assets:**
```blade
<!-- Automatic CDN URL -->
<img src="{{ asset('images/logo.png') }}">
<!-- Output: https://cdn.example.com/images/logo.png -->

<!-- External CDN untuk libraries -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
```

**3. Upload Assets to CDN:**
```php
// Sync assets to S3/CloudFront
use Illuminate\Support\Facades\Storage;

$file = $request->file('image');
$path = Storage::disk('s3')->put('images', $file, 'public');
$url = Storage::disk('s3')->url($path);
```

---

*Halaman 111-125 selesai. Lanjut ke bagian Responsive Design dan SEO...*
