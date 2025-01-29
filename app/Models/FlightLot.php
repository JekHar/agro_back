<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class FlightLot extends Model implements ContractsAuditable
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'flight_id', 'lot_id', 'lot_total_hectares', 'hectares_to_apply'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}