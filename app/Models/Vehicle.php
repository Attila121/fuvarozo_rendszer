<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;


    protected $fillable = [
        'brand',
        'model',
        'license_plate',
        'user_id'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
