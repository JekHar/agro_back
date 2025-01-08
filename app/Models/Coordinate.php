<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Coordinate extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = [
        'lot_id',
        'latitude',
        'longitude'
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}
