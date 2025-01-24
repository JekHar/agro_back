<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class FlightProduct extends Model implements ContractsAuditable
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'flight_id', 'product_id', 'quantity'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}