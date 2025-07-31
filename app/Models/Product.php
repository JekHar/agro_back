<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'commercial_brand',
        'category_id',
        'concentration',
        'dosage_per_hectare',
        'liters_per_can',
        'application_volume_per_hectare',
        'stock',
        'merchant_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // Accessor for Inventory LITERS
    public function getInventoryLitersAttribute()
    {
        if (is_null($this->liters_per_can)) {
            // Stock is already in liters
            return $this->stock;
        }
        // Stock is in cans, convert to liters
        return $this->stock * $this->liters_per_can;
    }

    // Accessor for Inventory CANS
    public function getInventoryCansAttribute()
    {
        if (is_null($this->liters_per_can)) {
            // Stock is in liters, so no cans count
            return '-';
        }
        // Stock is already in cans
        return $this->stock;
    }
}
