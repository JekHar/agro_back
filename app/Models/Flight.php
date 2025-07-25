<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Flight extends Model implements ContractsAuditable
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'flight_number',
        'total_hectares', 
        'status',
        'started_at',
        'completed_at', 
        'observations',
        'weather_conditions'
    ];

    protected $casts = [
        'weather_conditions' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function flightProducts()
    {
        return $this->hasMany(FlightProduct::class);
    }

    public function flightLots()
    {
        return $this->hasMany(FlightLot::class);
    }
}