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

    public function mount($clientId = null, $existingInventory = [], $totalHectares = 0)
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

    public function initializeInventoryMovements()
    {
        if (!empty($this->existingInventory)) {
            $this->inventoryMovements = $this->existingInventory;
        } else {
            $this->inventoryMovements = [];
        }
    }

    public function updateFromFlights($flights)
    {
        // Calculate required quantities based on flight products
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
            $this->calculateDifference($existingIndex);
        } else {
            $this->inventoryMovements[] = [
                'product_id' => $productId,
                'client_provides_product' => false,
                'client_provided_quantity' => 0,
                'required_quantity' => $requiredQuantity,
                'difference_quantity' => 0,
                'difference_type' => 'exact',
                'add_surplus_to_inventory' => false,
                'notes' => '',
            ];
        }
    }

    public function toggleClientProvidesProduct($index)
    {
        $this->inventoryMovements[$index]['client_provides_product'] = !$this->inventoryMovements[$index]['client_provides_product'];

        if (!$this->inventoryMovements[$index]['client_provides_product']) {
            $this->inventoryMovements[$index]['client_provided_quantity'] = 0;
            $this->inventoryMovements[$index]['add_surplus_to_inventory'] = false;
        }

        $this->calculateDifference($index);
        $this->dispatch('inventoryUpdated', $this->inventoryMovements);
    }

    public function updatedInventoryMovements($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $index = $parts[0];
            $field = $parts[1];

            if ($field === 'client_provided_quantity') {
                $this->calculateDifference($index);
            }
        }

        $this->dispatch('inventoryUpdated', $this->inventoryMovements);
    }

    private function calculateDifference($index)
    {
        $movement = &$this->inventoryMovements[$index];
        $clientProvided = floatval($movement['client_provided_quantity']);
        $required = floatval($movement['required_quantity']);

        $difference = $clientProvided - $required;
        $movement['difference_quantity'] = abs($difference);

        if ($difference > 0) {
            $movement['difference_type'] = 'surplus';
        } elseif ($difference < 0) {
            $movement['difference_type'] = 'shortage';
            $movement['add_surplus_to_inventory'] = false; // Can't add shortage to inventory
        } else {
            $movement['difference_type'] = 'exact';
            $movement['add_surplus_to_inventory'] = false; // No surplus to add
        }
    }

    public function toggleAddSurplusToInventory($index)
    {
        if ($this->inventoryMovements[$index]['difference_type'] === 'surplus') {
            $this->inventoryMovements[$index]['add_surplus_to_inventory'] = !$this->inventoryMovements[$index]['add_surplus_to_inventory'];
            $this->dispatch('inventoryUpdated', $this->inventoryMovements);
        }
    }

    public function getDifferenceText($movement)
    {
        if ($movement['difference_type'] === 'exact') {
            return 'Cantidad exacta';
        }

        $action = $movement['difference_type'] === 'surplus' ? 'Sobra' : 'Falta';
        return "{$action} {$movement['difference_quantity']} de producto";
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
                return $product['stock'] ?? 0;
            }
        }
        return 0;
    }

    public function getInventoryUsageNote($movement)
    {
        $productStock = $this->getProductStock($movement['product_id']);
        $requiredQuantity = floatval($movement['required_quantity']);

        // If client doesn't provide product, will use inventory stock
        if (!$movement['client_provides_product']) {
            if ($productStock >= $requiredQuantity) {
                return "Se utilizará stock del inventario (Disponible: {$productStock} L)";
            } else {
                $shortage = $requiredQuantity - $productStock;
                return "⚠️ Stock insuficiente. Disponible: {$productStock} L, Falta: {$shortage} L";
            }
        }

        // If client provides product but there's a shortage
        if ($movement['difference_type'] === 'shortage') {
            $shortageAmount = floatval($movement['difference_quantity']);
            if ($productStock >= $shortageAmount) {
                return "Se completará con stock del inventario (Disponible: {$productStock} L)";
            } else {
                $totalShortage = $shortageAmount - $productStock;
                return "⚠️ Stock insuficiente para completar. Disponible: {$productStock} L, Falta: {$totalShortage} L";
            }
        }

        return '';
    }

    public function getStockStatusClass($movement)
    {
        $productStock = $this->getProductStock($movement['product_id']);
        $requiredQuantity = floatval($movement['required_quantity']);

        if (!$movement['client_provides_product']) {
            return $productStock >= $requiredQuantity ? 'text-success' : 'text-danger';
        }

        if ($movement['difference_type'] === 'shortage') {
            $shortageAmount = floatval($movement['difference_quantity']);
            return $productStock >= $shortageAmount ? 'text-warning' : 'text-danger';
        }

        return 'text-muted';
    }

    public function render()
    {
        return view('livewire.order-inventory');
    }
}
