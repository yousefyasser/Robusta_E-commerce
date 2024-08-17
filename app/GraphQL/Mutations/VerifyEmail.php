<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final readonly class VerifyEmail
{
    /**
     *  @param  array<string, string>  $args 
     *  @return array<string, string|User>
     */
    public function __invoke(null $_, array $args): array
    {
        /**
         * @var User $user
         *  */
        $user = Auth::user();

        if ($user->email_verified_at !== null) {
            return [
                'status' => 'error',
                'message' => 'Email already verified',
            ];
        }

        $user->email_verified_at = now()->toString();
        $user->save();

        return [
            'status' => 'success',
            'message' => 'Email verified successfully',
            'user' => $user,
        ];
    }
}
