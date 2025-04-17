<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class OrderProducts extends Component
{
    public $clientId;
    public $selectedProducts = [];
    public $availableProducts = [];
    public $totalHectares = 0;

    protected $listeners = [
        'clientSelected' => 'loadAvailableProducts',
        'hectaresUpdated' => 'updateTotalHectares'
    ];

    public function mount($clientId = null, $existingProducts = [], $totalHectares = 0)
    {
        $this->clientId = $clientId;
        $this->totalHectares = floatval($totalHectares);

        // Initialize with proper structure including checkboxes
        if (empty($existingProducts)) {
            $this->selectedProducts = [];
        } else {
            $this->selectedProducts = $existingProducts;
        }

        if ($this->clientId) {
            $this->loadAvailableProducts();
        }
    }

    public function loadAvailableProducts($clientId = null)
    {
        if ($clientId !== null) {
            $this->clientId = $clientId;
        }

        if (!$this->clientId) {
            $this->availableProducts = [];
            return;
        }

        $this->availableProducts = Product::where(function($query) {
            $query->where('merchant_id', $this->clientId)
                ->orWhereNull('merchant_id');
        })
            ->orderBy('name')
            ->get();
    }

    public function updateTotalHectares($hectares)
    {
        $this->totalHectares = floatval($hectares);
        $this->recalculateProducts();
    }

    public function addProduct()
    {
        $this->selectedProducts[] = [
            'product_id' => '',
            'use_client_quantity' => false,    // Checkbox for column C
            'client_provided_quantity' => 0,
            'manual_total_quantity' => 0,
            'use_manual_dosage' => false,      // Checkbox for column F
            'manual_dosage_per_hectare' => 0,
            'total_quantity_to_use' => 0,
            'calculated_dosage' => 0,
            'product_difference' => 0,
            'difference_observation' => ''
        ];

        $this->dispatch('productsUpdated', $this->selectedProducts);
    }

    public function removeProduct($index)
    {
        unset($this->selectedProducts[$index]);
        $this->selectedProducts = array_values($this->selectedProducts);

        $this->dispatch('productsUpdated', $this->selectedProducts);
    }

    public function updated($name, $value)
    {
        $this->recalculateProducts();
    }

    public function recalculateProducts()
    {
        if ($this->totalHectares <= 0) {
            return;
        }

        foreach ($this->selectedProducts as $index => $product) {
            if (empty($product['product_id'])) {
                continue;
            }

            // Safe conversion of values to float
            $clientQuantity = floatval($product['client_provided_quantity'] ?? 0);
            $manualTotalQuantity = floatval($product['manual_total_quantity'] ?? 0);
            $manualDosage = floatval($product['manual_dosage_per_hectare'] ?? 0);
            $useClientQuantity = isset($product['use_client_quantity']) ? (bool)$product['use_client_quantity'] : false;
            $useManualDosage = isset($product['use_manual_dosage']) ? (bool)$product['use_manual_dosage'] : false;

            // Calculate CANTIDAD TOTAL A UTILIZAR
            // =if(C22=TRUE,D22,IF(F22=TRUE,D20*G22,E22))
            if ($useClientQuantity) {
                $totalToUse = $clientQuantity;
            } elseif ($useManualDosage) {
                $totalToUse = $this->totalHectares * $manualDosage;
            } else {
                $totalToUse = $manualTotalQuantity;
            }

            // Calculate DOSIS
            // =IF(C22=TRUE,D22/D20,IF(F22=TRUE,G22,E22/D20))
            if ($useClientQuantity) {
                $dosis = $this->totalHectares > 0 ? $clientQuantity / $this->totalHectares : 0;
            } elseif ($useManualDosage) {
                $dosis = $manualDosage;
            } else {
                $dosis = $this->totalHectares > 0 ? $manualTotalQuantity / $this->totalHectares : 0;
            }

            // Update the calculated values
            $this->selectedProducts[$index]['total_quantity_to_use'] = round($totalToUse, 2);
            $this->selectedProducts[$index]['calculated_dosage'] = round($dosis, 2);

            // Calculate SOBRA/FALTA PRODUCTO
            // =if((H22-D22)<>0,IF(H22>D22,"Falta producto","Sobra producto"),"n/a")
            $difference = $totalToUse - $clientQuantity;

            if ($difference != 0) {
                $this->selectedProducts[$index]['product_status'] = ($totalToUse > $clientQuantity) ? 'Falta producto' : 'Sobra producto';
            } else {
                $this->selectedProducts[$index]['product_status'] = 'n/a';
            }

            // Calculate CANTIDAD sobrante/faltante
            // =IF(D22>H22,D22-H22,H22-D22)
            $this->selectedProducts[$index]['product_difference'] = abs($difference);
        }

        $this->dispatch('productsUpdated', $this->selectedProducts);
    }

    public function render()
    {
        return view('livewire.order-products');
    }
}
