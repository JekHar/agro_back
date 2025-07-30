<?php

namespace App\Livewire;

use App\Models\Lot;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class OrderLots extends Component
{
    public $clientId;
    public $selectedLots = [];
    public $availableLots = [];

    protected $listeners = [
        'clientSelected' => 'loadAvailableLots',
        'lotCreated' => 'handleLotCreated',
    ];


    public function mount($clientId = null, $existingLots = [])
    {
        $this->clientId = $clientId;
        $this->selectedLots = $existingLots;

        if ($this->clientId) {
            $this->loadAvailableLots();
        }
    }

    /**
     * Load lots available for the selected client
     */
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

    /**
     * Add an empty lot to the selection
     */
    public function addLot()
    {
        $this->selectedLots[] = [
            'lot_id' => '',
            'hectares' => 0,
            'status' => 'pending'
        ];

        $this->dispatch('lotsUpdated', $this->selectedLots);
    }

    /**
     * Remove a lot from the selection
     */
    public function removeLot($index)
    {
        unset($this->selectedLots[$index]);
        $this->selectedLots = array_values($this->selectedLots);

        $this->dispatch('lotsUpdated', $this->selectedLots);
    }

    /**
     * Update lot data when selection changes
     */
    public function updatedSelectedLots($value, $key)
    {
        $keyParts = explode('.', $key);
        if (count($keyParts) === 2 && $keyParts[1] === 'lot_id') {
            $index = $keyParts[0];
            $lotId = $value;

            if (!empty($lotId)) {
                $lot = $this->availableLots->firstWhere('id', $lotId);
                if ($lot) {
                    $this->selectedLots[$index]['hectares'] = $lot->hectares;
                }
            }
        }

        $this->dispatch('lotsUpdated', $this->selectedLots);
    }

    /**
     * Handle opening the lot selection modal/page
     */
    public function openLotSelection()
    {
        // This could redirect to a lot creation page
        // or open a modal for lot selection
        $this->dispatch('openLotCreation', $this->clientId);
    }

    /**
     * Open the lot creation modal
     */
    public function createNewLot()
    {
        $this->dispatch('openLotModal');
    }

    /**
     * Handle the event when a new lot is created
     */
    public function handleLotCreated($lotId)
    {
        // Refresh available lots to include the new one
        $this->loadAvailableLots();

        // Automatically add the new lot to the selection
        $newLot = $this->availableLots->firstWhere('id', $lotId);
        if ($newLot) {
            $this->selectedLots[] = [
                'lot_id' => $newLot->id,
                'hectares' => $newLot->hectares,
                'status' => 'pending'
            ];

            $this->dispatch('lotsUpdated', $this->selectedLots);
        }
    }

    public function render()
    {
        return view('livewire.order-lots');
    }
}
