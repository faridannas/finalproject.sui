<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Check if product already in cart
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            if ($newQuantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }

            $cartItem->update([
                'quantity' => $newQuantity,
                'total_price' => $newQuantity * $product->price
            ]);
        } else {
            // Create new cart item
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * $product->price
            ]);
        }

        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Buy now - clear cart, add product, redirect to checkout
     */
    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Clear existing cart
        Cart::where('user_id', Auth::id())->delete();

        // Add product to cart
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $request->quantity * $product->price,
            'created_at' => now()
        ]);

        // Redirect to checkout
        return redirect()->route('checkout')->with('success', 'Produk ditambahkan! Silakan lanjutkan checkout.');
    }
    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $request->cart_id)
            ->firstOrFail();

        $product = $cartItem->product;

        // Check stock
        if ($product->stock < $request->quantity) {
            return response()->json(['success' => false, 'message' => 'Stok tidak mencukupi. Sisa stok: ' . $product->stock], 400);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
            'total_price' => $request->quantity * $product->price
        ]);

        // Recalculate totals
        $cartItems = Cart::where('user_id', Auth::id())->get();
        $subtotal = $cartItems->sum('total_price');
        $discount = 0;
        $grandTotal = $subtotal;

        // Calculate discount if promo code exists
        if ($request->promo_code) {
            $promo = \App\Models\Promo::where('code', $request->promo_code)
                ->where('valid_until', '>=', now())
                ->first();
            
            if ($promo) {
                $discount = ($subtotal * $promo->discount) / 100;
                $grandTotal = $subtotal - $discount;
            }
        }

        return response()->json([
            'success' => true, 
            'message' => 'Keranjang berhasil diperbarui',
            'item_total' => $cartItem->total_price,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'grand_total' => $grandTotal
        ]);
    }
}
