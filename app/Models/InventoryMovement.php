<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryMovement extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'order_id',
        'product_id',
        'client_provides_product',
        'client_provided_quantity',
        'required_quantity',
        'difference_quantity',
        'difference_type',
        'add_surplus_to_inventory',
        'notes',
    ];

    protected $casts = [
        'client_provides_product' => 'boolean',
        'add_surplus_to_inventory' => 'boolean',
        'client_provided_quantity' => 'decimal:2',
        'required_quantity' => 'decimal:2',
        'difference_quantity' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Calculate the difference and type based on provided vs required quantities
     */
    public function calculateDifference()
    {
        $difference = $this->client_provided_quantity - $this->required_quantity;

        $this->difference_quantity = abs($difference);

        if ($difference > 0) {
            $this->difference_type = 'surplus';
        } elseif ($difference < 0) {
            $this->difference_type = 'shortage';
        } else {
            $this->difference_type = 'exact';
        }
    }

    /**
     * Get formatted difference text
     */
    public function getDifferenceTextAttribute()
    {
        if ($this->difference_type === 'exact') {
            return 'Cantidad exacta';
        }

        $action = $this->difference_type === 'surplus' ? 'Sobra' : 'Falta';
        return "{$action} {$this->difference_quantity} de producto";
    }

    /**
     * Update product stock when surplus is added to inventory
     */
    public function updateProductStock()
    {
        if ($this->add_surplus_to_inventory && $this->difference_type === 'surplus') {
            $this->product->increment('stock', $this->difference_quantity);
        }
    }
}
