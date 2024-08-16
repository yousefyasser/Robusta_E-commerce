<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerified;
use Illuminate\Support\Str;

final readonly class RegisterUser
{
    /** 
     * @param  array{}  $args 
     * @return User
     * */
    public function __invoke(null $_, array $args): User
    {
        $token = Str::random(60);

        $user = User::create($args);
        $user->remember_token = $token;
        $user->save();

        $verificationUrl = url("/api/verify-email?token={$token}");

        Mail::to($user->email)->queue(new EmailVerified($verificationUrl));

        return $user;
    }
}
