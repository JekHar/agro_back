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

    const TYPE_MAIN = 'main';
    const TYPE_EXCLUDED = 'excluded';

    protected $fillable = [
        'lot_id',
        'latitude',
        'longitude',
        'sequence_number',
        'is_hole',
        'hole_group',
    ];

    protected $casts = [
        'is_hole' => 'boolean',
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function scopeMainPerimeter($query)
    {
        return $query->where('is_hole', false);
    }

    public function scopeHoles($query)
    {
        return $query->where('is_hole', true);
    }
}
