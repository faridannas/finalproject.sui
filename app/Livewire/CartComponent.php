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
            session()->flash('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
            return;
        }

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                session()->flash('error', 'Stok tidak mencukupi.');
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
        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function buyNow($productId, $quantity = 1)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Silakan login untuk melanjutkan.');
            return $this->redirect(route('login'));
        }

        $product = Product::find($productId);
        if (!$product || $product->stock < $quantity) {
            session()->flash('error', 'Produk tidak tersedia atau stok tidak mencukupi.');
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
                session()->flash('error', 'Stok tidak mencukupi.');
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
            session()->flash('success', 'Produk berhasil dihapus dari keranjang.');
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
