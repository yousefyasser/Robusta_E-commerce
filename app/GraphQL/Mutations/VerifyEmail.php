<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;

final readonly class VerifyEmail
{
    /** @param  array<string, string>  $args */
    public function __invoke(null $_, array $args): bool
    {
        $user = User::where('remember_token', $args['token'])->first();

        if (!$user) {
            return false;
        }

        $user->email_verified_at = now()->toString();
        $user->remember_token = null;
        $user->save();

        return true;
    }
}
