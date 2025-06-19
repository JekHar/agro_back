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
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sequence_number' => 'integer',
        'hole_group' => 'integer',
    ];

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function scopeMainPerimeter($query)
    {
        return $query->where('is_hole', false)->orderBy('sequence_number');
    }

    public function scopeHoles($query)
    {
        return $query->where('is_hole', true)->orderBy('hole_group')->orderBy('sequence_number');
    }

        public function scopeHoleGroups($query)
    {
        return $query->where('is_hole', true)
                    ->selectRaw('hole_group, COUNT(*) as coordinate_count')
                    ->groupBy('hole_group')
                    ->orderBy('hole_group');
    }
}
