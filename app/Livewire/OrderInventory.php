<?php

namespace App\Livewire;

use App\Models\InventoryMovement;
use App\Models\Product;
use Livewire\Component;

class OrderInventory extends Component
{
    public $clientId;
    public $totalHectares = 0;
    public $existingInventory = [];
    public $inventoryMovements = [];
    public $products = [];

    protected $listeners = [
        'clientSelected' => 'loadProducts',
        'hectaresUpdated' => 'updateHectares',
        'flightsUpdated' => 'updateFromFlights',
    ];

    public function mount($clientId = null, $existingInventory = [], $totalHectares = 0): void
    {

        $this->clientId = $clientId;
        $this->totalHectares = $totalHectares;
        $this->existingInventory = $existingInventory;

        $this->loadProducts();

        $this->initializeInventoryMovements();
    }

    public function loadProducts(): void
    {
        $this->products = Product::where('merchant_id', auth()->user()->merchant_id)
            ->orderBy('name')
            ->get();
    }

    public function initializeInventoryMovements(): void
    {
        if (!empty($this->existingInventory)) {
            $this->inventoryMovements = $this->existingInventory;
        } else {
            $this->inventoryMovements = [];
        }
    }

    public function updateFromFlights($flights): void
    {
        $productQuantities = [];

        foreach ($flights as $flight) {
            if (isset($flight['products']) && is_array($flight['products'])) {
                foreach ($flight['products'] as $product) {
                    $productId = $product['product_id'];
                    $quantity = floatval($product['quantity'] ?? 0);

                    if (!isset($productQuantities[$productId])) {
                        $productQuantities[$productId] = 0;
                    }
                    $productQuantities[$productId] += $quantity;
                }
            }
        }

        // Update inventory movements with calculated quantities
        foreach ($productQuantities as $productId => $requiredQuantity) {
            $this->updateInventoryMovement($productId, $requiredQuantity);
        }

        // Remove inventory movements for products no longer in flights
        $this->inventoryMovements = array_filter($this->inventoryMovements, function ($movement) use ($productQuantities) {
            return isset($productQuantities[$movement['product_id']]);
        });

        // Load fresh product data to get current stock
        $this->loadProducts();

        $this->dispatch('inventoryUpdated', $this->inventoryMovements);
    }

    public function updateHectares($hectares)
    {
        $this->totalHectares = $hectares;
    }

    private function updateInventoryMovement($productId, $requiredQuantity)
    {
        $existingIndex = null;
        foreach ($this->inventoryMovements as $index => $movement) {
            if ($movement['product_id'] == $productId) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $this->inventoryMovements[$existingIndex]['required_quantity'] = $requiredQuantity;
        } else {
            $this->inventoryMovements[] = [
                'product_id' => $productId,
                'client_provided_quantity' => 0,
                'required_quantity' => $requiredQuantity,
                'tenant_quantity_to_add' => 0,
                'add_client_surplus_to_inventory' => false,
                'add_tenant_to_inventory' => true, // Default to true for tenant additions
                'notes' => '',
            ];
        }
    }

    public function updatedInventoryMovements($value, $key)
    {
        // Just dispatch the update - no complex calculations needed
        $this->dispatch('inventoryUpdated', $this->inventoryMovements);
    }

    public function hasInventoryShortage($movement)
    {
        $productStock = $this->getProductStock($movement['product_id']);
        $requiredQuantity = floatval($movement['required_quantity']);
        $clientProvided = floatval($movement['client_provided_quantity'] ?? 0);
        $tenantAdded = floatval($movement['tenant_quantity_to_add'] ?? 0);

        $totalAvailable = $productStock + $clientProvided + $tenantAdded;

        return $totalAvailable < $requiredQuantity;
    }

    public function getInventoryUsageNote($movement)
    {
        $productStock = $this->getProductStock($movement['product_id']);
        $requiredQuantity = floatval($movement['required_quantity']);
        $clientProvided = floatval($movement['client_provided_quantity'] ?? 0);
        $tenantAdded = floatval($movement['tenant_quantity_to_add'] ?? 0);

        $totalProvided = $clientProvided + $tenantAdded;
        $stockNeeded = $requiredQuantity - $totalProvided;

        if ($stockNeeded <= 0) {
            return "Cubierto completamente con productos agregados";
        }

        if ($productStock >= $stockNeeded) {
            return "Se utilizará {$stockNeeded} L del inventario (Disponible: {$productStock} L)";
        } else {
            $shortage = $stockNeeded - $productStock;
            return "⚠️ Stock insuficiente. Disponible: {$productStock} L, Falta: {$shortage} L";
        }
    }

    public function getStockStatusClass($movement)
    {
        $productStock = $this->getProductStock($movement['product_id']);
        $requiredQuantity = floatval($movement['required_quantity']);
        $clientProvided = floatval($movement['client_provided_quantity'] ?? 0);
        $tenantAdded = floatval($movement['tenant_quantity_to_add'] ?? 0);

        $totalProvided = $clientProvided + $tenantAdded;
        $stockNeeded = $requiredQuantity - $totalProvided;

        if ($stockNeeded <= 0) {
            return 'text-success';
        }

        return $productStock >= $stockNeeded ? 'text-warning' : 'text-danger';
    }

    public function getProductName($productId)
    {
        foreach ($this->products as $product) {
            if ($product['id'] == $productId) {
                return $product['name'];
            }
        }
        return 'Producto no encontrado';
    }

    public function getProductStock($productId)
    {
        foreach ($this->products as $product) {
            if ($product['id'] == $productId) {
                $stockInContainers = $product['stock'] ?? 0;
                $litersPerCan = $product['liters_per_can'] ?? 0;

                // If liters_per_can is defined, convert containers to liters
                if ($litersPerCan > 0) {
                    return $stockInContainers * $litersPerCan;
                }

                // Otherwise, stock is already in liters
                return $stockInContainers;
            }
        }
        return 0;
    }

    public function getStockDisplayInfo($productId)
    {
        foreach ($this->products as $product) {
            if ($product['id'] == $productId) {
                $stockInContainers = $product['stock'] ?? 0;
                $litersPerCan = $product['liters_per_can'] ?? 0;

                if ($litersPerCan > 0) {
                    $totalLiters = $stockInContainers * $litersPerCan;
                    return [
                        'total_liters' => $totalLiters,
                        'display_text' => "{$totalLiters} L ({$stockInContainers} envases)",
                        'containers' => $stockInContainers,
                        'liters_per_container' => $litersPerCan
                    ];
                }

                return [
                    'total_liters' => $stockInContainers,
                    'display_text' => "{$stockInContainers} L",
                    'containers' => null,
                    'liters_per_container' => null
                ];
            }
        }

        return [
            'total_liters' => 0,
            'display_text' => '0 L',
            'containers' => 0,
            'liters_per_container' => 0
        ];
    }

    public function render()
    {
        return view('livewire.order-inventory');
    }
}
