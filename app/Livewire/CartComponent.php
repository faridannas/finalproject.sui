<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartComponent extends Component
{
    public $cartItems;
    public $total = 0;
    public $quantities = [];

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            $this->calculateTotal();
        }
    }

    public function addToCart($productId, $quantity = 1)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $product = Product::find($productId);
        if (!$product || $product->stock < $quantity) {
            session()->flash('error', 'Product not available or insufficient stock.');
            return;
        }

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                session()->flash('error', 'Insufficient stock.');
                return;
            }
            $cartItem->update([
                'quantity' => $newQuantity,
                'total_price' => $newQuantity * $product->price
            ]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $quantity * $product->price
            ]);
        }

        $this->loadCart();
        session()->flash('success', 'Product added to cart.');
    }

    public function buyNow($productId, $quantity = 1)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to continue.');
            return $this->redirect(route('login'));
        }

        $product = Product::find($productId);
        if (!$product || $product->stock < $quantity) {
            session()->flash('error', 'Product not available or insufficient stock.');
            return $this->redirect(request()->header('Referer') ?: route('products.index'));
        }

        // Clear existing cart
        Cart::where('user_id', Auth::id())->delete();

        // Add product to cart
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price,
            'created_at' => now()
        ]);

        // Redirect to checkout
        return $this->redirect(route('checkout'));
    }

    public function updateQuantity($cartId, $quantity)
    {
        $cartItem = Cart::find($cartId);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $product = $cartItem->product;
            if ($quantity > $product->stock) {
                session()->flash('error', 'Insufficient stock.');
                return;
            }
            if ($quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update([
                    'quantity' => $quantity,
                    'total_price' => $quantity * $product->price
                ]);
            }
            $this->loadCart();
        }
    }

    public function removeFromCart($cartId)
    {
        $cartItem = Cart::find($cartId);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->delete();
            $this->loadCart();
            session()->flash('success', 'Product removed from cart.');
        }
    }

    public function calculateTotal()
    {
        $this->total = $this->cartItems->sum('total_price');
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}
