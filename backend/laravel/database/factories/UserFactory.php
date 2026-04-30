<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'trust_tier' => 'guest',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
