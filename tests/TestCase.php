<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function login($role = 'user')
    {
        $user = User::factory()->create([
            'role' => $role,
        ]);

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
    }
}
