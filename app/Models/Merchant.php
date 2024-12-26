<?php

namespace App\Models;

use App\Types\MerchantType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Merchant extends Model implements ContractsAuditable
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    protected $fillable = [
        'business_name',
        'trade_name',
        'fiscal_number',
        'main_activity',
        'email',
        'phone',
        'merchant_type',
        'disabled_at',
        'merchant_id',
        'locality',
        'address'
    ];

    protected $casts = [
        'merchant_type' => MerchantType::class,

    ];

    public function parentMerchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function childMerchants()
    {
        return $this->hasMany(Merchant::class, 'merchant_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function aircraft()
    {
        return $this->hasMany(Aircraft::class);
    }

    public function plots()
    {
        return $this->hasMany(Lot::class);
    }
}
