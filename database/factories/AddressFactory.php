<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word,
            'recipient_name' => fake()->name,
            'address_line_1' => fake()->streetAddress,
            // @phpstan-ignore-next-line
            'address_line_2' => fake()->secondaryAddress,
            'city' => fake()->city,
            // @phpstan-ignore-next-line
            'state' => fake()->state,
            'postal_code' => fake()->postcode,
            'country' => fake()->country,
            'phone_number' => fake()->phoneNumber,
            'user_id' => User::factory(),
        ];
    }
}
