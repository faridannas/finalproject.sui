# BAB V - PART 3
# DOKUMENTASI FITUR DAN MODUL SISTEM (Lanjutan)

---

## 5.9 Modul Dashboard User

### 5.9.1 User Profile Management

User dapat mengelola informasi profil mereka melalui dashboard.

**URL:** `/profile`

**Informasi Profil yang Dapat Dikelola:**

1. **Personal Information**
   - Name (required)
   - Email (required, unique)
   - Phone number (optional)
   - Address (optional)

2. **Avatar/Profile Picture**
   - Upload photo
   - Preview before save
   - Crop/resize functionality
   - Default avatar jika tidak upload

3. **Password Management**
   - Change password
   - Current password verification
   - Password strength indicator
   - Password confirmation

**Implementasi:**
```php
// Controller: ProfileController
public function edit()
{
    $user = auth()->user();
    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    $user = auth()->user();
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);
    
    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        // Delete old avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar'] = $avatarPath;
    }
    
    $user->update($validated);
    
    return back()->with('success', 'Profile updated successfully');
}

public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'current_password' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);
    
    $user = auth()->user();
    
    // Verify current password
    if (!Hash::check($validated['current_password'], $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect']);
    }
    
    // Update password
    $user->update([
        'password' => Hash::make($validated['password']),
    ]);
    
    return back()->with('success', 'Password updated successfully');
}
```

**Frontend Form:**
```blade
<!-- Profile Edit Form -->
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- Avatar Upload -->
    <div class="mb-4">
        <label>Profile Picture</label>
        <div class="flex items-center gap-4">
            <img src="{{ auth()->user()->avatar_url }}" 
                 alt="Avatar" 
                 class="w-20 h-20 rounded-full object-cover">
            <input type="file" name="avatar" accept="image/*">
        </div>
    </div>
    
    <!-- Name -->
    <div class="mb-4">
        <label>Name</label>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
    </div>
    
    <!-- Email -->
    <div class="mb-4">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
    </div>
    
    <!-- Phone -->
    <div class="mb-4">
        <label>Phone</label>
        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
    </div>
    
    <!-- Address -->
    <div class="mb-4">
        <label>Address</label>
        <textarea name="address" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
    </div>
    
    <button type="submit">Update Profile</button>
</form>

<!-- Change Password Form -->
<form method="POST" action="{{ route('profile.password.update') }}">
    @csrf
    @method('PUT')
    
    <div class="mb-4">
        <label>Current Password</label>
        <input type="password" name="current_password" required>
    </div>
    
    <div class="mb-4">
        <label>New Password</label>
        <input type="password" name="password" required>
    </div>
    
    <div class="mb-4">
        <label>Confirm New Password</label>
        <input type="password" name="password_confirmation" required>
    </div>
    
    <button type="submit">Change Password</button>
</form>
```

### 5.9.2 Transaction History

User dapat melihat riwayat transaksi mereka.

**URL:** `/transaction-history`

**Fitur Transaction History:**

1. **Transaction List**
   - Order number
   - Date and time
   - Items purchased (summary)
   - Total amount
   - Payment status
   - Order status

2. **Filtering**
   - Filter by date range
   - Filter by status (All, Pending, Paid, Completed, Cancelled)
   - Filter by payment method

3. **Sorting**
   - Sort by date (newest/oldest)
   - Sort by amount (highest/lowest)

4. **Actions**
   - View details
   - Download invoice
   - Reorder (buy again)
   - Leave review

**Implementasi dengan Livewire:**
```php
// Livewire Component: TransactionHistory
namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class TransactionHistory extends Component
{
    use WithPagination;
    
    public $statusFilter = 'all';
    public $dateFrom;
    public $dateTo;
    public $search;
    
    public function render()
    {
        $query = Order::where('user_id', auth()->id())
            ->with('items.product');
        
        // Filter by status
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        
        // Filter by date range
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        
        // Search by order number
        if ($this->search) {
            $query->where('order_number', 'like', '%' . $this->search . '%');
        }
        
        $transactions = $query->latest()->paginate(10);
        
        return view('livewire.transaction-history', [
            'transactions' => $transactions
        ]);
    }
}
```

### 5.9.3 Order Tracking

User dapat melacak status pengiriman order mereka.

**Tracking Information:**
- Order status timeline
- Estimated delivery date
- Tracking number (jika sudah shipped)
- Courier information
- Current location (jika terintegrasi dengan courier API)

**Order Timeline:**
```
✓ Order Placed - 29 Nov 2025, 10:00
✓ Payment Confirmed - 29 Nov 2025, 10:05
✓ Order Processing - 29 Nov 2025, 11:00
⏳ Shipped - Expected: 30 Nov 2025
○ Delivered - Expected: 1 Dec 2025
```

**Implementation:**
```blade
<!-- Order Tracking Timeline -->
<div class="order-timeline">
    @php
        $statuses = [
            'pending' => 'Order Placed',
            'paid' => 'Payment Confirmed',
            'processing' => 'Order Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered'
        ];
        
        $currentStatusIndex = array_search($order->status, array_keys($statuses));
    @endphp
    
    @foreach($statuses as $key => $label)
        @php
            $statusIndex = array_search($key, array_keys($statuses));
            $isCompleted = $statusIndex <= $currentStatusIndex;
            $isCurrent = $key === $order->status;
        @endphp
        
        <div class="timeline-item {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
            <div class="timeline-icon">
                @if($isCompleted)
                    <i class="fas fa-check"></i>
                @elseif($isCurrent)
                    <i class="fas fa-spinner fa-spin"></i>
                @else
                    <i class="far fa-circle"></i>
                @endif
            </div>
            <div class="timeline-content">
                <h4>{{ $label }}</h4>
                @if($isCompleted)
                    <p>{{ $order->updated_at->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    @endforeach
</div>
```

### 5.9.4 Avatar Upload

User dapat mengupload dan mengubah avatar mereka.

**Avatar Features:**
- Drag & drop upload
- Preview before save
- Crop/resize functionality
- Default avatar generator (initials)
- Supported formats: JPG, PNG, GIF
- Max file size: 2MB

**Implementation dengan Image Cropper:**
```javascript
// Frontend: Image Cropper
let cropper;

document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(event) {
            const img = document.getElementById('avatarPreview');
            img.src = event.target.result;
            
            // Initialize cropper
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(img, {
                aspectRatio: 1,
                viewMode: 1,
                minCropBoxWidth: 200,
                minCropBoxHeight: 200,
                ready: function() {
                    // Cropper is ready
                }
            });
        };
        
        reader.readAsDataURL(file);
    }
});

// Save cropped image
document.getElementById('saveAvatar').addEventListener('click', function() {
    if (cropper) {
        cropper.getCroppedCanvas({
            width: 400,
            height: 400
        }).toBlob(function(blob) {
            const formData = new FormData();
            formData.append('avatar', blob, 'avatar.jpg');
            
            fetch('/profile/avatar', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    }
});
```

---

## 5.10 Modul Testimonial dan Rating

### 5.10.1 Submit Testimonial

User yang sudah melakukan pembelian dapat memberikan testimonial/review.

**URL:** `/testimonials` (POST)

**Syarat Submit Testimonial:**
- User harus login
- User harus pernah membeli produk (optional)
- Satu user bisa submit multiple testimonials
- Review bisa untuk produk tertentu atau general

**Form Testimonial:**
```blade
<form method="POST" action="{{ route('testimonials.store') }}">
    @csrf
    
    <!-- Product Selection (optional) -->
    <div class="mb-4">
        <label>Product (Optional)</label>
        <select name="product_id">
            <option value="">General Review</option>
            @foreach($purchasedProducts as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </div>
    
    <!-- Rating -->
    <div class="mb-4">
        <label>Rating</label>
        <div class="star-rating">
            <input type="radio" name="rating" value="5" id="star5">
            <label for="star5">★</label>
            <input type="radio" name="rating" value="4" id="star4">
            <label for="star4">★</label>
            <input type="radio" name="rating" value="3" id="star3">
            <label for="star3">★</label>
            <input type="radio" name="rating" value="2" id="star2">
            <label for="star2">★</label>
            <input type="radio" name="rating" value="1" id="star1">
            <label for="star1">★</label>
        </div>
    </div>
    
    <!-- Review Text -->
    <div class="mb-4">
        <label>Your Review</label>
        <textarea name="review" rows="5" required 
                  placeholder="Share your experience..."></textarea>
    </div>
    
    <button type="submit">Submit Review</button>
</form>
```

**Backend Implementation:**
```php
// Controller: TestimonialController
public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'nullable|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'required|string|min:10',
    ]);
    
    // Check if user has purchased the product (optional)
    if ($validated['product_id']) {
        $hasPurchased = Order::where('user_id', auth()->id())
            ->where('payment_status', 'paid')
            ->whereHas('items', function($query) use ($validated) {
                $query->where('product_id', $validated['product_id']);
            })
            ->exists();
        
        if (!$hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased');
        }
    }
    
    Testimonial::create([
        'user_id' => auth()->id(),
        'product_id' => $validated['product_id'],
        'rating' => $validated['rating'],
        'review' => $validated['review'],
        'is_approved' => false, // Requires admin approval
    ]);
    
    return back()->with('success', 'Thank you for your review! It will be published after approval.');
}
```

### 5.10.2 Star Rating System

Star rating system memungkinkan user memberikan rating 1-5 bintang.

**Rating Display:**
- Visual stars (filled/empty)
- Numeric rating (4.5/5)
- Total reviews count
- Rating distribution

**Star Rating Component:**
```blade
<!-- Display Star Rating -->
@php
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
@endphp

<div class="star-rating-display">
    @for($i = 0; $i < $fullStars; $i++)
        <i class="fas fa-star text-yellow-400"></i>
    @endfor
    
    @if($halfStar)
        <i class="fas fa-star-half-alt text-yellow-400"></i>
    @endif
    
    @for($i = 0; $i < $emptyStars; $i++)
        <i class="far fa-star text-yellow-400"></i>
    @endfor
    
    <span class="ml-2 text-gray-600">{{ number_format($rating, 1) }}/5</span>
    <span class="text-gray-500">({{ $totalReviews }} reviews)</span>
</div>
```

**Rating Distribution:**
```php
// Calculate rating distribution
public function getRatingDistribution($productId = null)
{
    $query = Testimonial::where('is_approved', true);
    
    if ($productId) {
        $query->where('product_id', $productId);
    }
    
    $distribution = [];
    for ($i = 5; $i >= 1; $i--) {
        $count = (clone $query)->where('rating', $i)->count();
        $distribution[$i] = $count;
    }
    
    $total = array_sum($distribution);
    
    // Calculate percentages
    foreach ($distribution as $rating => $count) {
        $distribution[$rating] = [
            'count' => $count,
            'percentage' => $total > 0 ? ($count / $total) * 100 : 0
        ];
    }
    
    return $distribution;
}
```

**Display Rating Distribution:**
```blade
<div class="rating-distribution">
    @foreach($distribution as $stars => $data)
        <div class="flex items-center gap-2 mb-2">
            <span class="w-12">{{ $stars }} ★</span>
            <div class="flex-1 bg-gray-200 rounded-full h-2">
                <div class="bg-yellow-400 h-2 rounded-full" 
                     style="width: {{ $data['percentage'] }}%"></div>
            </div>
            <span class="w-12 text-right text-sm text-gray-600">
                {{ $data['count'] }}
            </span>
        </div>
    @endforeach
</div>
```

### 5.10.3 Testimonial Moderation (Admin)

Admin dapat memoderasi testimonials sebelum dipublikasikan.

**URL:** `/admin/testimonials`

**Admin Features:**

1. **View All Testimonials**
   - Pending approval
   - Approved
   - Rejected

2. **Approve/Reject**
   - Approve button
   - Reject button
   - Bulk actions

3. **Delete Inappropriate Reviews**
   - Delete button dengan confirmation
   - Reason for deletion (optional)

4. **Filter dan Search**
   - Filter by status
   - Filter by rating
   - Search by user or product

**Implementation:**
```php
// Controller: TestimonialController (Admin)
public function index()
{
    $testimonials = Testimonial::with('user', 'product')
        ->latest()
        ->paginate(20);
    
    return view('admin.testimonials.index', compact('testimonials'));
}

public function update(Request $request, Testimonial $testimonial)
{
    $validated = $request->validate([
        'is_approved' => 'required|boolean',
    ]);
    
    $testimonial->update($validated);
    
    $status = $validated['is_approved'] ? 'approved' : 'rejected';
    
    return back()->with('success', "Testimonial {$status} successfully");
}

public function destroy(Testimonial $testimonial)
{
    $testimonial->delete();
    
    return back()->with('success', 'Testimonial deleted successfully');
}
```

**Admin View:**
```blade
<table>
    <thead>
        <tr>
            <th>User</th>
            <th>Product</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($testimonials as $testimonial)
            <tr>
                <td>{{ $testimonial->user->name }}</td>
                <td>{{ $testimonial->product->name ?? 'General' }}</td>
                <td>
                    @for($i = 0; $i < $testimonial->rating; $i++)
                        ★
                    @endfor
                </td>
                <td>{{ Str::limit($testimonial->review, 50) }}</td>
                <td>
                    @if($testimonial->is_approved)
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </td>
                <td>{{ $testimonial->created_at->format('d M Y') }}</td>
                <td>
                    @if(!$testimonial->is_approved)
                        <form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_approved" value="1">
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $testimonial) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
```

### 5.10.4 Public Testimonial Display

Testimonials yang sudah approved ditampilkan di halaman public.

**URL:** `/testimonials`

**Display Features:**
- Grid atau list layout
- User avatar dan name
- Star rating
- Review text
- Product name (jika review untuk produk tertentu)
- Date posted
- Helpful votes (optional future feature)

**Implementation:**
```php
public function index()
{
    $testimonials = Testimonial::where('is_approved', true)
        ->with('user', 'product')
        ->latest()
        ->paginate(12);
    
    // Calculate average rating
    $averageRating = Testimonial::where('is_approved', true)
        ->avg('rating');
    
    $totalReviews = Testimonial::where('is_approved', true)->count();
    
    return view('testimonials.index', compact(
        'testimonials',
        'averageRating',
        'totalReviews'
    ));
}
```

---

## 5.11 Modul Banner Management

### 5.11.1 CRUD Banner

Admin dapat mengelola banner untuk homepage slider.

**Create Banner:**
```php
public function create()
{
    return view('admin.banners.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB
        'link_url' => 'nullable|url',
        'order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ]);
    
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('banners', 'public');
        $validated['image_path'] = $imagePath;
    }
    
    Banner::create($validated);
    
    return redirect()->route('admin.banners.index')
        ->with('success', 'Banner created successfully');
}
```

### 5.11.2 Banner Ordering

Banner dapat diurutkan berdasarkan priority order.

**Reorder Implementation:**
```php
// Drag & drop reorder dengan JavaScript
// Update order via AJAX

public function updateOrder(Request $request)
{
    $orders = $request->input('orders'); // Array of [id => order]
    
    foreach ($orders as $id => $order) {
        Banner::where('id', $id)->update(['order' => $order]);
    }
    
    return response()->json(['success' => true]);
}
```

### 5.11.3 Banner Status (Active/Inactive)

Admin dapat mengaktifkan atau menonaktifkan banner.

**Toggle Status:**
```php
public function toggleStatus(Banner $banner)
{
    $banner->update([
        'is_active' => !$banner->is_active
    ]);
    
    return back()->with('success', 'Banner status updated');
}
```

---

## 5.12 Modul Promo Management

### 5.12.1 Create Promo

Admin dapat membuat promo code untuk diskon.

**Promo Schema:**
```sql
CREATE TABLE promos (
    id BIGINT PRIMARY KEY,
    code VARCHAR(50) UNIQUE,
    description TEXT,
    discount_type ENUM('percentage', 'fixed'),
    discount_value DECIMAL(10,2),
    min_purchase DECIMAL(10,2) NULLABLE,
    max_discount DECIMAL(10,2) NULLABLE,
    usage_limit INT NULLABLE,
    used_count INT DEFAULT 0,
    valid_from DATETIME,
    valid_until DATETIME,
    is_active BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Create Promo:**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|string|max:50|unique:promos',
        'description' => 'nullable|string',
        'discount_type' => 'required|in:percentage,fixed',
        'discount_value' => 'required|numeric|min:0',
        'min_purchase' => 'nullable|numeric|min:0',
        'max_discount' => 'nullable|numeric|min:0',
        'usage_limit' => 'nullable|integer|min:1',
        'valid_from' => 'required|date',
        'valid_until' => 'required|date|after:valid_from',
    ]);
    
    Promo::create($validated);
    
    return redirect()->route('admin.promos.index')
        ->with('success', 'Promo created successfully');
}
```

### 5.12.2 Promo Validation

Validasi promo code saat checkout.

**Validation Logic:**
```php
public function validatePromo($code, $totalAmount)
{
    $promo = Promo::where('code', $code)
        ->where('is_active', true)
        ->where('valid_from', '<=', now())
        ->where('valid_until', '>=', now())
        ->first();
    
    if (!$promo) {
        return ['valid' => false, 'message' => 'Invalid promo code'];
    }
    
    // Check usage limit
    if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) {
        return ['valid' => false, 'message' => 'Promo code has reached usage limit'];
    }
    
    // Check minimum purchase
    if ($promo->min_purchase && $totalAmount < $promo->min_purchase) {
        return [
            'valid' => false,
            'message' => "Minimum purchase of Rp " . number_format($promo->min_purchase) . " required"
        ];
    }
    
    // Calculate discount
    if ($promo->discount_type === 'percentage') {
        $discount = ($totalAmount * $promo->discount_value) / 100;
        
        // Apply max discount if set
        if ($promo->max_discount && $discount > $promo->max_discount) {
            $discount = $promo->max_discount;
        }
    } else {
        $discount = $promo->discount_value;
    }
    
    return [
        'valid' => true,
        'promo' => $promo,
        'discount' => $discount,
        'final_amount' => $totalAmount - $discount
    ];
}
```

### 5.12.3 Promo Display

Display active promos di homepage atau checkout page.

---

## 5.13 Modul Content Management

### 5.13.1 Static Content Pages

Admin dapat membuat halaman static content seperti About Us, Terms & Conditions, Privacy Policy.

**Content Schema:**
```sql
CREATE TABLE contents (
    id BIGINT PRIMARY KEY,
    slug VARCHAR(255) UNIQUE,
    title VARCHAR(255),
    body LONGTEXT,
    is_published BOOLEAN DEFAULT 1,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 5.13.2 Content Editor

Admin menggunakan rich text editor untuk membuat content.

**Implementation dengan TinyMCE atau CKEditor:**
```blade
<textarea id="contentEditor" name="body">{{ old('body', $content->body ?? '') }}</textarea>

<script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#contentEditor',
        plugins: 'lists link image table code',
        toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
        height: 500
    });
</script>
```

### 5.13.3 Content Display

Display content di public page.

**URL:** `/contents/{slug}`

```php
public function show($slug)
{
    $content = Content::where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();
    
    return view('contents.show', compact('content'));
}
```

---

## 5.14 Modul Laporan (Reports)

### 5.14.1 Export Orders to Excel

Admin dapat export data orders ke Excel.

**Implementation dengan Laravel Excel:**
```php
// Controller: ReportController
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

public function exportOrders(Request $request)
{
    $dateFrom = $request->get('date_from');
    $dateTo = $request->get('date_to');
    
    return Excel::download(
        new OrdersExport($dateFrom, $dateTo),
        'orders_' . date('Y-m-d') . '.xlsx'
    );
}
```

**Export Class:**
```php
// app/Exports/OrdersExport.php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $dateFrom;
    protected $dateTo;
    
    public function __construct($dateFrom = null, $dateTo = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }
    
    public function collection()
    {
        $query = Order::with('user', 'items.product');
        
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }
        
        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            'Order Number',
            'Customer Name',
            'Customer Email',
            'Total Amount',
            'Status',
            'Payment Status',
            'Date',
        ];
    }
    
    public function map($order): array
    {
        return [
            $order->order_number,
            $order->user->name,
            $order->user->email,
            $order->total_amount,
            $order->status,
            $order->payment_status,
            $order->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
```

### 5.14.2 Export Products to Excel

Similar implementation untuk export products.

### 5.14.3 Date Range Filtering

Filter reports berdasarkan date range.

### 5.14.4 Sales Reports

Generate comprehensive sales reports dengan analytics.

---

*Halaman 96-110 selesai. BAB 5 lengkap!*
