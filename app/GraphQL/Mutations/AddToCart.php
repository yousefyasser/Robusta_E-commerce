<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use Illuminate\Support\Facades\Auth;
use App\Models\Product;

final readonly class AddToCart
{
    /**
     *  @param  array<int>  $args 
     *  @return Product|null
     */
    public function __invoke(null $_, array $args): Product|null
    {
        /** @var Product $product */
        $product = Product::find($args['product_id']);
        if ($product->stock < $args['quantity']) {
            return null;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->cart()->syncWithoutDetaching([
            $args['product_id'] => ['quantity' => $args['quantity']]
        ]);

        return $product;
    }
}
