<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;

final readonly class AddToCart
{
    /**
     *  @param  array<mixed>  $args 
     *  @return array<string>
     */
    public function __invoke(null $_, array $args): array
    {
        /** @var Product $product */
        $product = Product::find($args['product_id']);
        if ($product->stock < $args['quantity']) {
            return [
                'status' => 'error',
                'message' => 'Not enough stock'
            ];
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->cart()->syncWithoutDetaching([
            $args['product_id'] => ['quantity' => $args['quantity']]
        ]);

        // $user->cart()->attach($args['product_id'], ['quantity' => $args['quantity']]);

        return [
            'status' => 'success',
            'message' => 'Product added to cart'
        ];
    }
}
