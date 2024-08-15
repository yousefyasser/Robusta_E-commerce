<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class RegisterUser
{
    /** 
     * @param  array{}  $args 
     * @return User
     * */
    public function __invoke(null $_, array $args): User
    {
        // send a verification email
        return User::create($args);
    }
}
