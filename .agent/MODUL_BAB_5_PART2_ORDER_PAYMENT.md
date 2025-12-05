# BAB V - PART 2
# DOKUMENTASI FITUR DAN MODUL SISTEM (Lanjutan)

---

## 5.6 Modul Pemesanan (Orders)

### 5.6.1 Checkout Process

Checkout adalah proses krusial dimana user menyelesaikan pembelian mereka.

**URL:** `/checkout`

**Alur Checkout:**

1. **Cart Review**
   - Display semua items di cart
   - Show quantity, price per item, subtotal
   - Allow last-minute quantity changes
   - Option to remove items

2. **Shipping Information**
   - Nama penerima
   - Alamat lengkap
   - Nomor telepon
   - Catatan untuk kurir (optional)
   - Pre-fill dari user profile jika ada

3. **Order Summary**
   - Subtotal produk
   - Ongkir (jika applicable)
   - Diskon/promo (jika ada)
   - Total pembayaran

4. **Payment Method Selection**
   - Pilih metode pembayaran (Midtrans)
   - Display available payment channels
   - Payment instructions

5. **Confirmation**
   - Review semua informasi
   - Terms & conditions checkbox
   - Place Order button

**Validasi Checkout:**
- Cart tidak boleh kosong
- User harus login
- Stock availability check
- Shipping info must be complete
- Payment method must be selected

**Implementasi:**
```php
// Controller: OrderController
public function checkout()
{
    $cart = session()->get('cart', []);
    
    if (empty($cart)) {
        return redirect()->route('products.index')
            ->with('error', 'Your cart is empty');
    }
    
    // Calculate totals
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $shipping = 0; // Calculate shipping if needed
    $total = $subtotal + $shipping;
    
    return view('checkout', compact('cart', 'subtotal', 'shipping', 'total'));
}
```

### 5.6.2 Order Creation

Saat user klik "Place Order", sistem membuat order record di database.

**Order Data Structure:**
```php
// Database Schema: orders table
- id (primary key)
- user_id (foreign key to users)
- order_number (unique, auto-generated)
- total_amount (decimal)
- status (enum: pending, paid, processing, shipped, delivered, cancelled)
- payment_status (enum: unpaid, paid, failed, refunded)
- shipping_name (string)
- shipping_phone (string)
- shipping_address (text)
- notes (text, nullable)
- created_at
- updated_at

// order_items table (pivot table)
- id
- order_id (foreign key)
- product_id (foreign key)
- quantity (integer)
- price (decimal, snapshot price saat order)
- subtotal (decimal)
```

**Order Number Generation:**
```php
// Generate unique order number
// Format: ORD-YYYYMMDD-XXXX
// Example: ORD-20251129-0001

public function generateOrderNumber()
{
    $date = date('Ymd');
    $lastOrder = Order::whereDate('created_at', today())
        ->orderBy('id', 'desc')
        ->first();
    
    $sequence = $lastOrder ? (int)substr($lastOrder->order_number, -4) + 1 : 1;
    
    return 'ORD-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
}
```

**Store Order Implementation:**
```php
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
    
    // Calculate total
    $totalAmount = 0;
    foreach ($cart as $productId => $item) {
        $totalAmount += $item['price'] * $item['quantity'];
    }
    
    // Create order
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
    
    // Create order items
    foreach ($cart as $productId => $item) {
        $product = Product::find($productId);
        
        // Check stock
        if ($product->stock < $item['quantity']) {
            $order->delete();
            return back()->with('error', "Insufficient stock for {$product->name}");
        }
        
        // Create order item
        $order->items()->create([
            'product_id' => $productId,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'subtotal' => $item['price'] * $item['quantity'],
        ]);
        
        // Reduce stock
        $product->decrement('stock', $item['quantity']);
    }
    
    // Clear cart
    session()->forget('cart');
    
    // Redirect to payment
    return redirect()->route('payment.show', $order);
}
```

### 5.6.3 Order History (User)

User dapat melihat semua order mereka di halaman order history.

**URL:** `/orders`

**Fitur Order History:**
- List semua orders user
- Display: Order number, date, total, status
- Filter by status (All, Pending, Paid, Processing, Shipped, Delivered, Cancelled)
- Search by order number
- Pagination
- Quick actions: View details, Pay (if unpaid), Cancel (if pending)

**Implementasi:**
```php
public function index()
{
    $orders = Order::where('user_id', auth()->id())
        ->with('items.product')
        ->latest()
        ->paginate(10);
    
    return view('orders.index', compact('orders'));
}
```

**Order Status Badge:**
```php
// Helper function untuk display status dengan warna
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
```

### 5.6.4 Order Detail

Halaman detail order menampilkan informasi lengkap tentang satu order.

**URL:** `/orders/{order}`

**Informasi yang Ditampilkan:**

1. **Order Information**
   - Order number
   - Order date
   - Order status
   - Payment status

2. **Shipping Information**
   - Recipient name
   - Phone number
   - Shipping address
   - Notes

3. **Order Items**
   - Product image, name
   - Quantity
   - Price per item
   - Subtotal

4. **Payment Information**
   - Payment method
   - Total amount
   - Payment date (if paid)
   - Transaction ID

5. **Order Timeline**
   - Order placed
   - Payment confirmed
   - Order processing
   - Order shipped
   - Order delivered

6. **Actions**
   - Pay Now (if unpaid)
   - Cancel Order (if pending/paid)
   - Download Invoice (if paid)
   - Track Shipment (if shipped)
   - Leave Review (if delivered)

**Implementasi:**
```php
public function show(Order $order)
{
    // Authorization check
    if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
        abort(403);
    }
    
    $order->load('items.product', 'payment');
    
    return view('orders.show', compact('order'));
}
```

### 5.6.5 Order Cancellation

User dapat membatalkan order dengan kondisi tertentu.

**Kondisi Cancellation:**
- Order status: pending atau paid
- Belum diproses oleh admin
- Dalam waktu tertentu (misalnya 1 jam setelah order)

**Alur Cancellation:**
1. User klik "Cancel Order"
2. Confirmation modal muncul
3. User confirm cancellation
4. Order status diubah ke "cancelled"
5. Payment status diubah ke "refunded" (jika sudah paid)
6. Stock produk dikembalikan
7. Notification ke user dan admin

**Implementasi:**
```php
public function update(Request $request, Order $order)
{
    // Authorization
    if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
        abort(403);
    }
    
    $validated = $request->validate([
        'status' => 'required|in:cancelled',
    ]);
    
    // Check if order can be cancelled
    if (!in_array($order->status, ['pending', 'paid'])) {
        return back()->with('error', 'This order cannot be cancelled');
    }
    
    // Update order status
    $order->update([
        'status' => 'cancelled',
    ]);
    
    // Update payment status if paid
    if ($order->payment_status === 'paid') {
        $order->update(['payment_status' => 'refunded']);
    }
    
    // Restore product stock
    foreach ($order->items as $item) {
        $item->product->increment('stock', $item->quantity);
    }
    
    return back()->with('success', 'Order cancelled successfully');
}
```

### 5.6.6 Order Management (Admin)

Admin dapat mengelola semua orders dari semua users.

**URL:** `/admin/orders`

**Fitur Admin Order Management:**

1. **Order List**
   - Table view dengan columns: Order Number, Customer, Date, Total, Status, Actions
   - Search by order number atau customer name
   - Filter by status
   - Filter by date range
   - Export to Excel
   - Pagination

2. **Order Details**
   - View full order information
   - Customer information
   - Order items
   - Payment details

3. **Update Order Status**
   - Change status: pending → processing → shipped → delivered
   - Add tracking number (for shipped status)
   - Send notification to customer

4. **Order Analytics**
   - Total orders today/this week/this month
   - Total revenue
   - Average order value
   - Top selling products

**Implementasi:**
```php
// Controller: OrderController
public function adminIndex(Request $request)
{
    $query = Order::with('user', 'items');
    
    // Search
    if ($request->has('search')) {
        $query->where(function($q) use ($request) {
            $q->where('order_number', 'like', '%' . $request->search . '%')
              ->orWhereHas('user', function($q) use ($request) {
                  $q->where('name', 'like', '%' . $request->search . '%');
              });
        });
    }
    
    // Filter by status
    if ($request->has('status') && $request->status !== 'all') {
        $query->where('status', $request->status);
    }
    
    // Filter by date
    if ($request->has('date')) {
        $query->whereDate('created_at', $request->date);
    }
    
    $orders = $query->latest()->paginate(20);
    
    return view('admin.orders.index', compact('orders'));
}

public function adminShow(Order $order)
{
    $order->load('user', 'items.product', 'payment');
    return view('admin.orders.show', compact('order'));
}
```

### 5.6.7 Order Status Update

Admin dapat mengupdate status order sesuai progress pengiriman.

**Status Flow:**
```
pending → paid → processing → shipped → delivered
                    ↓
                cancelled
```

**Update Implementation:**
```php
// Admin update order status
public function update(Request $request, Order $order)
{
    if (auth()->user()->role === 'admin') {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
        ]);
        
        $order->update($validated);
        
        // Send notification to customer
        // Mail::to($order->user->email)->send(new OrderStatusUpdated($order));
        
        return back()->with('success', 'Order status updated');
    }
    
    // User can only cancel
    // ... (same as cancellation logic above)
}
```

---

## 5.7 Modul Pembayaran (Payment)

### 5.7.1 Integrasi Midtrans

Midtrans adalah payment gateway yang digunakan untuk memproses pembayaran.

**Konfigurasi Midtrans:**

```php
// config/midtrans.php
return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];

// .env
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=false
```

**Midtrans Setup:**
```php
// Set configuration
\Midtrans\Config::$serverKey = config('midtrans.server_key');
\Midtrans\Config::$isProduction = config('midtrans.is_production');
\Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
\Midtrans\Config::$is3ds = config('midtrans.is_3ds');
```

### 5.7.2 Payment Methods

Midtrans mendukung berbagai metode pembayaran:

**Available Payment Methods:**
1. **E-Wallet**
   - GoPay
   - DANA
   - ShopeePay
   - OVO
   - LinkAja

2. **Bank Transfer**
   - BCA Virtual Account
   - BNI Virtual Account
   - Mandiri Virtual Account
   - Permata Virtual Account
   - BRI Virtual Account

3. **Credit/Debit Card**
   - Visa
   - Mastercard
   - JCB
   - Amex

4. **Retail Outlet**
   - Indomaret
   - Alfamart

5. **Cardless Credit**
   - Akulaku
   - Kredivo

**Payment Method Configuration:**
```php
// Enable specific payment methods
$params = [
    'enabled_payments' => [
        'credit_card',
        'gopay',
        'dana',
        'shopeepay',
        'bca_va',
        'bni_va',
        'bri_va',
        'permata_va',
        'echannel', // Mandiri Bill
        'other_qris',
        'indomaret',
        'alfamart',
    ],
];
```

### 5.7.3 Payment Creation

Saat user memilih untuk membayar, sistem membuat Snap Token dari Midtrans.

**Payment Flow:**
1. User klik "Pay Now" di order detail
2. Sistem membuat payment record
3. Request Snap Token ke Midtrans API
4. Display Midtrans Snap popup
5. User memilih payment method dan complete payment
6. Midtrans send notification ke callback URL
7. Sistem update payment dan order status

**Implementasi:**
```php
// Controller: PaymentController
public function show(Order $order)
{
    // Authorization
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }
    
    // Check if already paid
    if ($order->payment_status === 'paid') {
        return redirect()->route('orders.show', $order)
            ->with('info', 'This order has been paid');
    }
    
    // Get or create payment
    $payment = $order->payment ?? $this->createPayment($order);
    
    return view('payment.show', compact('order', 'payment'));
}

private function createPayment(Order $order)
{
    // Set Midtrans configuration
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    
    // Prepare transaction details
    $transactionDetails = [
        'order_id' => $order->order_number,
        'gross_amount' => (int) $order->total_amount,
    ];
    
    // Prepare item details
    $itemDetails = [];
    foreach ($order->items as $item) {
        $itemDetails[] = [
            'id' => $item->product_id,
            'price' => (int) $item->price,
            'quantity' => $item->quantity,
            'name' => $item->product->name,
        ];
    }
    
    // Customer details
    $customerDetails = [
        'first_name' => $order->user->name,
        'email' => $order->user->email,
        'phone' => $order->shipping_phone,
    ];
    
    // Transaction parameters
    $params = [
        'transaction_details' => $transactionDetails,
        'item_details' => $itemDetails,
        'customer_details' => $customerDetails,
        'enabled_payments' => [
            'credit_card', 'gopay', 'dana', 'shopeepay',
            'bca_va', 'bni_va', 'bri_va', 'permata_va',
            'echannel', 'other_qris', 'indomaret', 'alfamart',
        ],
    ];
    
    try {
        // Get Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        // Create payment record
        $payment = Payment::create([
            'order_id' => $order->id,
            'snap_token' => $snapToken,
            'status' => 'pending',
        ]);
        
        return $payment;
        
    } catch (\Exception $e) {
        \Log::error('Midtrans Error: ' . $e->getMessage());
        throw $e;
    }
}
```

### 5.7.4 Payment Notification Handler

Midtrans mengirim notification ke callback URL setelah payment completed.

**Notification URL:** `/payments/notification`

**Notification Flow:**
1. Midtrans send POST request ke notification URL
2. Sistem verify notification signature
3. Get transaction status dari Midtrans
4. Update payment dan order status
5. Send confirmation email ke customer

**Implementasi:**
```php
public function notification(Request $request)
{
    // Set Midtrans configuration
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    
    try {
        // Create notification object
        $notification = new \Midtrans\Notification();
        
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;
        $orderId = $notification->order_id;
        
        // Find order
        $order = Order::where('order_number', $orderId)->firstOrFail();
        $payment = $order->payment;
        
        // Handle different transaction status
        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                // Payment success
                $this->handleSuccessPayment($order, $payment, $notification);
            }
        } elseif ($transactionStatus == 'settlement') {
            // Payment success
            $this->handleSuccessPayment($order, $payment, $notification);
        } elseif ($transactionStatus == 'pending') {
            // Payment pending
            $payment->update([
                'status' => 'pending',
                'payment_type' => $notification->payment_type,
            ]);
        } elseif ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
            // Payment failed
            $payment->update([
                'status' => 'failed',
                'payment_type' => $notification->payment_type,
            ]);
            
            $order->update([
                'payment_status' => 'failed',
            ]);
            
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }
        
        return response()->json(['status' => 'success']);
        
    } catch (\Exception $e) {
        \Log::error('Payment Notification Error: ' . $e->getMessage());
        return response()->json(['status' => 'error'], 500);
    }
}

private function handleSuccessPayment($order, $payment, $notification)
{
    $payment->update([
        'status' => 'paid',
        'payment_type' => $notification->payment_type,
        'transaction_id' => $notification->transaction_id,
        'paid_at' => now(),
    ]);
    
    $order->update([
        'payment_status' => 'paid',
        'status' => 'paid',
    ]);
    
    // Send confirmation email
    // Mail::to($order->user->email)->send(new PaymentConfirmation($order));
}
```

### 5.7.5 Payment Status Tracking

User dapat melihat status pembayaran mereka secara real-time.

**Payment Status:**
- **pending**: Menunggu pembayaran
- **paid**: Pembayaran berhasil
- **failed**: Pembayaran gagal
- **expired**: Pembayaran kadaluarsa

**Payment Information Display:**
- Payment method used
- Transaction ID
- Payment date and time
- Amount paid
- Payment proof (for manual transfer)

**Database Schema:**
```sql
CREATE TABLE payments (
    id BIGINT PRIMARY KEY,
    order_id BIGINT,
    snap_token VARCHAR(255),
    transaction_id VARCHAR(255) NULLABLE,
    payment_type VARCHAR(50) NULLABLE,
    status ENUM('pending', 'paid', 'failed', 'expired'),
    paid_at TIMESTAMP NULLABLE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);
```

---

## 5.8 Modul Dashboard Admin

### 5.8.1 Dashboard Overview

Admin dashboard memberikan overview bisnis secara real-time.

**URL:** `/admin/dashboard`

**Metrics yang Ditampilkan:**

1. **Key Performance Indicators (KPI)**
   - Total Revenue (Today, This Week, This Month, All Time)
   - Total Orders (Today, This Week, This Month)
   - Total Customers
   - Total Products
   - Average Order Value

2. **Quick Stats Cards**
   - Pending Orders (dengan badge count)
   - Low Stock Products (alert)
   - New Customers (this week)
   - Total Revenue (this month)

3. **Charts dan Graphs**
   - Revenue Chart (bar chart, daily/weekly/monthly)
   - Sales Trend (line chart)
   - Top Selling Products (horizontal bar chart)
   - Order Status Distribution (pie chart)
   - Category Performance (bar chart)

4. **Recent Activities**
   - Latest Orders (last 10)
   - Recent Customers
   - Low Stock Alerts
   - Pending Reviews

**Implementasi:**
```php
// Controller: AdminController
public function dashboard()
{
    // Today's stats
    $todayRevenue = Order::whereDate('created_at', today())
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    
    $todayOrders = Order::whereDate('created_at', today())->count();
    
    // This month's stats
    $monthRevenue = Order::whereMonth('created_at', now()->month)
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    
    $monthOrders = Order::whereMonth('created_at', now()->month)->count();
    
    // All time stats
    $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
    $totalOrders = Order::count();
    $totalCustomers = User::where('role', 'user')->count();
    $totalProducts = Product::count();
    
    // Pending orders
    $pendingOrders = Order::where('status', 'pending')->count();
    
    // Low stock products
    $lowStockProducts = Product::where('stock', '<=', 10)
        ->where('stock', '>', 0)
        ->count();
    
    // Recent orders
    $recentOrders = Order::with('user')
        ->latest()
        ->take(10)
        ->get();
    
    return view('admin.dashboard', compact(
        'todayRevenue',
        'todayOrders',
        'monthRevenue',
        'monthOrders',
        'totalRevenue',
        'totalOrders',
        'totalCustomers',
        'totalProducts',
        'pendingOrders',
        'lowStockProducts',
        'recentOrders'
    ));
}
```

### 5.8.2 Revenue Charts

Revenue chart menampilkan visualisasi pendapatan dalam bentuk grafik.

**Chart Types:**
1. **Daily Revenue Chart** (Last 7 days)
2. **Weekly Revenue Chart** (Last 4 weeks)
3. **Monthly Revenue Chart** (Last 12 months)

**Implementation dengan Chart.js:**
```javascript
// Frontend: Chart.js implementation
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [], // Dates
        datasets: [{
            label: 'Revenue',
            data: [], // Revenue amounts
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgba(59, 130, 246, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Fetch data via AJAX
fetch('/admin/dashboard-data?period=daily')
    .then(response => response.json())
    .then(data => {
        revenueChart.data.labels = data.labels;
        revenueChart.data.datasets[0].data = data.values;
        revenueChart.update();
    });
```

**Backend API untuk Chart Data:**
```php
public function getDashboardData(Request $request)
{
    $period = $request->get('period', 'daily');
    
    if ($period === 'daily') {
        // Last 7 days
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');
            
            $data['labels'][] = $date->format('d M');
            $data['values'][] = $revenue;
        }
    } elseif ($period === 'weekly') {
        // Last 4 weeks
        // Similar implementation
    } elseif ($period === 'monthly') {
        // Last 12 months
        // Similar implementation
    }
    
    return response()->json($data);
}
```

### 5.8.3 Sales Statistics

Sales statistics memberikan insight tentang performa penjualan.

**Statistics yang Ditampilkan:**

1. **Top Selling Products**
   - Product name
   - Total quantity sold
   - Total revenue
   - Percentage of total sales

2. **Sales by Category**
   - Category name
   - Number of products sold
   - Total revenue
   - Growth percentage

3. **Customer Analytics**
   - New customers this month
   - Returning customers
   - Customer lifetime value
   - Average orders per customer

4. **Order Analytics**
   - Average order value
   - Order completion rate
   - Cancellation rate
   - Payment success rate

**Implementation:**
```php
// Top selling products
$topProducts = OrderItem::select('product_id', 
        DB::raw('SUM(quantity) as total_quantity'),
        DB::raw('SUM(subtotal) as total_revenue'))
    ->groupBy('product_id')
    ->orderBy('total_quantity', 'desc')
    ->with('product')
    ->take(10)
    ->get();

// Sales by category
$categoryStats = Category::withCount('products')
    ->with(['products' => function($query) {
        $query->withSum('orderItems', 'subtotal');
    }])
    ->get();
```

### 5.8.4 Real-time Data Updates

Dashboard data di-update secara real-time menggunakan AJAX polling atau WebSockets.

**AJAX Polling Implementation:**
```javascript
// Auto-refresh dashboard every 30 seconds
setInterval(function() {
    fetch('/admin/dashboard-data')
        .then(response => response.json())
        .then(data => {
            // Update KPI cards
            document.getElementById('todayRevenue').textContent = 
                'Rp ' + data.todayRevenue.toLocaleString('id-ID');
            document.getElementById('todayOrders').textContent = data.todayOrders;
            document.getElementById('pendingOrders').textContent = data.pendingOrders;
            
            // Update charts
            revenueChart.data.datasets[0].data = data.chartData;
            revenueChart.update();
        });
}, 30000); // 30 seconds
```

---

*Halaman 86-95 selesai. Lanjut ke modul dashboard user dan fitur lainnya...*
