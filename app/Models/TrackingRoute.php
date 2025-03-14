<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_lot_id',
        'user_id',
        'route_data',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'route_data' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function orderLot()
    {
        return $this->belongsTo(OrderLot::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}