<?php

namespace App\Livewire;

use App\Models\Merchant;
use App\Types\MerchantType;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

class MerchantForm extends Component
{
    public ?string $business_name = '';
    public ?string $trade_name = '';
    public ?string $fiscal_number = '';
    public ?string $main_activity = '';
    public ?string $email = '';
    public ?string $phone = '';
    public MerchantType $merchant_type;
    public ?string $locality = '';
    public ?string $address = '';
    public ?Merchant $merchant = null;
    public bool $isClient;
    public bool $showMainActivity;

    protected $rules = [
        'business_name' => 'required|string|max:255',
        'trade_name' => 'required|string|max:255',
        'fiscal_number' => 'required|string|max:20',
        'main_activity' => 'nullable|string',
        'email' => 'required|email',
        'phone' => 'required|string',
        'merchant_type' => 'required',
        'locality' => 'required|string',
        'address' => 'required|string',
    ];


    public function mount(?Merchant $merchant = null, bool $isClient = false)
    {
        if ($merchant) {
            $this->merchant = $merchant;
            $this->business_name = $merchant->business_name;
            $this->trade_name = $merchant->trade_name;
            $this->fiscal_number = $merchant->fiscal_number;
            $this->main_activity = $merchant->main_activity;
            $this->email = $merchant->email;
            $this->phone = $merchant->phone;
            $this->merchant_type = $merchant->merchant_type ?? ($isClient ? MerchantType::CLIENT : MerchantType::TENANT);
            $this->locality = $merchant->locality;
            $this->address = $merchant->address;
        } else {
            $this->merchant_type = $isClient ? MerchantType::CLIENT : MerchantType::TENANT;
        }

        $this->isClient = $isClient;
        $this->showMainActivity = $isClient;
    }


    public function save()
    {
        $validated = $this->validate();
        $validated['merchant_type'] = MerchantType::from($validated['merchant_type']->value);

        try {
            if ($this->merchant && $this->merchant->exists) {
                $this->merchant->update($validated);
            } else {
                Merchant::create($validated);
            }

            $route = $validated['merchant_type'] === MerchantType::CLIENT
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
