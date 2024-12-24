<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Aircraft extends Model  implements ContractsAuditable
{
    use SoftDeletes;
    use Auditable;

    protected $fillable = [
        'merchant_id',
        'brand',
        'model',
        'manufacturing_year',
        'acquisition_date',
        'working_width'
    ];

    protected $dates = [
        'manufacturing_year',
        'acquisition_date'
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
