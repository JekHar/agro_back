<?php

namespace App\Models;

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
        'order_id', 'lot_id', 'hectares'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}