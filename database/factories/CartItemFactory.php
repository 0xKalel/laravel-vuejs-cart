<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cart_id' =>  Cart::inRandomOrder()->first()->id,
            'product_id' =>  Product::inRandomOrder()->first()->id,
            'quantity' => fake()->numberBetween(1, 100),
        ];
    }
}
