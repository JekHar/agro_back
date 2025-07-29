<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Order extends Model implements ContractsAuditable
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'client_id',
        'tenant_id',
        'service_id',
        'aircraft_id',
        'pilot_id',
        'ground_support_id',
        'total_hectares',
        'total_amount',
        'status',
        'scheduled_date',
        'completed_at',
        'observations'
    ];

    protected $dates = ['scheduled_date', 'completed_at'];

    public function client()
    {
        return $this->belongsTo(Merchant::class, 'client_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Merchant::class, 'tenant_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function aircraft()
    {
        return $this->belongsTo(Aircraft::class);
    }

    public function pilot()
    {
        return $this->belongsTo(User::class, 'pilot_id');
    }

    public function groundSupport()
    {
        return $this->belongsTo(User::class, 'ground_support_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderLots()
    {
        return $this->hasMany(OrderLot::class);
    }

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
