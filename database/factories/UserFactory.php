<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * UserFactory class for generating User model instances with predefined attributes.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use HasFactory;

    /**
     * Define the model's default state.
     * 
     * @return array
     */
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

    /**
     * Define the state for an admin user.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin(): Factory
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    /**
     * Define the state for a driver user.
     * 
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function driver(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'role' => 'driver',
            ];
        });
    }

    /**
     * Retrieve a list of drivers if the authenticated user is an admin.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function drivers()
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $drivers = User::where('role', 'driver')
            ->select('id', 'name')
            ->get();

        return response()->json([
            'drivers' => $drivers
        ]);
    }
}
