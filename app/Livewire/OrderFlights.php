<?php

namespace App\Livewire;

use App\Models\Lot;
use Livewire\Component;

class OrderFlights extends Component
{
    public $clientId;
    public $totalHectares = 0;
    public $flightCount = 1;
    public $flights = [];
    public $availableLots = [];
    public $remainingHectares;

    // Products from the order
    public $products = [];
    public $availableProducts = [];

    protected $listeners = [
        'clientSelected' => 'loadAvailableLots',
        'hectaresUpdated' => 'updateTotalHectares',
        'productsUpdated' => 'updateProducts',
        'availableProductsUpdated' => 'updateAvailableProducts',
    ];

    public function mount($clientId = null, $existingFlights = [], $totalHectares = 0, $products = [])
    {
        $this->clientId = $clientId;
        $this->totalHectares = floatval($totalHectares);
        $this->remainingHectares = $this->totalHectares;
        $this->products = $products;

        if (empty($existingFlights)) {
            // Initialize with one flight by default
            $this->initializeFlights(1);
        } else {
            $this->flights = $existingFlights;
            $this->flightCount = count($existingFlights);
        }

        if ($this->clientId) {
            $this->loadAvailableLots();
        }
    }

    public function initializeFlights($count)
    {
        $this->flights = [];
        for ($i = 0; $i < $count; $i++) {
            $this->flights[] = [
                'hectares_to_perform' => 0,
                'lots' => [
                    [
                        'lot_id' => '',
                        'lot_hectares' => 0,
                        'hectares_to_apply' => 0
                    ]
                ],
                'products' => []
            ];
        }
        $this->flightCount = $count;
        $this->updateProductsForFlights();
        $this->recalculateRemainingHectares();
    }

    public function loadAvailableLots($clientId = null)
    {
        if ($clientId !== null) {
            $this->clientId = $clientId;
        }

        if (!$this->clientId) {
            $this->availableLots = [];
            return;
        }

        $this->availableLots = Lot::where('merchant_id', $this->clientId)
            ->orderBy('number')
            ->get();
    }

    public function updateTotalHectares($hectares)
    {
        $this->totalHectares = floatval($hectares);
        $this->recalculateRemainingHectares();
    }

    public function updateProducts($products)
    {
        $this->products = $products;
        $this->updateProductsForFlights();
    }

    public function updateAvailableProducts($products)
    {
        $this->availableProducts = $products;
    }

    public function updatedFlightCount($value)
    {
        $this->initializeFlights($value);
    }

    public function updatedFlights($value, $key)
    {
        // Parse key to get flight index, field name, and possibly sub-indices
        $keyParts = explode('.', $key);
        $flightIndex = $keyParts[0];

        // If a flight's hectares changed, update remaining hectares
        if ($keyParts[1] === 'hectares_to_perform') {
            // Update hectares to apply for all lots in this flight
            foreach ($this->flights[$flightIndex]['lots'] as $lotIndex => $lot) {
                $this->recalculateLotHectaresToApply($flightIndex, $lotIndex);
            }
            $this->recalculateRemainingHectares();
        }

        // If a lot selection changed, update the lot's hectares
        if (count($keyParts) >= 4 && $keyParts[1] === 'lots' && $keyParts[3] === 'lot_id') {
            $lotIndex = $keyParts[2];
            $lotId = $value;

            if (!empty($lotId)) {
                $lot = $this->availableLots->firstWhere('id', $lotId);
                if ($lot) {
                    $this->flights[$flightIndex]['lots'][$lotIndex]['lot_hectares'] = $lot->hectares;
                    $this->recalculateLotHectaresToApply($flightIndex, $lotIndex);
                }
            } else {
                $this->flights[$flightIndex]['lots'][$lotIndex]['lot_hectares'] = 0;
                $this->flights[$flightIndex]['lots'][$lotIndex]['hectares_to_apply'] = 0;
            }
        }

        $this->updateProductsForFlights();
        $this->dispatch('flightsUpdated', $this->flights);
    }

    public function recalculateLotHectaresToApply($flightIndex, $lotIndex)
    {
        $flight = $this->flights[$flightIndex];
        $lot = $flight['lots'][$lotIndex];

        if (!empty($flight['hectares_to_perform']) && !empty($lot['lot_id'])) {
            $hectaresToPerform = floatval($flight['hectares_to_perform']);
            $lotHectares = floatval($lot['lot_hectares']);

            // Use the smaller value between flight hectares and lot hectares
            $hectaresToApply = min($hectaresToPerform, $lotHectares);

            $this->flights[$flightIndex]['lots'][$lotIndex]['hectares_to_apply'] = $hectaresToApply;
        } else {
            $this->flights[$flightIndex]['lots'][$lotIndex]['hectares_to_apply'] = 0;
        }
    }

    public function recalculateRemainingHectares()
    {
        $usedHectares = 0;
        foreach ($this->flights as $flight) {
            $usedHectares += floatval($flight['hectares_to_perform'] ?? 0);
        }

        $this->remainingHectares = $this->totalHectares - $usedHectares;
    }

    private function getTotalHectaresAppliedInFlight($flightIndex)
    {
        $total = 0;
        foreach ($this->flights[$flightIndex]['lots'] as $lot) {
            $total += floatval($lot['hectares_to_apply'] ?? 0);
        }
        return $total;
    }

    public function updateProductsForFlights()
    {
        foreach ($this->flights as $i => $flight) {
            $flightHectares = $this->getTotalHectaresAppliedInFlight($i);

            // Initialize products array if it doesn't exist
            if (!isset($this->flights[$i]['products'])) {
                $this->flights[$i]['products'] = [];
            }

            // Update product quantities based on hectares and product dosage
            foreach ($this->products as $product) {
                $productId = $product['product_id'] ?? null;
                if (empty($productId)) continue;

                $dosage = floatval($product['calculated_dosage'] ?? 0);
                $quantity = $flightHectares * $dosage;

                // Find if product already exists in flight
                $productExists = false;
                foreach ($this->flights[$i]['products'] as $j => $flightProduct) {
                    if ($flightProduct['product_id'] == $productId) {
                        $this->flights[$i]['products'][$j]['quantity'] = $quantity;
                        $productExists = true;
                        break;
                    }
                }

                // If product doesn't exist in flight, add it
                if (!$productExists) {
                    $this->flights[$i]['products'][] = [
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ];
                }
            }
        }
    }

    public function addLotToFlight($flightIndex)
    {
        $this->flights[$flightIndex]['lots'][] = [
            'lot_id' => '',
            'lot_hectares' => 0,
            'hectares_to_apply' => 0
        ];
    }

    public function removeLotFromFlight($flightIndex, $lotIndex)
    {
        if (count($this->flights[$flightIndex]['lots']) > 1) {
            unset($this->flights[$flightIndex]['lots'][$lotIndex]);
            $this->flights[$flightIndex]['lots'] = array_values($this->flights[$flightIndex]['lots']);
            $this->updateProductsForFlights();
            $this->dispatch('flightsUpdated', $this->flights);
        }
    }

    public function render()
    {
        return view('livewire.order-flights');
    }
}
