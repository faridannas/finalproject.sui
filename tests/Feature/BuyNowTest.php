<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class BuyNowTest extends TestCase
{
    use RefreshDatabase;

    public function test_buy_now_button_exists_on_product_page()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)
            ->get(route('products.show', $product->id));

        $response->assertStatus(200);
        $response->assertSee('Buy Now');
    }

    public function test_buy_now_clears_existing_cart_and_adds_product()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product1 = Product::factory()->create(['stock' => 10, 'price' => 10000]);
        $product2 = Product::factory()->create(['stock' => 5, 'price' => 20000]);

        // Add product1 to cart first
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
            'quantity' => 1,
            'total_price' => $product1->price,
            'created_at' => now()
        ]);

        // Verify product1 is in cart
        $this->assertEquals(1, Cart::where('user_id', $user->id)->count());

        // Call buyNow method via Livewire
        $this->actingAs($user);
        Livewire::test(\App\Livewire\CartComponent::class)
            ->call('buyNow', $product2->id, 1);

        // Verify cart is cleared and only product2 is in cart
        $cartItems = Cart::where('user_id', $user->id)->get();
        $this->assertEquals(1, $cartItems->count());
        $this->assertEquals($product2->id, $cartItems->first()->product_id);
        $this->assertEquals($product2->price, $cartItems->first()->total_price);
    }

    public function test_buy_now_redirects_to_checkout_page()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($user);
        Livewire::test(\App\Livewire\CartComponent::class)
            ->call('buyNow', $product->id, 1)
            ->assertRedirect(route('checkout'));
    }

    public function test_buy_now_fails_for_out_of_stock_product()
    {
        $user = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 0]);

        $this->actingAs($user);
        Livewire::test(\App\Livewire\CartComponent::class)
            ->call('buyNow', $product->id, 1)
            ->assertRedirect()
            ->assertSessionHas('error', 'Product not available or insufficient stock.');
    }

    public function test_buy_now_requires_authentication()
    {
        $product = Product::factory()->create(['stock' => 10]);

        Livewire::test(\App\Livewire\CartComponent::class)
            ->call('buyNow', $product->id, 1)
            ->assertRedirect(route('login'));
    }
}
