<?php

namespace App\Livewire;

use App\Http\Requests\AircraftRequest;
use Livewire\Component;
use App\Models\Aircraft;
use App\Models\Merchant;
use App\Types\MerchantType;

class AircraftForm extends Component
{
    public $aircraft;
    public $aircraftId;
    public $merchant_id;
    public $brand;
    public $models;
    public $manufacturing_year;
    public $acquisition_date;
    public $working_width;
    public $merchants;
    public $isEditing = false;

    protected function rules()
    {
        $aircraftRequest = new AircraftRequest();
        $aircraftRequest->setAircraftId($this->aircraftId);
        return $aircraftRequest->rules();
    }

    public function mount($aircraftId = null)
    {
        $this->merchants = Merchant::where('merchant_type', MerchantType::CLIENT)
            ->pluck('business_name', 'id');

        if ($aircraftId) {
            $this->isEditing = true;
            $this->aircraftId = $aircraftId;
            $this->aircraft = Aircraft::find($aircraftId);
            $this->merchant_id = $this->aircraft->merchant_id;
            $this->brand = $this->aircraft->brand;
            $this->models = $this->aircraft->models;
            $this->manufacturing_year = $this->aircraft->manufacturing_year;
            $this->acquisition_date = $this->aircraft->acquisition_date;
            $this->working_width = $this->aircraft->working_width;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        try {
            if ($this->isEditing) {
                $this->aircraft->update($validatedData);
                $message = 'Avion modificado exitosamente';
            } else {
                Aircraft::create($validatedData);
                $message = 'Avion creado exitosamente';
            }

            $this->dispatch('swal', [
                'title' => 'Éxito!',
                'message' => $message,
                'icon' => 'success',
                'redirect' => route('aircrafts.index')
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('swal', [
                'title' => ('Error'),
                'message' => ('Ocurrió un error al procesar la solicitud'),
                'icon' => 'error',
            ]);
        }

        // return redirect()->route('aircrafts.index');
    }

    public function render()
    {
        return view('livewire.aircraft-form');
    }
}
