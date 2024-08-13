<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;

final readonly class Products
{
    /** 
     * @param  array<mixed>  $args 
     * @return Builder
     */
    public function resolve(null $_, array $args): Builder
    {
        $query = Product::query();

        if (isset($args['category'])) {
            $query->where('category_id', $args['category']);
        }

        if (isset($args['search'])) {
            $query->where('name', 'like', "%{$args['search']}%");
        }

        [$sortingColumn, $sortingOrder] = $args['sortBy'];

        $query->orderBy($sortingColumn, $sortingOrder);

        return $query;
    }
}
