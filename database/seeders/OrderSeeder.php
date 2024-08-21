<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @param \Illuminate\Support\Collection<int, \App\Models\User> $users
     */
    public function run($users): void
    {
        $users->each(function ($user) {
            Order::factory(3)
                ->for($user)
                ->for($user->payment_methods->random(), 'payment_method')
                ->for($user->addresses->random())
                ->has(OrderItem::factory(5), 'items')
                ->create();
        });
    }
}
