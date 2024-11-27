<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Job;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DeliverySystemTest  extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $admin;
    private $driver;
    private $jobData;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com'
        ]);

        $this->driver = User::factory()->create([
            'role' => 'driver',
            'email' => 'driver@test.com'
        ]);

        // Prepare test job data
        $this->jobData = [
            'pickup_address' => $this->faker->address,
            'delivery_address' => $this->faker->address,
            'recipient_name' => $this->faker->name,
            'recipient_phone' => $this->faker->phoneNumber,
            'driver_id' => $this->driver->id
        ];
    }

    public function test_admin_can_create_job()
    {
        $response = $this->actingAs($this->admin)
            ->postJson('/api/jobs', $this->jobData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'job' => [
                    'id',
                    'pickup_address',
                    'delivery_address',
                    'recipient_name',
                    'recipient_phone',
                    'driver_id',
                    'status'
                ]
            ]);

        $this->assertDatabaseHas('jobs', [
            'pickup_address' => $this->jobData['pickup_address']
        ]);
    }


    public function test_driver_can_update_job_status()
    {
        // Create a job first
        $job = Job::factory()->create([
            'driver_id' => $this->driver->id,
            'status' => 'assigned'
        ]);

        $response = $this->actingAs($this->driver)
            ->putJson("/api/jobs/{$job->id}", [
                'status' => 'in_progress'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Job status updated'
            ]);

        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'status' => 'in_progress'
        ]);
    }

    public function test_driver_cannot_update_other_drivers_job()
    {
        $otherDriver = User::factory()->create(['role' => 'driver']);
        $job = Job::factory()->create([
            'driver_id' => $otherDriver->id
        ]);

        $response = $this->actingAs($this->driver)
            ->putJson("/api/jobs/{$job->id}", [
                'status' => 'in_progress'
            ]);

        $response->assertStatus(403);
    }

    public function test_user_registration()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'role']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'role' => 'driver' 
        ]);
    }

    public function test_user_login()
    {
        $password = 'testpassword123';
        $user = User::factory()->create([
            'password' => bcrypt($password)
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token'
            ]);
    }
}
