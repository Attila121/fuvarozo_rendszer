<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * The User model represents a user in the system.
 * It includes attributes like name, email, password, and role.
 * It also defines relationships with vehicles and jobs.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    // Define the attributes that should be hidden for serialization
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define the attributes that should be cast
    protected $casts = [
        'password' => 'hashed',
    ];

    // Define the relationship between User and Vehicle
    public function vehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class);
    }

    // Define the relationship between User and Job
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'driver_id');
    }

    // Check if the user is an admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Check if the user is a driver
    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }
}
