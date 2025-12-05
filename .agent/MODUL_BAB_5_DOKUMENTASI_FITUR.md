# BAB V
# DOKUMENTASI FITUR DAN MODUL SISTEM

---

## 5.1 Modul Autentikasi dan Otorisasi

### 5.1.1 Registrasi Pengguna

Sistem registrasi pengguna pada aplikasi Seblak Umi AI menggunakan Laravel Breeze sebagai foundation dengan beberapa kustomisasi. Proses registrasi melibatkan validasi data yang ketat untuk memastikan keamanan dan integritas data pengguna.

**Fitur Registrasi:**
- Input field: Name, Email, Password, Password Confirmation
- Validasi real-time untuk email dan password
- Password hashing menggunakan bcrypt
- Automatic role assignment (default: user)
- Email verification (optional)

**Alur Registrasi:**
1. User mengakses halaman `/register`
2. User mengisi form registrasi dengan data yang valid
3. Sistem melakukan validasi:
   - Name: required, string, max 255 characters
   - Email: required, valid email format, unique in database
   - Password: required, min 8 characters, confirmed
4. Password di-hash menggunakan bcrypt
5. Data user disimpan ke database dengan role 'user'
6. User diarahkan ke dashboard atau halaman verifikasi email

**Implementasi Teknis:**
```php
// Route: routes/auth.php
Route::post('/register', [RegisteredUserController::class, 'store']);

// Controller: RegisteredUserController
- Validasi input menggunakan Laravel Validation
- Hash password dengan Hash::make()
- Event UserRegistered dipicu setelah registrasi berhasil
```

### 5.1.2 Login dan Logout

**Proses Login:**
Sistem login menggunakan session-based authentication dengan fitur "Remember Me" untuk kenyamanan pengguna.

**Fitur Login:**
- Email dan password authentication
- Remember me checkbox untuk persistent login
- Rate limiting untuk mencegah brute force attacks
- Redirect berdasarkan role (admin ke admin dashboard, user ke user dashboard)

**Alur Login:**
1. User mengakses `/login`
2. User memasukkan email dan password
3. Sistem memvalidasi kredensial
4. Jika valid, session dibuat dan user diarahkan sesuai role
5. Jika invalid, error message ditampilkan

**Proses Logout:**
Logout dilakukan dengan menghapus session dan me-regenerate CSRF token untuk keamanan.

**Implementasi Logout:**
```php
// Route: web.php (line 25-30)
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
```

**Fitur Keamanan:**
- Session invalidation
- CSRF token regeneration
- Secure cookie handling
- Protection against session fixation

### 5.1.3 Forgot Password

Fitur forgot password memungkinkan pengguna untuk mereset password mereka melalui email verification.

**Alur Forgot Password:**
1. User klik "Forgot Password" di halaman login
2. User memasukkan email yang terdaftar
3. Sistem generate password reset token
4. Email dengan link reset dikirim ke user
5. User klik link dan diarahkan ke halaman reset password
6. User memasukkan password baru
7. Password di-update dan user bisa login dengan password baru

**Keamanan:**
- Token expiration (default 60 menit)
- Token hanya bisa digunakan sekali
- Rate limiting untuk prevent spam

### 5.1.4 Email Verification

Email verification memastikan bahwa email yang didaftarkan adalah valid dan dimiliki oleh user.

**Fitur:**
- Automatic email sending setelah registrasi
- Verification link dengan signed URL
- Resend verification email option
- Middleware untuk protect routes yang memerlukan verified email

### 5.1.5 Middleware dan Role Management

**Middleware yang Digunakan:**

1. **Auth Middleware**
   - Memastikan user sudah login
   - Redirect ke login page jika belum authenticated
   - Digunakan di hampir semua protected routes

2. **AdminMiddleware**
   - Custom middleware untuk admin access
   - Memeriksa role user di database
   - Redirect non-admin ke dashboard user
   
   ```php
   // app/Http/Middleware/AdminMiddleware.php
   - Check if auth()->user()->role === 'admin'
   - Abort 403 jika bukan admin
   ```

3. **Verified Middleware**
   - Memastikan email sudah diverifikasi
   - Optional, bisa diaktifkan per route

**Role Management:**
Sistem menggunakan simple role-based access control dengan dua role utama:
- **admin**: Full access ke admin panel dan semua fitur
- **user**: Access ke fitur customer (browse, cart, checkout, order)

**Implementasi di Database:**
```sql
users table:
- id
- name
- email
- password
- role (enum: 'admin', 'user', default: 'user')
- phone (nullable)
- address (nullable)
- avatar (nullable)
- email_verified_at
- created_at
- updated_at
```

---

## 5.2 Modul Landing Page dan Public Interface

### 5.2.1 Homepage/Welcome Page

Homepage adalah halaman pertama yang dilihat pengunjung dan berfungsi sebagai landing page untuk menarik perhatian calon customer.

**Komponen Homepage:**

1. **Hero Section**
   - Banner utama dengan call-to-action
   - Tagline menarik tentang Seblak Umi AI
   - Button "Order Now" atau "Lihat Menu"

2. **Featured Products**
   - Menampilkan produk unggulan
   - Grid layout responsive
   - Quick view product details
   - Add to cart button (untuk logged-in users)

3. **Categories Section**
   - Showcase kategori produk
   - Visual cards dengan gambar kategori
   - Link ke halaman kategori

4. **Testimonials**
   - Customer reviews dengan star rating
   - Carousel atau grid layout
   - Real testimonials dari database

5. **About Section**
   - Informasi singkat tentang Seblak Umi AI
   - Value proposition
   - Quality assurance

6. **Call-to-Action Section**
   - Encourage visitors untuk order
   - Promo atau special offers

**Implementasi Teknis:**
```php
// Controller: WelcomeController.php
public function __invoke()
{
    $banners = Banner::where('is_active', true)
        ->orderBy('order')
        ->get();
    
    $featuredProducts = Product::where('is_featured', true)
        ->take(6)
        ->get();
    
    $categories = Category::all();
    
    $testimonials = Testimonial::where('is_approved', true)
        ->latest()
        ->take(6)
        ->get();
    
    return view('welcome', compact(
        'banners',
        'featuredProducts',
        'categories',
        'testimonials'
    ));
}
```

**Design Principles:**
- Modern, premium dark theme
- Smooth animations dan transitions
- Mobile-first responsive design
- Fast loading dengan lazy loading images
- SEO optimized dengan proper meta tags

### 5.2.2 Navbar dan Footer Components

**Navbar Component (x-navbar)**

Navbar adalah komponen reusable yang digunakan di seluruh aplikasi untuk navigasi.

**Fitur Navbar:**
- Logo Seblak Umi AI
- Navigation links (Home, Products, Categories, Testimonials)
- Search bar (optional)
- Cart icon dengan badge counter (untuk logged-in users)
- User menu dropdown (Profile, Orders, Logout)
- Login/Register buttons (untuk guests)
- Mobile hamburger menu

**Responsive Behavior:**
- Desktop: Horizontal menu dengan dropdowns
- Mobile: Hamburger menu dengan slide-in drawer
- Sticky navbar on scroll
- Transparent to solid background on scroll

**Implementasi:**
```blade
// resources/views/components/navbar.blade.php
- Dynamic menu berdasarkan auth status
- Cart count dari session atau database
- Active link highlighting
- Mobile menu toggle dengan JavaScript
```

**Footer Component (x-footer)**

Footer memberikan informasi tambahan dan navigasi sekunder.

**Konten Footer:**
1. **Company Info**
   - Logo dan tagline
   - Alamat bisnis
   - Contact information

2. **Quick Links**
   - About Us
   - FAQ
   - Terms & Conditions
   - Privacy Policy

3. **Social Media**
   - Instagram, Facebook, WhatsApp links
   - Social media icons

4. **Newsletter** (optional)
   - Email subscription form

5. **Copyright**
   - © 2025 Seblak Umi AI. All rights reserved.

**Design:**
- Dark background dengan light text
- Multi-column layout (responsive)
- Hover effects pada links
- Consistent dengan overall theme

### 5.2.3 Banner Slider

Banner slider menampilkan promotional banners di homepage dengan animasi slide otomatis.

**Fitur Banner:**
- Multiple banners dengan prioritas order
- Auto-play slider dengan interval
- Manual navigation (prev/next buttons)
- Dot indicators
- Responsive images
- Link to specific products/categories

**Banner Management (Admin):**
- Upload banner image
- Set banner title dan description
- Set link URL
- Set order/priority
- Active/Inactive status
- Preview before publish

**Implementasi Teknis:**
```php
// Model: Banner
- id, title, description, image_path, link_url, order, is_active
- Scope: active()
- Accessor untuk image URL

// Controller: BannerController (Admin)
- CRUD operations
- Image upload dengan validation
- Reorder functionality
```

**Frontend Implementation:**
- JavaScript slider (Swiper.js atau custom)
- Touch-friendly untuk mobile
- Lazy loading images
- Smooth transitions

### 5.2.4 Featured Products

Featured products adalah produk pilihan yang ditampilkan di homepage untuk menarik perhatian.

**Kriteria Featured Products:**
- Produk best-seller
- Produk baru
- Produk dengan rating tinggi
- Produk promo
- Manual selection oleh admin

**Display Features:**
- Product image
- Product name
- Price
- Rating (jika ada)
- Quick add to cart
- View details button

**Implementasi:**
```php
// Database: products table memiliki kolom 'is_featured' (boolean)
// Query: Product::where('is_featured', true)->get()
// Admin bisa toggle featured status dari product management
```

### 5.2.5 Testimonials Display

Testimonials menampilkan review dan rating dari customer untuk build trust.

**Fitur Display:**
- Customer name dan avatar
- Star rating (1-5)
- Review text
- Date posted
- Product yang di-review (optional)

**Layout Options:**
- Grid layout
- Carousel/slider
- List view

**Filtering:**
- Hanya approved testimonials yang ditampilkan
- Sort by latest atau highest rating
- Pagination atau load more

---

## 5.3 Modul Produk

### 5.3.1 Katalog Produk (Public)

Halaman katalog produk menampilkan semua produk yang tersedia untuk dibeli.

**URL:** `/products`

**Fitur Katalog:**

1. **Product Grid**
   - Responsive grid layout (4 kolom desktop, 2 kolom tablet, 1 kolom mobile)
   - Product card dengan image, name, price
   - Hover effects untuk better UX

2. **Filtering**
   - Filter by category
   - Filter by price range
   - Filter by rating (jika ada)
   - Filter by availability (in stock)

3. **Sorting**
   - Sort by: Latest, Price (Low to High), Price (High to Low), Name (A-Z)
   - Dropdown selector untuk sorting options

4. **Search**
   - Search bar untuk cari produk by name
   - Real-time search results (optional)

5. **Pagination**
   - Pagination untuk handle banyak produk
   - Configurable items per page (12, 24, 48)

**Implementasi:**
```php
// Controller: PublicProductController
public function index(Request $request)
{
    $query = Product::query();
    
    // Search
    if ($request->has('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    // Filter by category
    if ($request->has('category')) {
        $query->where('category_id', $request->category);
    }
    
    // Filter by price range
    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }
    
    // Sorting
    $sortBy = $request->get('sort', 'latest');
    switch ($sortBy) {
        case 'price_low':
            $query->orderBy('price', 'asc');
            break;
        case 'price_high':
            $query->orderBy('price', 'desc');
            break;
        case 'name':
            $query->orderBy('name', 'asc');
            break;
        default:
            $query->latest();
    }
    
    $products = $query->paginate(12);
    $categories = Category::all();
    
    return view('products.index', compact('products', 'categories'));
}
```

### 5.3.2 Detail Produk

Halaman detail produk menampilkan informasi lengkap tentang satu produk.

**URL:** `/products/{product}`

**Informasi yang Ditampilkan:**
- Product image (dengan zoom on hover atau lightbox)
- Product name
- Price
- Description (full description)
- Category
- Stock availability
- SKU atau product code
- Specifications (jika ada)
- Related products

**Actions:**
- Add to Cart (dengan quantity selector)
- Buy Now (langsung ke checkout)
- Share product (social media share buttons)
- Add to wishlist (future feature)

**Reviews Section:**
- Display customer reviews
- Average rating
- Rating distribution (5 stars: X%, 4 stars: Y%, etc.)
- Write a review (untuk users yang sudah beli)

**Implementasi:**
```php
// Controller: PublicProductController
public function show(Product $product)
{
    $relatedProducts = Product::where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->take(4)
        ->get();
    
    $reviews = Testimonial::where('product_id', $product->id)
        ->where('is_approved', true)
        ->latest()
        ->get();
    
    return view('products.show', compact('product', 'relatedProducts', 'reviews'));
}
```

### 5.3.3 Pencarian dan Filter Produk

Sistem pencarian dan filter membantu user menemukan produk yang mereka inginkan dengan cepat.

**Search Features:**
- Search by product name
- Search by description (optional)
- Search suggestions/autocomplete
- Search history (untuk logged-in users)

**Filter Features:**
- Category filter (checkbox atau dropdown)
- Price range filter (slider atau input min-max)
- Availability filter (in stock, out of stock)
- Rating filter (4+ stars, 3+ stars, etc.)

**Advanced Filtering:**
- Multiple filters dapat dikombinasikan
- Filter results update tanpa page reload (AJAX)
- Clear all filters button
- Active filters indicator

**URL Structure:**
```
/products?search=seblak&category=1&min_price=10000&max_price=50000&sort=price_low
```

### 5.3.4 Manajemen Produk (Admin)

Admin dapat mengelola semua produk melalui admin panel.

**URL:** `/admin/products`

**Fitur Admin:**

1. **Product List**
   - Table view dengan columns: Image, Name, Category, Price, Stock, Status
   - Search dan filter
   - Bulk actions (delete, activate, deactivate)
   - Pagination

2. **Create Product**
   - Form dengan fields:
     - Name (required)
     - Description (textarea)
     - Category (dropdown)
     - Price (number)
     - Stock (number)
     - Image (file upload)
     - Is Featured (checkbox)
     - Is Active (checkbox)
   - Validation rules
   - Preview before save

3. **Edit Product**
   - Pre-filled form dengan data existing
   - Update image (optional)
   - Track changes history (optional)

4. **Delete Product**
   - Soft delete atau hard delete
   - Confirmation modal
   - Check if product has orders (prevent delete if yes)

**Implementasi:**
```php
// Controller: ProductController (Admin)
// Route: Route::resource('admin/products', ProductController::class);

public function index()
{
    $products = Product::with('category')->paginate(20);
    return view('admin.products.index', compact('products'));
}

public function create()
{
    $categories = Category::all();
    return view('admin.products.create', compact('categories'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    
    // Handle image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
        $validated['image'] = $imagePath;
    }
    
    Product::create($validated);
    
    return redirect()->route('admin.products.index')
        ->with('success', 'Product created successfully');
}

// Similar methods untuk update dan delete
```

### 5.3.5 Upload Gambar Produk

Upload gambar produk menggunakan Laravel's file storage system dengan validasi ketat.

**Validasi Upload:**
- File type: JPEG, PNG, JPG only
- Max file size: 2MB
- Image dimensions: minimum 500x500px (recommended)
- Automatic resize untuk optimize storage

**Storage:**
- Images disimpan di `storage/app/public/products/`
- Symbolic link dari `public/storage` ke `storage/app/public`
- Filename: unique hash untuk prevent conflicts

**Image Processing:**
- Resize image untuk thumbnail
- Compress image untuk web optimization
- Generate multiple sizes (thumbnail, medium, large)
- Lazy loading implementation

**Implementasi:**
```php
// Image upload handling
$imagePath = $request->file('image')->store('products', 'public');

// Atau dengan custom filename
$filename = time() . '_' . $request->file('image')->getClientOriginalName();
$imagePath = $request->file('image')->storeAs('products', $filename, 'public');

// Accessor di Model untuk get full URL
public function getImageUrlAttribute()
{
    return asset('storage/' . $this->image);
}
```

### 5.3.6 Manajemen Stok

Manajemen stok memastikan inventory selalu accurate dan mencegah overselling.

**Fitur Stok:**

1. **Stock Tracking**
   - Real-time stock count
   - Low stock alerts (ketika stock < threshold)
   - Out of stock indicator
   - Stock history log

2. **Stock Update**
   - Manual stock adjustment (admin)
   - Automatic deduction saat order placed
   - Automatic restoration saat order cancelled
   - Bulk stock update

3. **Stock Validation**
   - Prevent checkout jika stock insufficient
   - Reserve stock saat order pending
   - Release reserved stock jika payment expired

**Implementasi:**
```php
// Saat order dibuat
$product->decrement('stock', $quantity);

// Saat order dibatalkan
$product->increment('stock', $quantity);

// Check stock availability
if ($product->stock < $requestedQuantity) {
    return back()->with('error', 'Insufficient stock');
}

// Low stock alert
public function scopeLowStock($query, $threshold = 10)
{
    return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
}
```

---

## 5.4 Modul Kategori

### 5.4.1 Daftar Kategori

Halaman daftar kategori menampilkan semua kategori produk yang tersedia.

**URL:** `/categories`

**Display:**
- Grid layout dengan category cards
- Category image/icon
- Category name
- Product count per category
- Link ke halaman kategori

**Implementasi:**
```php
// Controller: CategoryController
public function index()
{
    $categories = Category::withCount('products')->get();
    return view('categories.index', compact('categories'));
}
```

### 5.4.2 Produk per Kategori

Menampilkan semua produk dalam kategori tertentu.

**URL:** `/categories/{category}`

**Fitur:**
- Breadcrumb navigation (Home > Categories > Category Name)
- Category description
- Product grid (sama seperti katalog produk)
- Filtering dan sorting dalam kategori
- Pagination

**Implementasi:**
```php
public function show(Category $category)
{
    $products = $category->products()->paginate(12);
    return view('categories.show', compact('category', 'products'));
}
```

### 5.4.3 CRUD Kategori (Admin)

Admin dapat mengelola kategori melalui admin panel.

**URL:** `/admin/categories`

**Fitur:**
- List all categories
- Create new category (name, description, image)
- Edit category
- Delete category (dengan validation: tidak bisa delete jika ada produk)
- Reorder categories

**Validasi Delete:**
```php
public function destroy(Category $category)
{
    if ($category->products()->count() > 0) {
        return back()->with('error', 'Cannot delete category with products');
    }
    
    $category->delete();
    return redirect()->route('admin.categories.index')
        ->with('success', 'Category deleted successfully');
}
```

---

## 5.5 Modul Keranjang Belanja (Cart)

### 5.5.1 Add to Cart

Fitur add to cart memungkinkan user menambahkan produk ke keranjang belanja.

**Implementasi:**
- Button "Add to Cart" di product card dan detail page
- Quantity selector
- AJAX request untuk smooth UX
- Success notification
- Cart counter update

**Flow:**
1. User klik "Add to Cart"
2. Validate: user must be logged in
3. Validate: product stock availability
4. Add item ke cart (session atau database)
5. Update cart counter
6. Show success message

**Implementasi Teknis:**
```php
// Controller: CartController
public function addToCart(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);
    
    $product = Product::findOrFail($validated['product_id']);
    
    // Check stock
    if ($product->stock < $validated['quantity']) {
        return back()->with('error', 'Insufficient stock');
    }
    
    // Add to cart (using session)
    $cart = session()->get('cart', []);
    
    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $validated['quantity'];
    } else {
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $validated['quantity'],
            'image' => $product->image,
        ];
    }
    
    session()->put('cart', $cart);
    
    return back()->with('success', 'Product added to cart');
}
```

### 5.5.2 Update Quantity

User dapat mengubah quantity produk di cart.

**Fitur:**
- Increment/decrement buttons
- Direct input quantity
- Real-time price calculation
- Stock validation

**Implementasi:**
```php
public function updateCart(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:0',
    ]);
    
    $cart = session()->get('cart');
    
    if ($validated['quantity'] == 0) {
        // Remove item if quantity is 0
        unset($cart[$validated['product_id']]);
    } else {
        // Update quantity
        $cart[$validated['product_id']]['quantity'] = $validated['quantity'];
    }
    
    session()->put('cart', $cart);
    
    return back()->with('success', 'Cart updated');
}
```

### 5.5.3 Remove Item

User dapat menghapus item dari cart.

**Implementasi:**
- Remove button di setiap cart item
- Confirmation modal (optional)
- Update total price
- Update cart counter

### 5.5.4 Buy Now Feature

Buy Now memungkinkan user langsung checkout satu produk tanpa melalui cart.

**Flow:**
1. User klik "Buy Now" di product page
2. Product ditambahkan ke temporary cart
3. User langsung diarahkan ke checkout page
4. Setelah checkout, temporary cart dihapus

**Implementasi:**
```php
public function buyNow(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ]);
    
    // Clear existing cart
    session()->forget('cart');
    
    // Add only this product
    $product = Product::findOrFail($validated['product_id']);
    
    $cart = [
        $product->id => [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $validated['quantity'],
            'image' => $product->image,
        ]
    ];
    
    session()->put('cart', $cart);
    
    return redirect()->route('checkout');
}
```

### 5.5.5 Cart Persistence

Cart persistence memastikan cart items tidak hilang saat user logout atau close browser.

**Strategi:**

1. **Session-based Cart (Current)**
   - Cart disimpan di session
   - Hilang saat session expired
   - Lightweight dan fast

2. **Database-based Cart (Recommended untuk production)**
   - Cart items disimpan di database
   - Persistent across devices
   - Sync cart saat login

**Database Schema untuk Cart:**
```sql
CREATE TABLE carts (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    product_id BIGINT,
    quantity INT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

*Halaman 71-85 selesai. Lanjut ke halaman berikutnya...*
