<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * JobFactory class is responsible for generating fake data for the Job model.
 * It extends the base Factory class provided by Laravel.
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     * This method returns an array of default values for the Job model's attributes.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pickup_address' => fake()->address(), // Generates a fake pickup address
            'delivery_address' => fake()->address(), // Generates a fake delivery address
            'recipient_name' => fake()->name(), // Generates a fake recipient name
            'recipient_phone' => fake()->phoneNumber(), // Generates a fake recipient phone number
            'status' => fake()->randomElement(['assigned', 'in_progress', 'completed', 'failed']), // Randomly assigns a status
        ];
    }

    /**
     * Define a state for the Job model where the status is 'assigned'.
     *
     * @return static
     */
    public function assigned(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'assigned'
        ]);
    }

    /**
     * Define a state for the Job model where the status is 'in_progress'.
     *
     * @return static
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress'
        ]);
    }
}