<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'session_id' => fake()->uuid,
            'ip_address' => fake()->ipv4,
            'user_agent' => fake()->userAgent,
            'last_activity' => fake()->dateTimeBetween('-1 year', 'now'),
            'expires_at' => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
