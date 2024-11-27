<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Job;
use App\Models\Vehicle;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an Admin
        User::factory()->admin()->create([
            'email' => 'admin@delivery.com',
            'password' => bcrypt('password')
        ]);
        
        // Create a driver
        User::factory()->create([
            'name' => 'Sam',
            'email' => 'sam@mail.com',
            'password' => bcrypt('sam12345'),
            'role' => 'driver'
        ]);

        // Create 5 drivers
        $drivers = User::factory()
            ->count(5)
            ->driver()
            ->create();

        // Create one vehicle for each driver
        foreach ($drivers as $driver) {
            Vehicle::factory()->create([
                'user_id' => $driver->id
            ]);
        }

        // Create jobs and assign them to random drivers
        foreach (range(1, 10) as $index) {
            Job::factory()->create([
                'driver_id' => $drivers->random()->id
            ]);
        }
    }
}
