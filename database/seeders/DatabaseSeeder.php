<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ShoppingCart;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $mainCategoriesCount = 5;
        $mainCategoriesIds = Category::factory($mainCategoriesCount)->create()->pluck('id')->toArray();

        for ($i = 0; $i < 5 * $mainCategoriesCount; $i++) {
            Category::factory()->create([
                'parent_id' => fake()->randomElement($mainCategoriesIds),
            ]);
        }

        ShoppingCart::factory(10)->create();
        Address::factory(10)->create();
    }
}
