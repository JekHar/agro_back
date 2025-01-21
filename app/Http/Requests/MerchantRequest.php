<?php

namespace App\Http\Requests;

use App\Types\MerchantType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MerchantRequest extends FormRequest
{
    protected ?int $merchantId = null;

    protected bool $isClient = false;

    public function setMerchantId(?int $merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'business_name' => ['required', 'string', 'max:80'],
            'trade_name' => ['nullable', 'string', 'max:80'],
            'fiscal_number' => [
                'max:999999999999',
                'required',
                'numeric',
                Rule::unique('merchants', 'fiscal_number')
                    ->ignore($this->merchantId),
            ],
            'merchant_id' => $this->isClientRoute()
                ? ['required', 'exists:merchants,id']
                : ['nullable', 'exists:merchants,id'],
            'merchant_type' => ['required', new Enum(MerchantType::class)],
            'email' => ['required', 'email', 'max:80'],
            'phone' => ['required', 'string', 'max:20'],
            'locality' => ['required', 'string', 'max:120'],
            'address' => ['required', 'string', 'max:120'],
        ];

        if ($this->isClientRoute()) {
            $rules['main_activity'] = ['nullable', 'string', 'max:60'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'business_name.required' => 'La razón social es requerida.',
            'business_name.max' => 'La razón social no puede tener más de 80 caracteres.',
            'fiscal_number.required' => 'El CUIT/CUIL es requerido.',
            'fiscal_number.unique' => 'Este CUIT/CUIL ya está registrado.',
            'fiscal_number.max' => 'El CUIT/CUIL no puede ser mas de 13 caracteres.',
            'email.required' => 'El correo electrónico es requerido.',
            'email.email' => 'Por favor ingrese una dirección de correo electrónico válida.',
            'email.max' => 'El correo electrónico no puede tener más de 80 caracteres.',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'locality.max' => 'La localidad no puede tener más de 120 caracteres.',
            'address.max' => 'La dirección no puede tener más de 120 caracteres.',
            'main_activity.max' => 'La actividad principal no puede tener más de 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'business_name' => 'razón social',
            'trade_name' => 'nombre fantasía',
            'fiscal_number' => 'CUIT/CUIL',
            'email' => 'correo electrónico',
            'phone' => 'teléfono',
            'locality' => 'localidad',
            'address' => 'dirección',
            'main_activity' => 'actividad principal',
        ];
    }

    public function setIsClient(bool $isClient)
    {
        $this->isClient = $isClient;
    }

    private function isClientRoute(): bool
    {
        if ($this->isClient) {
            return true;
        }

        return $this->routeIs('clients.merchants.*');
    }
}
