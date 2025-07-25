<?php

namespace App\Livewire;

use App\Http\Requests\LotRequest;
use App\Models\Lot;
use App\Models\Merchant;
use App\Types\MerchantType;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class LotForm extends Component
{
    use WithFileUploads;

    public $merchant_id;
    public $number;
    public $hectares = 0;
    public $coordinates = [];
    public $holes = [];
    public $kmlFile;
    public $name_lot;
    public $currentLotId;
    public $isCreateMode = false;
    public $navigationPin = ['lat' => null, 'lng' => null];

    protected function rules()
    {
        $lotRequest = new LotRequest;
        $lotRequest->setLotId($this->currentLotId);

        return [
            'rules' => $lotRequest->rules(),
            'messages' => $lotRequest->messages()
        ];
    }

    public function mount($lotId = null)
    {
        $this->currentLotId = $lotId;
        if ($lotId) {
            $this->loadLot($lotId);
        } else {
            $this->isCreateMode = true;
            $this->number = null;
        }
    }

    public function loadLot($lotId)
    {
        $lot = Lot::with(['coordinates' => function ($query) {
            $query->orderBy('sequence_number');
        }])->findOrFail($lotId);

        $this->merchant_id = $lot->merchant_id;
        $this->number = $lot->number;
        $this->hectares = $lot->hectares;
        $this->name_lot = $lot->name_lot;
        $this->navigationPin['lat'] = $lot->navigation_latitude; // Asumiendo que existe
        $this->navigationPin['lng'] = $lot->navigation_longitude;

        $mainCoords = $lot->coordinates->where('is_hole', false);
        $holeCoords = $lot->coordinates->where('is_hole', true)->groupBy('hole_group'); // Agrupa por hole_group

        $this->coordinates = $mainCoords->map(function ($coords) {
            return [
                'lat' => $coords->latitude,
                'lng' => $coords->longitude,
            ];
        })->values()->toArray();

        $this->holes = $holeCoords->map(function ($holeGroup) {
            return $holeGroup->sortBy('sequence_number')->map(function ($coords) {
                return [
                    'lat' => $coords->latitude,
                    'lng' => $coords->longitude,
                ];
            })->values()->toArray();
        })->values()->toArray();


        $this->dispatch('lot-loaded', [
            'coordinates' => $this->coordinates,
            'holes' => $this->holes, 
            'hectares' => $this->hectares,
            'navigationPin' => $this->navigationPin,
        ]);
    }

    #[On('updateNavigationPin')]
    public function updateNavigationPin($lat, $lng) 
    {
        $this->navigationPin['lat'] = $lat;
        $this->navigationPin['lng'] = $lng;
    }

    #[On('updateCoordinates')]
    public function updateCoordinates($coords, $hectares, $holes = [])
    {
        $this->coordinates = $coords;
        $this->hectares = round($hectares, 2);
        $this->holes = $holes;
    }

    public function updatedMerchantId($value)
    {
        if ($this->isCreateMode && $value) {
            $this->number = $this->getNextLotNumber($value);
        }
    }

    protected function getNextLotNumber($merchantId)
    {
        $maxNumber = Lot::where('merchant_id', $merchantId)->max('number');
        return ($maxNumber ?? 0) + 1;
    }

    public function saveLot()
    {
        $validatedData = $this->validate(
            $this->rules()['rules'],
            $this->rules()['messages']
        );

        try {
            $lot = $this->currentLotId ?
                Lot::findOrFail($this->currentLotId) :
                new Lot;

            if ($this->currentLotId) {
                $lot->coordinates()->delete(); // Asegura que se borran las coordenadas existentes antes de guardar las nuevas
            }


            if (!$this->currentLotId) {
                $validatedData['number'] = $this->getNextLotNumber($validatedData['merchant_id']);
            }

            $lot->fill([
                'merchant_id' => $validatedData['merchant_id'],
                'number' => $validatedData['number'],
                'hectares' => $validatedData['hectares'],
                'name_lot' => $validatedData['name_lot'],
                'navigation_latitude' => $this->navigationPin['lat'], // Guardar el pin
                'navigation_longitude' => $this->navigationPin['lng'], // Guardar el pin
            ]);
            
            $lot->save();

            if ($this->currentLotId) {
                $lot->coordinates()->delete();
            }

            collect($this->coordinates)->each(function ($coord, $index) use ($lot) {
                $lot->coordinates()->create([
                    'latitude' => $coord['lat'],
                    'longitude' => $coord['lng'],
                    'sequence_number' => $index,
                    'is_hole' => false,
                    'hole_group' => null,
                ]);
            });

            // Guarda los agujeros
            collect($this->holes)->each(function ($hole, $holeIndex) use ($lot) {
                collect($hole)->each(function ($coord, $coordIndex) use ($lot, $holeIndex) {
                    $lot->coordinates()->create([
                        'latitude' => $coord['lat'],
                        'longitude' => $coord['lng'],
                        'sequence_number' => $coordIndex,
                        'is_hole' => true,
                        'hole_group' => $holeIndex + 1, // Asegúrate que esto sea consistente
                    ]);
                });
            });

            $this->dispatch('swal', [
                'title' => __('crud.success'),
                'message' => __($this->currentLotId ? 'crud.lots.actions.updated' : 'crud.lots.actions.created'),
                'icon' => 'success',
                'redirect' => route('lots.index'),
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());

            $this->dispatch('swal', [
                'title' => __('Error'),
                'message' => __('crud.lots.actions.error'),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        $merchantsQuery = Merchant::where('merchant_type', MerchantType::CLIENT);

        if (auth()->user()->hasRole('Tenant')) {
            $merchantsQuery->where('merchant_id', auth()->user()->merchant_id);
        }

        return view('livewire.lot-form', [
            'merchants' => $merchantsQuery->get()
        ]);
    }
}
