<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

final readonly class OrderHistory
{
    /**
     *  @param  array<string>  $args 
     *  @return Builder<Order>
     */
    public function __invoke(null $_, array $args): Builder
    {
        $userId = Auth::id();
        $orderQuery = Order::query()->where('user_id', $userId);


        if (isset($args['status'])) {
            $orderQuery->where('status', $args['status']);
        }

        if (isset($args['sort'])) {
            $parts = explode('_', $args['sort']);
            $sortingOrder = array_pop($parts);
            $sortingColumn = implode('_', $parts);

            $orderQuery->orderBy($sortingColumn, $sortingOrder);
        }

        return $orderQuery;
    }
}
