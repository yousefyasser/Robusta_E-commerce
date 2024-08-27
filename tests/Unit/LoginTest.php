<?php

namespace Tests\Unit;

use Illuminate\Support\Arr;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use MakesGraphQLRequests;

    public function get_register_mutation_response($user): \Illuminate\Testing\TestResponse
    {
        return $this->graphQL('
            mutation ($input: CreateUserInput!) {
                registerUser(input: $input)
            }
        ', [
            'input' => $user
        ]);
    }

    public function test_logs_in_validation()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422);

        $response = $this->postJson('/api/login', [
            'email' => 'invalidEmail',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }

    public function test_logs_in_fail()
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    public function test_logs_in_successfully()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_register_validation()
    {
        $user = [
            'name' => '',
            'email' => 'invalidEmail',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->get_register_mutation_response($user);

        $response->assertJson([
            'errors' => [
                [
                    'extensions' => [
                        'validation' => [
                            'input.name' => ['The input.name field is required.'],
                            'input.email' => ['Email must be in a valid format: test@example.com'],
                            'input.password' => ['Password must contain at least one uppercase letter and one number.'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_register_successfully()
    {
        $user = [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
        ];

        $this->get_register_mutation_response($user);

        $userData = User::all()->where('email', $user['email']);

        $this->assertNotEmpty($userData);
    }

    public function test_create_address_validation()
    {
        $this->login('user');

        $response = $this->graphQL('
            mutation ($addressData: CreateAddressInput!) {
                createAddress(addressData: $addressData)
            }
        ', [
            'addressData' => [
                'label' => '',
            ]
        ]);

        $response->assertJson([
            'errors' => [
                [
                    'extensions' => [
                        'validation' => [
                            'addressData.label' => ['The label field is required.'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function test_create_address_successfully()
    {
        $this->login('user');

        $addressData = Address::factory()->create()->attributesToArray();

        $response = $this->graphQL('
            mutation ($addressData: CreateAddressInput!) {
                createAddress(addressData: $addressData) 
            }
        ', [
            'addressData' => Arr::except($addressData, ['user_id', 'id'])
        ])->json('data.createAddress');

        $this->assertequals($response, $addressData['id'] + 1);
    }
}
