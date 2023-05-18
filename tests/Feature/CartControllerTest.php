<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test adding an item to the cart.
     *
     * @return void
     */
    public function testAddToCart()
    {
        $product = Product::factory()->create();

        $response = $this->post('/cart/add', ['id' => $product->id]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Item added to cart successfully']);

        $cart = Cart::first();
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        $this->assertNotNull($cartItem);
        $this->assertEquals(1, $cartItem->quantity);
    }

    /**
     * Test removing an item from the cart.
     *
     * @return void
     */
    public function testRemoveItem()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();
        $cartItem = CartItem::factory()->create();

        $response = $this->delete('/cart/remove', ['product_id' => $product->id]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Item removed from cart successfully']);

        $this->assertNotSoftDeleted($cartItem);
    }
}
