<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $mainCategoriesCount = 5;
        $mainCategories = Category::factory($mainCategoriesCount)->create();

        for ($i = 0; $i < $mainCategoriesCount; $i++) {
            Category::factory(5)->create([
                'parent_id' => fake()->numberBetween($mainCategories[0]->id, $mainCategories[$mainCategoriesCount - 1]->id),
            ]);
        }
    }
}
