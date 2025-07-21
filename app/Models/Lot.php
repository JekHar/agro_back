<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'number',
        'hectares',
        'navigation_latitude',
        'navigation_longitude',
        
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
