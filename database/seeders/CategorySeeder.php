<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
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
    }
}
