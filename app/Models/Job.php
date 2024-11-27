<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The Job model represents a delivery job in the system.
 * It includes attributes like pickup address, delivery address,
 * recipient details, status, and the associated driver.
 */
class Job extends Model
{
    use HasFactory;
    
    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'pickup_address',
        'delivery_address',
        'recipient_name',
        'recipient_phone',
        'status',
        'driver_id'
    ];

    // Define the job status constants
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    
    // Define the relationship between Job and User (driver)
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    // Get the job ID
    public function getJobId(): int
    {
        return $this->id;
    }

    // Get the delivery address
    public function getDeliveryAddress(): string
    {
        return $this->delivery_address;
    }

    // Get the driver's name
    public function getDriverName(): string
    {
        return $this->driver->name;
    }
}


