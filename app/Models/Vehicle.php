<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'brand',
        'model',
        'license_plate',
        'user_id'
    ];

    // Define the relationship between Vehicle and User (driver)
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
