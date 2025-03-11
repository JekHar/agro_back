<?php

namespace App\Models;

use App\Enums\OrderLotStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class OrderLot extends Model implements ContractsAuditable
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'lot_id',
        'hectares',
        'status'
    ];

    protected $casts = [
        'status' => OrderLotStatus::class,
        'hectares' => 'decimal:2'
    ];

    /**
     * Get the order that owns the order lot
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the lot associated with this order lot
     */
    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}