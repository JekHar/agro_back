<?php

namespace App\Livewire;

use App\Http\Requests\ServiceRequest;
use App\Models\Merchant;
use App\Models\Service;
use App\Types\MerchantType;
use Livewire\Component;

class ServiceForm extends Component
{
    public $service;

    public $serviceId;

    public $name;

    public $merchant_id;

    public $price_per_hectare;

    public bool $isModal = false;

    public $merchants;

    public $isEditing = false;

    public $userRole;

    protected function rules()
    {
        $serviceRequest = new ServiceRequest;
        $serviceRequest->setServiceId($this->serviceId);

        return [
            'rules' => $serviceRequest->rules(),
            'messages' => $serviceRequest->messages()
        ];
    }

    public function mount($serviceId = null)
    {
        $this->userRole = auth()->user()->hasRole('Admin') ? 'Admin' : 'Tenant';


        if ($this->userRole === 'Admin') {
            $this->merchants = Merchant::where('merchant_type', 'client')
                ->pluck('business_name', 'id');
        } else {
            $this->merchant_id = auth()->user()->merchant_id;
        }

        if ($serviceId) {
            $this->isEditing = true;
            $this->serviceId = $serviceId;
            $this->service = Service::find($serviceId);
            $this->name = $this->service->name;


            if ($this->userRole === 'Admin') {
                $this->merchant_id = $this->service->merchant_id;
            }

            $this->price_per_hectare = $this->service->price_per_hectare;
        }
    }

    public function save()
    {
        $validatedData = $this->validate(
            $this->rules()['rules'],
            $this->rules()['messages']
        );

        if ($this->userRole === 'Tenant') {
            $validatedData['merchant_id'] = auth()->user()->merchant_id;
        }

        try {
            if ($this->isEditing) {
                $this->service->update($validatedData);
                $message = 'Servicio modificado exitosamente';
            } else {
                $service = Service::create($validatedData);
                $message = 'Servicio creado exitosamente';

                // If in modal mode, emit event to parent component
                if ($this->isModal) {
                    $this->dispatch('entityCreated', 'service', $service->id);
                    return;
                }
            }

            $this->dispatch('swal', [
                'title' => 'Éxito!',
                'message' => $message,
                'icon' => 'success',
                'redirect' => route('services.index'),
            ]);

            if ($this->isModal) {
                $this->dispatch('close-modal');
            }

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => ('Error'),
                'message' => ('Ocurrió un error al procesar la solicitud'),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.service-form');
    }
}
