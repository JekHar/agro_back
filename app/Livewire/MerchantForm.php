<?php

namespace App\Livewire;

use App\Models\Merchant;
use App\Types\MerchantType;
use Livewire\Component;
use App\Http\Requests\MerchantRequest;


class MerchantForm extends Component
{
    public $merchant;
    public $business_name;
    public $merchantId;
    public $trade_name;
    public $fiscal_number;
    public $main_activity;
    public $email;
    public $phone;
    public $merchant_type;
    public $merchant_id ;
    public $locality;
    public $address;
    public $isEditing = false;
    public bool $isClient;
    public bool $showMainActivity;
    public array $tenants = [];


    protected function rules()
    {
        $merchantRequest = new MerchantRequest();
        if ($this->merchant && $this->merchant->exists) {
            $merchantRequest->setMerchantId($this->merchant->id);

        }
        $merchantRequest->setIsClient($this->isClient);
        return $merchantRequest->rules();
    }

    public function mount(bool $isClient = false, $merchantId = null)
    {
        if((auth()->user()->hasRole('Admin') && $isClient) ||  (!auth()->user()->hasRole('Tenant') && !auth()->user()->hasRole('Admin'))) {
            $this->tenants = Merchant::where('merchant_type', MerchantType::TENANT)
                ->pluck('business_name', 'id')->toArray();
        }
    

        if ($merchantId) {
            $this->isEditing = true;
            $this->merchantId = $merchantId;
            $this->merchant = Merchant::find($merchantId);
            $this->business_name = $this->merchant->business_name;
            $this->trade_name = $this->merchant->trade_name;
            $this->fiscal_number = $this->merchant->fiscal_number;
            $this->main_activity = $this->merchant->main_activity;
            $this->email = $this->merchant->email;
            $this->phone = $this->merchant->phone;
            $this->merchant_type = $this->merchant->merchant_type 
            ? $this->merchant->merchant_type 
            : ($isClient ? MerchantType::CLIENT->value : MerchantType::TENANT->value);
            $this->locality = $this->merchant->locality;
            $this->address = $this->merchant->address;

            if (auth()->user()->hasRole('Admin')) {
                $this->merchant_id = null;
            } elseif (auth()->user()->hasRole('Tenant')) {
                $this->merchant_id = auth()->id();
            }
        } else {
            $this->merchant_type = $isClient ? MerchantType::CLIENT->value : MerchantType::TENANT->value;
            if (auth()->user()->hasRole('Tenant')) {
                $this->merchant_id = auth()->id();
            }
        }

        $this->isClient = $isClient;
        $this->showMainActivity = $isClient;
    }


    public function save()
    {

        $validated = $this->validate();

        try {

            if (!auth()->user()->can('clients.merchants.create')) {
                throw new \Exception('No tienes permiso para crear clientes');
            }
            if(auth()->user()->hasRole('Admin')) {
                $validated['merchant_id'] = $this->merchant_id;
                $validated['merchant_type'] = $this->merchant_type;
            }
            if (auth()->user()->hasRole('Tenant')) {
                $validated['merchant_id'] = auth()->user()->id;
                $validated['merchant_type'] = $this->merchant_type;

            }
            if ($this->merchant && $this->merchant->exists) {
                $this->merchant->update($validated);
                $message = 'Service created successfully';
            } else {

                Merchant::create($validated);
                $message = 'Service created successfully';
            }

            
            
            $route = $validated['merchant_type'] === MerchantType::CLIENT->value
            ? 'merchants.clients.merchants.index'
            : 'merchants.tenants.merchants.index';

            
            $this->dispatch('swal', [
                'title' => 'Success',
                'message' => $message,
                'icon' => 'success',
                'redirect' => route($route)
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => ('Error'),
                'message' => $e->getMessage(),
                'icon' => 'error',
            ]);
        }
    }



    public function render()
    {
        return view('livewire.merchant-form', [
            'merchantTypes' => MerchantType::cases()
        ]);
    }
}
