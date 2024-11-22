<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Create an Admin with Factory
        User::factory()->admin()->create();
        

        //Create 5 drivers
        User::factory()->count(5)->driver()->create();

    }
}
