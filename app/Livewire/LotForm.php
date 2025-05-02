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

    public $kmlFile;

    public $currentLotId;
    
    // Flag to track if we're in create mode
    public $isCreateMode = false;

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
            'hectares' => $this->hectares,
        ]);
    }

    #[On('updateCoordinates')]
    public function updateCoordinates($coords, $hectares)
    {
        $this->coordinates = $coords;
        $this->hectares = $hectares;
    }
    
    public function updatedMerchantId($value)
    {
        
        if ($this->isCreateMode && $value) {
            $this->number = $this->getNextLotNumber($value);
        }
    }

    protected function getNextLotNumber($merchantId)
    {
        $maxNumber = Lot::where('merchant_id', $merchantId)
            ->max('number');
        
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

            if (!$this->currentLotId) {
                $validatedData['number'] = $this->getNextLotNumber($validatedData['merchant_id']);
            }

            $lot->fill([
                'merchant_id' => $validatedData['merchant_id'],
                'number' => $validatedData['number'],
                'hectares' => $validatedData['hectares'],
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
                ]);
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