<?php

declare(strict_types=1);

namespace App\GraphQL\Types\OrderItem;

use App\Models\OrderItem;

final readonly class Total
{
    /** @param OrderItem $args */
    public function __invoke(OrderItem $args): float
    {
        return $args['price'] * $args['quantity'];
    }
}
