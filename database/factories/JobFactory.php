<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pickup_address' => fake()->address(),
            'delivery_address' => fake()->address(),
            'recipient_name' => fake()->name(),
            'recipient_phone' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['assigned', 'in_progress', 'completed', 'failed']),
            'driver_id' => UserFactory::factory()->create(['role' => 'driver'])->id
        ];
    }

    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'assigned'
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress'
        ]);
    }

}
