<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class RemovedItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' =>  User::inRandomOrder()->first()->id,
            'session_id' =>  fake()->uuid,
            'product_id' =>  Product::inRandomOrder()->first()->id,
            'ip_address' => fake()->ipv4,
            'user_agent' => fake()->userAgent,
            'last_activity' => fake()->dateTimeBetween('-1 year', 'now'),
            'quantity' => fake()->numberBetween(1, 10),
            'removed_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
