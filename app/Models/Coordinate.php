<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coordinate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'lot_id',
        'latitude',
        'longitude',
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }
}
