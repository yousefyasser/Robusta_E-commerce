<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'address_id' => Address::factory(),
            'payment_method_id' => 1,
            'status' => fake()->randomElement(['pending', 'completed', 'shipped']),
            'total' => fake()->randomFloat(2, 10, 1000),
        ];
    }
}
