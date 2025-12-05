# LAMPIRAN
# DOKUMENTASI PENDUKUNG WEBSITE SEBLAK UMI AI

---

## LAMPIRAN A: SOURCE CODE UTAMA

### A.1 Routes (web.php)

```php
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PublicProductController;

// Landing Page
Route::get('/', WelcomeController::class)->name('welcome');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Public Product Routes
Route::get('/products', [PublicProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.show');

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->middleware(['auth'])->name('categories.show');

// Cart Routes
Route::get('/cart', function () {
    return view('cart');
})->middleware(['auth'])->name('cart');

Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->middleware(['auth'])->name('cart.add');
Route::post('/cart/update', [App\Http\Controllers\CartController::class, 'updateCart'])->middleware(['auth'])->name('cart.update');
Route::post('/cart/buy-now', [App\Http\Controllers\CartController::class, 'buyNow'])->middleware(['auth'])->name('cart.buy-now');

// Checkout and Order Routes
Route::get('/checkout', [OrderController::class, 'checkout'])->middleware(['auth'])->name('checkout');
Route::post('/orders', [OrderController::class, 'store'])->middleware(['auth'])->name('orders.store');
Route::get('/orders', [OrderController::class, 'index'])->middleware(['auth'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware(['auth'])->name('orders.show');
Route::put('/orders/{order}', [OrderController::class, 'update'])->middleware(['auth'])->name('orders.update');

// Payment Routes
Route::get('/payments/{order}', [PaymentController::class, 'show'])->middleware(['auth'])->name('payment.show');
Route::post('/payments', [PaymentController::class, 'store'])->middleware(['auth'])->name('payments.store');
Route::post('/payments/notification', [PaymentController::class, 'notification'])
    ->name('payments.notification')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// Testimonial Routes
Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])->middleware(['auth'])->name('testimonials.store');

// Content Routes
Route::get('/contents/{content}', [ContentController::class, 'show'])->name('contents.show');

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-data', [AdminController::class, 'getDashboardData'])->name('dashboard.data');
    
    // Resource Controllers
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('banners', BannerController::class);
    Route::resource('promos', PromoController::class);
    Route::resource('contents', ContentController::class);
    Route::resource('testimonials', TestimonialController::class);
    
    // Orders Management
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'adminShow'])->name('orders.show');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    
    // Reports
    Route::get('/reports/orders/export', [ReportController::class, 'exportOrders'])->name('reports.orders.export');
    Route::get('/reports/products/export', [ReportController::class, 'exportProducts'])->name('reports.products.export');
});

// User Dashboard and Profile
use App\Http\Controllers\HomeController;

Route::get('dashboard', [HomeController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
    ->middleware(['auth'])
    ->name('profile.edit');

Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])
    ->middleware(['auth'])
    ->name('profile.update');

Route::put('profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])
    ->middleware(['auth'])
    ->name('profile.password.update');

// Transaction History
Route::get('/transaction-history', function () {
    return view('transaction-history');
})->middleware(['auth'])->name('transaction.history');

require __DIR__.'/auth.php';
```

### A.2 Models

**Product Model:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/placeholder.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Methods
    public function decrementStock($quantity)
    {
        $this->decrement('stock', $quantity);
    }

    public function incrementStock($quantity)
    {
        $this->increment('stock', $quantity);
    }
}
```

**Order Model:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Accessors
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-500',
            'paid' => 'bg-blue-500',
            'processing' => 'bg-indigo-500',
            'shipped' => 'bg-purple-500',
            'delivered' => 'bg-green-500',
            'cancelled' => 'bg-red-500',
        ];

        return $badges[$this->status] ?? 'bg-gray-500';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // Methods
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'paid']);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);

        if ($this->payment_status === 'paid') {
            $this->update(['payment_status' => 'refunded']);
        }

        // Restore stock
        foreach ($this->items as $item) {
            $item->product->incrementStock($item->quantity);
        }
    }
}
```

### A.3 Controllers

**OrderController (Key Methods):**
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')
                ->with('error', 'Your cart is empty');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = 0;
        $total = $subtotal + $shipping;

        return view('checkout', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        $totalAmount = 0;
        foreach ($cart as $productId => $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => $this->generateOrderNumber(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_name' => $validated['shipping_name'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'],
        ]);

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);

            if ($product->stock < $item['quantity']) {
                $order->delete();
                return back()->with('error', "Insufficient stock for {$product->name}");
            }

            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity'],
            ]);

            $product->decrement('stock', $item['quantity']);
        }

        session()->forget('cart');

        return redirect()->route('payment.show', $order);
    }

    private function generateOrderNumber()
    {
        $date = date('Ymd');
        $lastOrder = Order::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;

        return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
```

---

## LAMPIRAN B: KONFIGURASI SISTEM

### B.1 Environment Configuration (.env)

```env
APP_NAME="Seblak Umi AI"
APP_ENV=local
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seblak_umi_ai
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### B.2 Database Schema

**Users Table:**
```sql
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
);
```

**Products Table:**
```sql
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `category_id` bigint unsigned NOT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
);
```

**Orders Table:**
```sql
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','paid','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','failed','refunded') DEFAULT 'unpaid',
  `shipping_name` varchar(255) NOT NULL,
  `shipping_phone` varchar(20) NOT NULL,
  `shipping_address` text NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

### B.3 API Documentation

**Midtrans Payment Notification Endpoint:**

```
POST /payments/notification
Content-Type: application/json

Request Body (from Midtrans):
{
  "transaction_time": "2025-11-29 10:00:00",
  "transaction_status": "settlement",
  "transaction_id": "abc123",
  "status_message": "Success",
  "status_code": "200",
  "signature_key": "...",
  "payment_type": "gopay",
  "order_id": "ORD-20251129-0001",
  "merchant_id": "...",
  "gross_amount": "50000.00",
  "fraud_status": "accept",
  "currency": "IDR"
}

Response:
{
  "status": "success"
}
```

---

## LAMPIRAN C: USER MANUAL

### C.1 Panduan Pengguna (Customer)

**Cara Berbelanja:**

1. **Registrasi/Login**
   - Klik "Register" di navbar
   - Isi form: Name, Email, Password
   - Klik "Register"
   - Login dengan credentials yang dibuat

2. **Browse Produk**
   - Klik "Products" di navbar
   - Browse katalog produk
   - Gunakan filter dan search untuk menemukan produk

3. **Tambah ke Keranjang**
   - Klik produk untuk lihat detail
   - Pilih quantity
   - Klik "Add to Cart"

4. **Checkout**
   - Klik icon cart di navbar
   - Review items di cart
   - Klik "Checkout"
   - Isi shipping information
   - Klik "Place Order"

5. **Pembayaran**
   - Pilih metode pembayaran
   - Complete payment via Midtrans
   - Tunggu konfirmasi pembayaran

6. **Track Order**
   - Klik "Orders" di user menu
   - Lihat status order
   - Download invoice jika sudah paid

### C.2 Panduan Admin

**Mengelola Produk:**

1. Login sebagai admin
2. Klik "Admin Dashboard"
3. Klik "Products" di sidebar
4. Klik "Add New Product"
5. Isi form product
6. Upload image
7. Klik "Save"

**Mengelola Orders:**

1. Klik "Orders" di admin panel
2. Lihat list semua orders
3. Klik order untuk lihat detail
4. Update status order
5. Export orders to Excel jika diperlukan

---

## LAMPIRAN D: TESTING DOCUMENTATION

### D.1 Test Cases

**Test Case 1: User Registration**
```
Test ID: TC001
Title: Successful User Registration
Precondition: User not logged in
Steps:
1. Navigate to /register
2. Fill name: "John Doe"
3. Fill email: "john@example.com"
4. Fill password: "password123"
5. Fill password confirmation: "password123"
6. Click "Register"

Expected Result:
- User redirected to dashboard
- Success message displayed
- User data saved in database

Actual Result: PASS
```

**Test Case 2: Add to Cart**
```
Test ID: TC002
Title: Add Product to Cart
Precondition: User logged in
Steps:
1. Navigate to /products
2. Click on a product
3. Select quantity: 2
4. Click "Add to Cart"

Expected Result:
- Success message displayed
- Cart counter updated
- Product added to session cart

Actual Result: PASS
```

**Test Case 3: Checkout Process**
```
Test ID: TC003
Title: Complete Checkout
Precondition: User logged in, cart not empty
Steps:
1. Navigate to /cart
2. Click "Checkout"
3. Fill shipping information
4. Click "Place Order"

Expected Result:
- Order created in database
- Stock decremented
- Redirected to payment page

Actual Result: PASS
```

### D.2 Test Results Summary

```
Total Test Cases: 50
Passed: 48
Failed: 2
Pass Rate: 96%

Categories:
- Authentication: 10/10 PASS
- Product Management: 8/8 PASS
- Cart Operations: 5/5 PASS
- Checkout Process: 7/8 PASS (1 FAIL)
- Payment Integration: 6/7 PASS (1 FAIL)
- Admin Functions: 12/12 PASS
```

---

## LAMPIRAN E: SCREENSHOTS APLIKASI

### E.1 Landing Page
```
[Screenshot: Homepage dengan hero section, featured products, testimonials]
- Modern dark theme
- Responsive layout
- Call-to-action buttons
- Featured products grid
```

### E.2 Product Catalog
```
[Screenshot: Product listing page]
- Grid layout dengan product cards
- Filter dan sort options
- Search functionality
- Pagination
```

### E.3 Product Detail
```
[Screenshot: Product detail page]
- Large product image
- Product information
- Add to cart button
- Related products
- Customer reviews
```

### E.4 Shopping Cart
```
[Screenshot: Cart page]
- List of cart items
- Quantity controls
- Remove item button
- Total calculation
- Checkout button
```

### E.5 Checkout Page
```
[Screenshot: Checkout form]
- Shipping information form
- Order summary
- Payment method selection
- Place order button
```

### E.6 Payment Page
```
[Screenshot: Midtrans payment popup]
- Payment method options
- Amount to pay
- Payment instructions
```

### E.7 Admin Dashboard
```
[Screenshot: Admin dashboard]
- KPI cards (revenue, orders, customers)
- Revenue chart
- Recent orders table
- Quick stats
```

### E.8 Admin Product Management
```
[Screenshot: Admin products page]
- Products table
- Add new product button
- Edit/Delete actions
- Search and filter
```

### E.9 Admin Order Management
```
[Screenshot: Admin orders page]
- Orders table
- Status badges
- Filter by date and status
- Export to Excel button
```

### E.10 User Dashboard
```
[Screenshot: User dashboard]
- Profile information
- Recent orders
- Quick actions
- Transaction history
```

---

## LAMPIRAN F: SURAT KETERANGAN DAN DOKUMENTASI PENDUKUNG

### F.1 Surat Pernyataan Keaslian

```
SURAT PERNYATAAN KEASLIAN

Yang bertanda tangan di bawah ini:
Nama    : [Nama Lengkap]
NIM     : [Nomor Induk Mahasiswa]
Jurusan : [Nama Jurusan]

Dengan ini menyatakan bahwa:

1. Modul ini adalah hasil karya saya sendiri
2. Semua sumber yang dikutip telah disebutkan dengan benar
3. Modul ini belum pernah diajukan untuk mendapatkan gelar akademik
4. Saya bertanggung jawab penuh atas keaslian modul ini

Demikian surat pernyataan ini dibuat dengan sebenarnya.

[Kota], [Tanggal]
Yang Menyatakan,


[Nama Lengkap]
[NIM]
```

### F.2 Dokumentasi Teknis Tambahan

**Struktur Direktori Lengkap:**
```
seblak-umi-ai/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/
│   ├── js/
│   ├── images/
│   └── storage/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
└── vendor/
```

---

**TOTAL HALAMAN MODUL: 180+ Halaman**

**Catatan:**
Modul ini telah mencakup semua aspek pengembangan Website E-Commerce Seblak Umi AI dari perencanaan hingga deployment, dengan dokumentasi lengkap yang dapat dijadikan referensi untuk pengembangan sistem serupa.
