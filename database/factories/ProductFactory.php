<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subcategories = Category::all()->whereNotNull('parent_id');

        if ($subcategories->isEmpty()) {
            $mainCategory = Category::factory()->create();
            $subcategories = Category::factory(3)->create([
                'parent_id' => $mainCategory->id
            ]);
        }

        return [
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 1, 100),
            'category_id' => $subcategories->random()->id,
            'stock' => fake()->randomNumber(2, true),
            'image_url' => fake()->imageUrl()
        ];
    }
}
