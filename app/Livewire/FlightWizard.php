<?php

namespace App\Livewire;

use App\Models\Lot;
use App\Models\Product;
use App\Models\OrderLot;
use App\Models\FlightLot;
use Illuminate\Support\Collection;
use Livewire\Component;

class FlightWizard extends Component
{
    // Modal state
    public $showModal = false;
    public $currentStep = 1;
    public $maxSteps = 2;

    // Order context
    public $orderId;
    public $clientId;
    public $selectedOrderLots = [];
    public $selectedOrderProducts = [];

    // Step 1: Lot and Area Selection
    public $availableLots = [];
    public $selectedFlightLots = [];
    public $totalFlightHectares = 0;

    // Step 2: Product Selection and Dosage
    public $availableProducts = [];
    public $selectedFlightProducts = [];
    public $calculationMethod = 'by_quantity'; // 'by_quantity' or 'by_dosage'

    protected $rules = [
        'selectedFlightLots' => 'required|array|min:1',
        'selectedFlightLots.*.lot_id' => 'required|exists:lots,id',
        'selectedFlightLots.*.hectares_to_apply' => 'required|numeric|min:0.01',
        // Remove validation for products array since we filter selected ones
        // 'selectedFlightProducts' => 'required|array|min:1',
        // 'selectedFlightProducts.*.product_id' => 'required|exists:products,id',
        // 'selectedFlightProducts.*.total_quantity' => 'required|numeric|min:0.01',
        // 'selectedFlightProducts.*.dosage_per_hectare' => 'required|numeric|min:0.01',
    ];

    protected $listeners = [
        'openFlightWizard' => 'openWizard',
        'closeFlightWizard' => 'closeWizard',
        'lotsUpdated' => 'handleLotsUpdated',
        'productsUpdated' => 'handleProductsUpdated'
    ];

    public function mount($clientId = null, $orderLots = [], $orderProducts = [])
    {
        $this->clientId = $clientId;
        $this->selectedOrderLots = $orderLots;
        $this->selectedOrderProducts = $orderProducts;

        // Initialize as empty collections
        $this->availableLots = collect();
        $this->availableProducts = collect();
        $this->selectedFlightLots = [];
        $this->selectedFlightProducts = [];
    }

    public function openWizard($data = [])
    {
        if (isset($data['orderId'])) {
            $this->orderId = $data['orderId'];
        }
        if (isset($data['clientId'])) {
            $this->clientId = $data['clientId'];
        }
        if (isset($data['orderLots'])) {
            $this->selectedOrderLots = $data['orderLots'];
        }
        if (isset($data['orderProducts'])) {
            $this->selectedOrderProducts = $data['orderProducts'];
        }

        $this->loadAvailableLots();
        $this->loadAvailableProducts();
        $this->initializeFlightLots();
        $this->initializeFlightProducts();

        $this->currentStep = 1;
        $this->showModal = true;
    }

    public function closeWizard()
    {
        $this->showModal = false;
        $this->currentStep = 1;
        $this->resetWizardData();
    }

    protected function resetWizardData()
    {
        $this->selectedFlightLots = [];
        $this->selectedFlightProducts = [];
        $this->totalFlightHectares = 0;
        $this->calculationMethod = 'by_quantity';
    }

    protected function loadAvailableLots()
    {
        if (!$this->clientId || empty($this->selectedOrderLots)) {
            $this->availableLots = collect();
            return;
        }

        // Get only the lots that were selected in the OrderLots form
        $orderLotIds = collect($this->selectedOrderLots)->pluck('lot_id')->filter();

        if ($orderLotIds->isEmpty()) {
            $this->availableLots = collect();
            return;
        }

        // Get the actual lot models for the selected order lots
        $selectedLots = Lot::whereIn('id', $orderLotIds)->get();

        // Calculate remaining hectares for each lot based on previous flights
        $this->availableLots = $selectedLots->map(function ($lot) {
            $totalHectaresUsed = $this->getTotalHectaresUsedInLot($lot->id);

            // Get the hectares from the order lot (not the total lot hectares)
            $orderLot = collect($this->selectedOrderLots)->firstWhere('lot_id', $lot->id);
            $orderLotHectares = $orderLot ? floatval($orderLot['hectares']) : $lot->hectares;

            $remainingHectares = max(0, $orderLotHectares - $totalHectaresUsed);

            return [
                'id' => $lot->id,
                'number' => $lot->number,
                'name' => $lot->name,
                'total_hectares' => $lot->hectares, // Total lot hectares
                'order_hectares' => $orderLotHectares, // Hectares assigned to this order
                'used_hectares' => $totalHectaresUsed,
                'remaining_hectares' => $remainingHectares,
                'coordinates' => $lot->coordinates,
            ];
        })->filter(function ($lot) {
            // Only show lots with remaining hectares
            return $lot['remaining_hectares'] > 0;
        });
    }

    protected function loadAvailableProducts()
    {
        $allProducts = \App\Models\Product::where('merchant_id', auth()->user()->merchant_id)
            ->orderBy('name')
            ->get();

        $this->availableProducts = $allProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'unit' => $product->unit ?? 'L',
                'category' => $product->category->name ?? 'Sin categoría',
                'description' => $product->description,
            ];
        });
    }

    protected function initializeFlightLots()
    {
        $this->selectedFlightLots = [];
        foreach ($this->availableLots as $lot) {
            $this->selectedFlightLots[] = [
                'lot_id' => $lot['id'],
                'lot_number' => $lot['number'],
                'lot_name' => $lot['name'],
                'total_hectares' => $lot['total_hectares'],
                'remaining_hectares' => $lot['remaining_hectares'],
                'hectares_to_apply' => 0,
                'selected' => false,
            ];
        }
    }

    protected function initializeFlightProducts()
    {
        $this->selectedFlightProducts = [];
        foreach ($this->availableProducts as $product) {
            $this->selectedFlightProducts[] = [
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'unit' => $product['unit'],
                'category' => $product['category'],
                'total_quantity' => 0,
                'dosage_per_hectare' => 0, // Start with 0 since no pre-defined dosage
                'selected' => false, // Explicitly set to false to prevent default selection
            ];
        }
    }

    protected function getTotalHectaresUsedInLot($lotId)
    {
        if (!$this->orderId) {
            return 0;
        }

        // Sum all hectares used in previous flights for this lot
        return FlightLot::whereHas('flight', function ($query) {
                $query->where('order_id', $this->orderId);
            })
            ->where('lot_id', $lotId)
            ->sum('hectares_to_apply');
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validateStep1();
            $this->calculateTotalHectares();
            $this->updateProductQuantities();
        }

        if ($this->currentStep < $this->maxSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateStep1()
    {
        $selectedLots = collect($this->selectedFlightLots)->where('selected', true);

        if ($selectedLots->isEmpty()) {
            throw new \Exception('Debe seleccionar al menos un lote.');
        }

        foreach ($selectedLots as $lot) {
            if ($lot['hectares_to_apply'] <= 0) {
                throw new \Exception('Las hectáreas a aplicar deben ser mayor a 0.');
            }
            if ($lot['hectares_to_apply'] > $lot['remaining_hectares']) {
                throw new \Exception("Las hectáreas a aplicar no pueden ser mayor a las hectáreas disponibles para el lote {$lot['lot_number']}.");
            }
        }
    }

    public function toggleLotSelection($index)
    {
        $this->selectedFlightLots[$index]['selected'] = !$this->selectedFlightLots[$index]['selected'];

        if (!$this->selectedFlightLots[$index]['selected']) {
            $this->selectedFlightLots[$index]['hectares_to_apply'] = 0;
        }

        $this->calculateTotalHectares();
    }

    public function useRemainingHectares($index)
    {
        if ($this->selectedFlightLots[$index]['selected']) {
            $this->selectedFlightLots[$index]['hectares_to_apply'] = $this->selectedFlightLots[$index]['remaining_hectares'];
            $this->calculateTotalHectares();
        }
    }

    public function updatedSelectedFlightLots($value, $key)
    {
        if (strpos($key, '.hectares_to_apply') !== false) {
            $this->calculateTotalHectares();
            $this->updateProductQuantities();
        }
    }

    protected function calculateTotalHectares()
    {
        $this->totalFlightHectares = collect($this->selectedFlightLots)
            ->where('selected', true)
            ->sum('hectares_to_apply');
    }

    public function toggleProductSelection($index)
    {
        $this->selectedFlightProducts[$index]['selected'] = !$this->selectedFlightProducts[$index]['selected'];

        if (!$this->selectedFlightProducts[$index]['selected']) {
            $this->selectedFlightProducts[$index]['total_quantity'] = 0;
            $this->selectedFlightProducts[$index]['dosage_per_hectare'] = 0;
        } else {
            // Auto-calculate based on current method
            $this->calculateProductValues($index);
        }
    }

    public function updatedCalculationMethod()
    {
        // Recalculate all selected products when method changes
        foreach ($this->selectedFlightProducts as $index => $product) {
            if ($product['selected']) {
                $this->calculateProductValues($index);
            }
        }
    }

    public function updatedSelectedFlightProducts($value, $key)
    {
        // Extract index from key (e.g., "0.total_quantity" -> 0)
        $parts = explode('.', $key);
        $index = $parts[0];
        $field = $parts[1];

        if ($this->selectedFlightProducts[$index]['selected']) {
            if ($field === 'total_quantity' && $this->calculationMethod === 'by_quantity') {
                $this->calculateDosageFromQuantity($index);
            } elseif ($field === 'dosage_per_hectare' && $this->calculationMethod === 'by_dosage') {
                $this->calculateQuantityFromDosage($index);
            }
        }
    }

    protected function updateProductQuantities()
    {
        foreach ($this->selectedFlightProducts as $index => $product) {
            if ($product['selected']) {
                $this->calculateProductValues($index);
            }
        }
    }

    protected function calculateProductValues($index)
    {
        if ($this->totalFlightHectares <= 0) {
            return;
        }

        if ($this->calculationMethod === 'by_dosage') {
            $this->calculateQuantityFromDosage($index);
        } else {
            $this->calculateDosageFromQuantity($index);
        }
    }

    protected function calculateQuantityFromDosage($index)
    {
        $dosage = floatval($this->selectedFlightProducts[$index]['dosage_per_hectare']);
        $this->selectedFlightProducts[$index]['total_quantity'] = $dosage * $this->totalFlightHectares;
    }

    protected function calculateDosageFromQuantity($index)
    {
        if ($this->totalFlightHectares > 0) {
            $quantity = floatval($this->selectedFlightProducts[$index]['total_quantity']);
            $this->selectedFlightProducts[$index]['dosage_per_hectare'] = $quantity / $this->totalFlightHectares;
        }
    }

    public function saveFlight()
    {
        try {
            // Custom validation for selected items
            $selectedLots = collect($this->selectedFlightLots)->where('selected', true);
            $selectedProducts = collect($this->selectedFlightProducts)->where('selected', true);

            if ($selectedLots->isEmpty()) {
                throw new \Exception('Debe seleccionar al menos un lote.');
            }

            if ($selectedProducts->isEmpty()) {
                throw new \Exception('Debe seleccionar al menos un producto.');
            }

            // Validate each selected product has proper quantities
            foreach ($selectedProducts as $product) {
                if ($product['total_quantity'] <= 0) {
                    throw new \Exception("El producto {$product['product_name']} debe tener una cantidad mayor a 0.");
                }
                if ($product['dosage_per_hectare'] <= 0) {
                    throw new \Exception("El producto {$product['product_name']} debe tener una dosificación mayor a 0.");
                }
            }

            $flightData = [
                'total_hectares' => $this->totalFlightHectares,
                'lots' => $selectedLots->map(function ($lot) {
                    return [
                        'lot_id' => $lot['lot_id'],
                        'lot_hectares' => $lot['total_hectares'],
                        'hectares_to_apply' => $lot['hectares_to_apply'],
                        'lot_total_hectares' => $lot['total_hectares'],
                        'dosage_per_lot' => $this->calculateDosagePerLot($lot)
                    ];
                })->values()->toArray(),
                'products' => $selectedProducts->map(function ($product) {
                    return [
                        'product_id' => $product['product_id'],
                        'quantity' => $product['total_quantity'],
                        'dosage_per_hectare' => $product['dosage_per_hectare']
                    ];
                })->values()->toArray(),
            ];

            $this->dispatch('flightCreated', $flightData);
            $this->closeWizard();

            $this->dispatch('showAlert', [
                'title' => 'Éxito',
                'text' => 'Vuelo configurado correctamente',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('showAlert', [
                'title' => 'Error',
                'text' => $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    protected function calculateDosagePerLot($lot)
    {
        $dosagePerLot = [];

        foreach ($this->selectedFlightProducts as $product) {
            if ($product['selected']) {
                $dosagePerLot[] = [
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'dosage' => $product['dosage_per_hectare'],
                    'total_for_lot' => $product['dosage_per_hectare'] * $lot['hectares_to_apply']
                ];
            }
        }

        return $dosagePerLot;
    }

    public function handleLotsUpdated($lots)
    {
        $this->selectedOrderLots = $lots;

        // If wizard is open, refresh the available lots
        if ($this->showModal) {
            $this->loadAvailableLots();
            $this->initializeFlightLots();
        }
    }

    public function handleProductsUpdated($products)
    {
        $this->selectedOrderProducts = $products;

        // If wizard is open, refresh the available products
        if ($this->showModal) {
            $this->loadAvailableProducts();
            $this->initializeFlightProducts();
        }
    }

    public function render()
    {
        return view('livewire.flight-wizard');
    }
}
