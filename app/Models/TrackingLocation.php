<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'latitude',
        'longitude',
        'timestamp',
        'speed',
        'heading',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'speed' => 'float',
        'heading' => 'float',
    ];
}