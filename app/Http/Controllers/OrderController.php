<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('orderItems.product')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function adminindex(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Search by Order ID or User Name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by Date (Single Date)
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This will be the checkout page
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        $total = $cartItems->sum('total_price');
        $promo = null;

        // Check if promo code is provided in session or request
        if (request('promo_code')) {
            $promo = \App\Models\Promo::where('code', request('promo_code'))
                ->where('valid_until', '>=', now())
                ->first();
        }

        return view('orders.create', compact('cartItems', 'total', 'promo'));
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }
        $total = $cartItems->sum('total_price');
        $promo = null;

        // Check if promo code is provided in session or request
        if (request('promo_code')) {
            $promo = \App\Models\Promo::where('code', request('promo_code'))
                ->where('valid_until', '>=', now())
                ->first();
        }

        return view('checkout', compact('cartItems', 'total', 'promo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:500',
            'payment_method' => 'required|string|in:bank_transfer,credit_card,e_wallet,cod',
            'promo_code' => 'nullable|string|exists:promos,code',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong.');
        }

        $total = $cartItems->sum('total_price');
        $discount = 0;
        $promo = null;

        // Apply promo code if provided
        if ($request->promo_code) {
            $promo = \App\Models\Promo::where('code', $request->promo_code)
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo) {
                $discount = ($total * $promo->discount) / 100;
                $total -= $discount;
            } else {
                return redirect()->back()->with('error', 'Kode promo tidak valid atau sudah kadaluarsa.');
            }
        }

        try {
            $orderId = null;

            DB::transaction(function () use ($request, $cartItems, $total, $discount, $promo, &$orderId) {
                // Create order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $total,
                    'address' => $request->address,
                    'status' => 'pending',
                    'promo_code' => $promo ? $promo->code : null,
                    'discount' => $discount,
                ]);

                $orderId = $order->id;

                // Create order items
                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->total_price,
                    ]);

                    // Decrease stock
                    $item->product->decrement('stock', $item->quantity);
                }

                // Create payment record
                $paymentMethod = $request->payment_method;

                // Manual Payment Logic (No Midtrans)
                // We just create the payment record and redirect user to upload proof page
                
                Payment::create([
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'payment_method' => $paymentMethod,
                    'payment_status' => 'pending',
                    'amount' => $total,
                ]);

                // Clear cart
                Cart::where('user_id', Auth::id())->delete();

                // Broadcast event to admin for real-time notification
                broadcast(new OrderCreated($order))->toOthers();

                // Send notifications
                try {
                    app(NotificationService::class)->notifyNewOrder($order);
                } catch (\Exception $e) {
                    Log::error('Notification Error: ' . $e->getMessage());
                    // Continue even if notification fails
                }
            });

            // Redirect based on payment method
            if ($request->payment_method === 'cod') {
                return redirect()->route('orders.show', $orderId)
                    ->with('success', 'Pesanan berhasil dibuat! Silakan siapkan uang cash saat pengiriman.');
            } else {
                // Redirect to payment page for Midtrans
                return redirect()->route('payment.show', $orderId);
            }
        } catch (\Exception $e) {
            Log::error('Order Creation Error: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::where('user_id', Auth::id())->with('orderItems.product', 'payment')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    /**
     * Display the specified resource for admin.
     */
    public function adminShow(string $id)
    {
        $order = Order::with(['user', 'orderItems.product', 'payment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Eager load relationships to reduce queries
        $order = Order::with(['orderItems.product', 'payment'])->findOrFail($id);
        
        // Admin can update order status
        if (Auth::user()->isAdmin()) {
            $request->validate([
                'status' => 'required|string|in:pending,paid,processing,shipped,completed,cancelled'
            ]);
            
            $order->update([
                'status' => $request->status
            ]);
            
            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        }
        
        // User can only cancel their own order
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // User can only cancel pending or paid orders
        if (!in_array($order->status, ['pending', 'paid'])) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan. Status saat ini: ' . ucfirst($order->status));
        }
        
        // Use database transaction for atomicity and speed
        DB::beginTransaction();
        try {
            // Update order status to cancelled
            $order->update(['status' => 'cancelled']);
            
            // Restore product stock efficiently using raw query
            foreach ($order->orderItems as $item) {
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }
            
            // Update payment status if exists
            if ($order->payment) {
                $order->payment->update(['payment_status' => 'cancelled']);
            }
            
            DB::commit();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::with(['orderItems', 'payment'])->findOrFail($id);
        
        // Only allow user to delete their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Only allow deletion of cancelled or done orders
        if (!in_array($order->status, ['cancelled', 'done'])) {
            return redirect()->back()->with('error', 'Hanya pesanan yang dibatalkan atau selesai yang bisa dihapus.');
        }
        
        try {
            // Delete order (will cascade delete order items and payment due to foreign key constraints)
            $order->delete();
            
            return redirect()->route('orders.index')->with('success', 'Riwayat pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pesanan.');
        }
    }
}
