<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    use HasFactory;
    
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
    

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }


}


