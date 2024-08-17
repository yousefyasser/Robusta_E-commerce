<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerified;
use Illuminate\Support\Facades\Auth;

final readonly class RegisterUser
{
    /** 
     * @param  array{}  $args 
     * @return array<string, string|User>
     * */
    public function __invoke(null $_, array $args): array
    {
        $user = User::create($args);

        /** @var string $token */
        $token = Auth::login($user);
        $verificationUrl = url("/api/verify-email?token={$token}");

        Mail::to($user->email)->queue(new EmailVerified($verificationUrl));

        return [
            'status' => 'success',
            'message' => 'User created successfully, please verify your email',
            'user' => $user,
            'token' => $token,
        ];
    }
}
