<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminindex()
    {
        $orders = Order::where('user_id', Auth::id())->with('orderItems.product')->paginate(10);
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
            'promo_code' => 'nullable|string|exists:promos,code',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
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
                return redirect()->back()->with('error', 'Invalid or expired promo code.');
            }
        }

        DB::transaction(function () use ($request, $cartItems, $total, $discount, $promo) {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'address' => $request->address,
                'status' => 'pending',
                'promo_code' => $promo ? $promo->code : null,
                'discount' => $discount,
            ]);

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

            // Create payment with Midtrans
            try {
                $midtrans = new \App\Services\MidtransService();
                $snapToken = $midtrans->createTransaction($order);

                Payment::create([
                    'user_id' => Auth::id(),
                    'order_id' => $order->id,
                    'payment_method' => 'midtrans',
                    'payment_status' => 'pending',
                    'amount' => $total,
                    'snap_token' => $snapToken,
                ]);
            } catch (\Exception $e) {
                Log::error('Midtrans Error: ' . $e->getMessage());
                throw $e;
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            // Send notifications
            app(NotificationService::class)->notifyNewOrder($order);
        });

        return redirect()->route('orders.index')->with('success', 'Order placed successfully. Please complete payment.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
