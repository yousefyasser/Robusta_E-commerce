<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;

final readonly class CreateAddress
{
    /** @param  array{}  $args 
     *  @return int
     */
    public function __invoke(null $_, array $args): int
    {
        $args['user_id'] = Auth::id();
        $address = Address::create($args);
        return $address->id;
    }
}
