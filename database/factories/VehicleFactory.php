<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand' => fake()->randomElement(['Toyota', 'Ford', 'Mercedes', 'Volvo']),
            'model' => fake()->randomElement(['Sprinter', 'Transit', 'Proace', 'FH']),
            'license_plate' => strtoupper(fake()->bothify('???-###')),
            'user_id' => User::factory()
        ];
    }
}
