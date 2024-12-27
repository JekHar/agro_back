<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Service;
use App\Models\Merchant;
use App\Types\MerchantType;

class ServiceForm extends Component
{
    public $service;
    public $serviceId;
    public $name;
    public $description;
    public $merchant_id;
    public $price_per_hectare;
    public $merchants;
    public $isEditing = false;

    protected function rules()
    {
        return [
            'name' => $this->isEditing 
                ? 'required|string|max:255'
                : 'required|string|max:255|unique:services,name',
            'description' => 'required|string',
            'merchant_id' => 'required|exists:merchants,id',
            'price_per_hectare' => 'required|numeric|min:0',
        ];
    }
    
    public function mount($serviceId = null)
    {
        $this->merchants = Merchant::where('merchant_type', MerchantType::CLIENT)
            ->pluck('business_name', 'id');

        if ($serviceId) {
            $this->isEditing = true;
            $this->serviceId = $serviceId;
            $this->service = Service::find($serviceId);
            $this->name = $this->service->name;
            $this->description = $this->service->description;
            $this->merchant_id = $this->service->merchant_id;
            $this->price_per_hectare = $this->service->price_per_hectare;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->service->update($validatedData);
        } else {
            Service::create($validatedData);
        }

        return redirect()->route('services.index');
    }

    public function render()
    {
        return view('livewire.service-form');
    }
}