<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Service extends Model implements ContractsAuditable
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'merchant_id',
        'name',
        'description',
        'price_per_hectare',
        'status'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
