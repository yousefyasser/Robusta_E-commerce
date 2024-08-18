<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Exceptions\InvalidOrderException;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShoppingCart;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

final readonly class Checkout
{
    /** 
     * @param  array<int>  $args 
     * @return int
     */
    public function __invoke(null $_, array $args): int
    {
        /** @var User $user */
        $user = Auth::user();

        // TODO: extract email verification to a separate guard
        if (!$user->email_verified_at) {
            throw new InvalidOrderException('Email not verified');
        }

        $total = 0;
        $cart = $user->cart;
        foreach ($cart as $cartItem) {
            if ($cartItem->pivot->quantity > $cartItem->stock) {
                throw new InvalidOrderException("{$cartItem->name} is out of stock");
            }
            $total += $cartItem->price * $cartItem->pivot->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $args['address_id'],
            'payment_method_id' => $args['payment_method_id'],
            'status' => 'pending',
            'total' => $total,
        ]);

        foreach ($cart as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->id,
                'quantity' => $cartItem->pivot->quantity,
                'price' => $cartItem->price,
            ]);
            $cartItem->stock -= $cartItem->pivot->quantity;
            $cartItem->save();
        }

        Mail::to($user->email)->queue(new OrderPlaced($order));

        return $order->id;
    }
}
