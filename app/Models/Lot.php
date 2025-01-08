<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Lot extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'merchant_id',
        'number',
        'hectares',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function coordinates()
    {
        return $this->hasMany(Coordinate::class);
    }
}
