<?php

namespace App\Livewire;

use App\Models\Lot;
use App\Models\Merchant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class LotForm extends Component
{
    use WithFileUploads;

    public $merchant_id;
    public $number;
    
    public $hectares = 0;
    public $coordinates = [];
    public $kmlFile;
    public $currentLotId;

    protected $rules = [
        'merchant_id' => 'required|exists:merchants,id',
        'number' => 'required|numeric|max:255',
        'hectares' => 'required|numeric|min:0',
        'coordinates' => 'required|array|min:3',
        'coordinates.*.lat' => 'required|numeric|between:-90,90',
        'coordinates.*.lng' => 'required|numeric|between:-180,180',
    ];

    public function mount($lotId = null)
    {
        $this->currentLotId = $lotId;
        if ($lotId) {
            $this->loadLot($lotId);
        }
    }

    public function loadLot($lotId)
    {
        $lot = Lot::with('coordinates')->findOrFail($lotId);
        $this->merchant_id = $lot->merchant_id;
        $this->number = $lot->number;
        
        $this->hectares = $lot->hectares;
        $this->coordinates = $lot->coordinates->map(function ($coords) {
            return [
                'lat' => $coords->latitude,
                'lng' => $coords->longitude,
            ];
        })->toArray();

        $this->dispatch('lot-loaded', [
            'coordinates' => $this->coordinates,
            'hectares' => $this->hectares
        ]);
    }

    #[On('updateCoordinates')]
    public function updateCoordinates($coords, $hectares)
    {
        $this->coordinates = $coords;
        $this->hectares = $hectares;
    }

    public function saveLot()
    {
        $this->validate();

        $lot = $this->currentLotId ?
            Lot::findOrFail($this->currentLotId) :
            new Lot();

        $lot->fill([
            'merchant_id' => $this->merchant_id,
            'number' => $this->number,
            
            'hectares' => $this->hectares,
        ]);

        $lot->save();

        if ($this->currentLotId) {
            $lot->coordinates()->delete();
        }

        collect($this->coordinates)->each(function ($coord, $index) use ($lot) {
            $lot->coordinates()->create([
                'latitude' => $coord['lat'],
                'longitude' => $coord['lng'],
                'sequence_number' => $index
            ]);
        });

        session()->flash(
            'message',
            $this->currentLotId ? 'Lote actualizado exitosamente.' : 'Lote creado exitosamente.'
        );
        return redirect()->route('lots.index');
    }

    public function render()
    {
        return view('livewire.lot-form', [
            'merchants' => Merchant::all()
        ]);
    }
}
