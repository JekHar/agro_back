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
        'client_provided',
        'quantity',
        'required_quantity',
        'add_surplus_to_inventory',
        'merchant_id',
        'notes',
    ];

    protected $casts = [
        'client_provided' => 'boolean',
        'add_surplus_to_inventory' => 'boolean',
        'quantity' => 'decimal:2',
        'required_quantity' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Calculate if there's a surplus (quantity > required_quantity)
     */
    public function hasSurplus(): bool
    {
        return $this->quantity > $this->required_quantity;
    }

    /**
     * Get the surplus amount
     */
    public function getSurplusQuantity(): float
    {
        return max(0, $this->quantity - $this->required_quantity);
    }

    /**
     * Calculate if there's a shortage (quantity < required_quantity)
     */
    public function hasShortage(): bool
    {
        return $this->quantity < $this->required_quantity;
    }

    /**
     * Get the shortage amount
     */
    public function getShortageQuantity(): float
    {
        return max(0, $this->required_quantity - $this->quantity);
    }

    /**
     * Update product stock when surplus is added to inventory
     */
    public function updateProductStock(): void
    {
        if ($this->add_surplus_to_inventory && $this->hasSurplus()) {
            $this->product->increment('stock', $this->getSurplusQuantity());
        }
    }

    /**
     * Scope to get client provided inventory movements
     */
    public function scopeClientProvided($query)
    {
        return $query->where('client_provided', true);
    }

    /**
     * Scope to get company provided inventory movements
     */
    public function scopeCompanyProvided($query)
    {
        return $query->where('client_provided', false);
    }
}
