<?php

namespace App\Livewire;

use App\Http\Requests\MerchantRequest;
use App\Models\Merchant;
use App\Models\User;
use App\Types\MerchantType;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

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

    public $merchant_id;

    public $locality;

    public $address;

    public $isEditing = false;

    public bool $isClient;

    public bool $showMainActivity;

    public array $tenants = [];

    protected function rules()
    {
        $merchantRequest = new MerchantRequest;
        if ($this->merchant && $this->merchant->exists) {
            $merchantRequest->setMerchantId($this->merchant->id);
        }
        $merchantRequest->setIsClient($this->isClient);

        return [
            'rules' => $merchantRequest->rules(),
            'messages' => $merchantRequest->messages()
        ];
    }

    public function mount(bool $isClient = false, $merchantId = null)
    {
        if ((auth()->user()->hasRole('Admin') && $isClient) || (! auth()->user()->hasRole('Tenant') && ! auth()->user()->hasRole('Admin'))) {
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
                $this->merchant_id = $this->merchant->merchant_id;
            } elseif (auth()->user()->hasRole('Tenant')) {
                $this->merchant_id = auth()->user()->merchant_id;
            } 
        } else {
            $this->merchant_type = $isClient ? MerchantType::CLIENT->value : MerchantType::TENANT->value;
            if (auth()->user()->hasRole('Tenant')) {
                $this->merchant_id = auth()->user()->merchant_id;
            }
        }
        $this->isClient = $isClient;
        $this->showMainActivity = $isClient;
    }

    public function save()
    {   
        try {
            if ($this->isClient && !auth()->user()->can('clients.merchants.create')) {
                throw new \Exception('No tienes permiso para crear clientes');
            } elseif (!$this->isClient && !auth()->user()->can('tenants.merchants.create')) {
                throw new \Exception('No tienes permiso para crear empresas');
            }
            
            $validated = $this->validate(
                $this->rules()['rules'],
                $this->rules()['messages']
            );
            
            $validated['business_name'] = $this->business_name;
            $validated['trade_name'] = $this->trade_name;
            $validated['fiscal_number'] = $this->fiscal_number;
            $validated['main_activity'] = $this->main_activity;
            $validated['email'] = $this->email;
            $validated['phone'] = $this->phone;
            $validated['locality'] = $this->locality;
            $validated['address'] = $this->address;

            if (auth()->user()->hasRole('Admin')) {
                $validated['merchant_id'] = $this->merchant_id;
                $validated['merchant_type'] = $this->merchant_type;
            } elseif (auth()->user()->hasRole('Tenant')) {
                $validated['merchant_id'] = auth()->user()->merchant_id;
                $validated['merchant_type'] = $this->merchant_type;
            }
            
            if ($this->merchant) {
                $this->merchant->update($validated);
                $merchant = $this->merchant;
                $message = $validated['merchant_type'] === 'client' ? 'Cliente modificado exitosamente' : 'Empresa modificada exitosamente';
            } else {
                $merchant = Merchant::create($validated);

                if ($validated['email']) {
                    $existingUser = User::where('email', $validated['email'])->first();
                    
                    if (!$existingUser) {

                        $roleName = 'Tenant';
                        if ($validated['merchant_type'] === 'client') {
                            $roleName = 'Client';
                        }
                        
                        $role = Role::where('name', $roleName)->first();
                        
                        if ($role) {
                            $user = User::create([
                                'name' => $validated['business_name'], 
                                'email' => $validated['email'],
                                'password' => Hash::make('password'), 
                                'merchant_id' => $merchant->id,
                            ]);
                            

                            $user->assignRole($role);
                        }
                    }
                }
                
                $message = $validated['merchant_type'] === 'client' ? 'Cliente creado exitosamente' : 'Empresa creada exitosamente';
            }

            $route = $this->isClient
                ? 'clients.merchants.index'
                : 'tenants.merchants.index';

            $this->dispatch('swal', [
                'title' => 'Éxito!',
                'message' => $message,
                'icon' => 'success',
                'redirect' => route($route),
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
            'merchantTypes' => MerchantType::cases(),
        ]);
    }
}