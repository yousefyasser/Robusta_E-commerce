<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShoppingCart>
 */
class ShoppingCartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty()) {
            $users[] = User::factory()->create();
        }

        if ($products->isEmpty()) {
            $products[] = Product::factory()->create();
        }

        $product = $products->random();

        return [
            'user_id' => $users->random()->id,
            'product_id' => $product->id,
            'quantity' => $this->faker->numberBetween(1, $product->stock),
        ];
    }
}
