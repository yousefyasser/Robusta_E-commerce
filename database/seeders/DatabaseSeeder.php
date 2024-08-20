<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\PaymentMethod;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([CategorySeeder::class]);

        $users = User::factory(5)
            ->hasAddresses(3)
            ->has(PaymentMethod::factory(2), 'payment_methods')
            ->hasAttached(Product::factory(5), ['quantity' => 1], 'cart')
            ->create();

        $this->callWith(OrderSeeder::class, ['users' => $users]);
    }
}
