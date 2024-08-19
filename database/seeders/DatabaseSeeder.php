<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\ShoppingCart;
use App\Models\Product;
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
        User::factory(10)->create()->each(function (User $user) {
            $user->addresses()->save(Address::factory()->make());
            $user->payment_methods()->save(PaymentMethod::factory()->make());
        });
    }
}
