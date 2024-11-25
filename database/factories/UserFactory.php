<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    use HasFactory;

    
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
