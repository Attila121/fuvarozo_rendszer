<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => 'driver',
            'remember_token' => Str::random(10),
        ];
    }
    
    public function admin(): Factory
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    public function driver(): Factory
    {
        return $this->state([
            'role' => 'driver',
        ]);
    }
}
