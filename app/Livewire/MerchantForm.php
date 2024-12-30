<?php

namespace App\Livewire;

use App\Http\Controllers\MerchantController;
use App\Models\Merchant;
use App\Types\MerchantType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;
use App\Http\Requests\MerchantRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class MerchantForm extends Component
{
    public ?string $business_name = '';
    public ?string $trade_name = '';
    public ?string $fiscal_number = '';
    public ?string $main_activity = '';
    public ?string $email = '';
    public ?string $phone = '';
    public ?string $merchant_type;
    public ?int $merchant_id = null;
    public ?string $locality = '';
    public ?string $address = '';
    public ?Merchant $merchant = null;
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

    public function mount(?Merchant $merchant = null, bool $isClient = false)
    {
        $this->tenants = Merchant::where('merchant_type', MerchantType::TENANT)
        ->pluck('business_name', 'id')->toArray();

        if ($merchant) {
            $this->merchant = $merchant;
            $this->business_name = $merchant->business_name;
            $this->trade_name = $merchant->trade_name;
            $this->fiscal_number = $merchant->fiscal_number;
            $this->main_activity = $merchant->main_activity;
            $this->email = $merchant->email;
            $this->phone = $merchant->phone;
            $this->merchant_type = $merchant->merchant_type ?? ($isClient ? MerchantType::CLIENT->value : MerchantType::TENANT->value);
            $this->locality = $merchant->locality;
            $this->address = $merchant->address;
        } else {
            $this->merchant_type = $isClient ? MerchantType::CLIENT->value : MerchantType::TENANT->value;
        }

        $this->isClient = $isClient;
        $this->showMainActivity = $isClient;
    }


    public function save()
    {
        $validated = $this->validate();

        try {
            if (auth()->user()->hasRole('Tenant')) {
                $validated['merchant_id'] = auth()->user()->merchant_id;
            }
            if ($this->merchant && $this->merchant->exists) {
                $this->merchant->update($validated);
                $message = 'Service created successfully';
            } else {
                dd($validated);
                Merchant::create($validated);
                $message = 'Service created successfully';
            }

            $route = $validated['merchant_type'] === MerchantType::CLIENT->value
                ? 'merchants.clients.merchants.index'
                : 'merchants.tenants.merchants.index';

            return redirect()->route($route);
        } catch (\Exception $e) {
            session()->flash('error', __('Ocurrió un error al procesar la solicitud'));
        }
    }



    public function render()
    {
        return view('livewire.merchant-form', [
            'merchantTypes' => MerchantType::cases()
        ]);
    }
}
