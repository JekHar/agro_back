<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Aircraft extends Model implements ContractsAuditable
{
    use Auditable;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'aircrafts';

    protected $fillable = [
        'merchant_id',
        'brand',
        'alias',
        'models',
        'manufacturing_year',
        'acquisition_date',
        'working_width',
    ];

    protected $dates = [
        'manufacturing_year',
        'acquisition_date',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
