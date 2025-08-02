<?php

namespace App\Livewire;

use App\Models\InventoryMovement;
use App\Models\Merchant;
use App\Models\Product;
use Livewire\Component;

class InventoryMovementForm extends Component
{
    public $showModal = false;
    public $isEditing = false;
    public $inventoryMovementId;

    // Form fields
    public $product_id;
    public $client_provided = false;
    public $quantity = 0;
    public $merchant_id;
    public $notes = '';

    // Lists for dropdowns
    public $products = [];
    public $clients = [];

    // Parent product ID (when accessed from ProductForm)
    public $parentProductId = null;

    protected $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|numeric|min:0',
        'merchant_id' => 'nullable|exists:merchants,id',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'product_id.required' => 'El producto es obligatorio.',
        'product_id.exists' => 'El producto seleccionado no es válido.',
        'quantity.required' => 'La cantidad es obligatoria.',
        'quantity.numeric' => 'La cantidad debe ser un número.',
        'quantity.min' => 'La cantidad debe ser mayor o igual a 0.',
        'merchant_id.exists' => 'El cliente seleccionado no es válido.',
        'notes.max' => 'Las notas no pueden exceder 500 caracteres.',
    ];

    protected $listeners = [
        'openInventoryMovementModal' => 'openModal',
        'closeInventoryMovementModal' => 'closeModal',
    ];

    public function mount($productId = null)
    {
        $this->parentProductId = $productId;
        $this->product_id = $productId;
        $this->loadProducts();
        $this->loadClients();
    }

    public function loadProducts()
    {
        $this->products = Product::where('merchant_id', auth()->user()->merchant_id)
            ->orderBy('name')
            ->get();
    }

    public function loadClients()
    {
        $this->clients = Merchant::where('merchant_type', 'client')
            ->where('merchant_id', auth()->user()->merchant_id)
            ->orderBy('business_name')
            ->get();
    }

    public function openModal($inventoryMovementId = null)
    {
        $this->resetForm();

        if ($inventoryMovementId) {
            $this->isEditing = true;
            $this->inventoryMovementId = $inventoryMovementId;
            $this->loadInventoryMovement($inventoryMovementId);
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->isEditing = false;
        $this->inventoryMovementId = null;
        $this->product_id = $this->parentProductId;
        $this->client_provided = false;
        $this->quantity = 0;
        $this->merchant_id = null;
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function loadInventoryMovement($id)
    {
        $movement = InventoryMovement::findOrFail($id);

        $this->product_id = $movement->product_id;
        $this->client_provided = $movement->client_provided;
        $this->quantity = $movement->quantity;
        $this->merchant_id = $movement->merchant_id;
        $this->notes = $movement->notes;
    }

    public function updatedClientProvided($value)
    {
        // Reset merchant_id if not client provided
        if (!$value) {
            $this->merchant_id = null;
        }

        // Force a re-render to show/hide the client selector
        $this->dispatch('$refresh');
    }

    public function save()
    {
        $this->validate();
        try {
            $data = [
                'product_id' => $this->product_id,
                'client_provided' => $this->client_provided,
                'quantity' => $this->quantity,
                'required_quantity' => 0, // Sin cantidad requerida para movimientos manuales
                'add_surplus_to_inventory' => true, // Siempre actualizar stock en movimientos manuales
                'merchant_id' => $this->client_provided ? $this->merchant_id : null,
                'notes' => $this->notes,
                'order_id' => null, // Manual inventory movements don't have an order
            ];

            if ($this->isEditing) {
                $movement = InventoryMovement::findOrFail($this->inventoryMovementId);
                $movement->update($data);
                $message = 'Movimiento de inventario actualizado correctamente';
            } else {
                $movement = InventoryMovement::create($data);
                $message = 'Movimiento de inventario creado correctamente';
            }

            // Siempre actualizar el stock del producto
            if ($this->quantity > 0) {
                $product = Product::find($this->product_id);
                if ($product) {
                    // Convert liters to containers if product uses containers
                    if ($product->liters_per_can > 0) {
                        $containersToAdd = $this->quantity / $product->liters_per_can;
                        $product->increment('stock', $containersToAdd);
                    } else {
                        // Stock is in liters
                        $product->increment('stock', $this->quantity);
                    }
                }
            }

            $this->dispatch('showAlert', [
                'title' => 'Éxito',
                'text' => $message,
                'type' => 'success'
            ]);

            $this->dispatch('inventoryMovementSaved');
            $this->closeModal();

            // refresh page
            $this->dispatch('refreshPage');

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'title' => 'Error',
                'text' => 'Ha ocurrido un error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function getSelectedProduct()
    {
        if ($this->product_id) {
            return $this->products->firstWhere('id', $this->product_id);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.inventory-movement-form');
    }
}
