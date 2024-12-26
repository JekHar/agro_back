<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MerchantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'business_name' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'fiscal_number' => 'required|string|max:50|unique:merchants,fiscal_number,' . $this->merchant,
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'locality' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ];

        if ($this->isClientRoute()) {
            $rules['main_activity'] = 'nullable|string|max:255';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
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

    /**
     * Check if the current route is for Client.
     */
    private function isClientRoute(): bool
    {
        return $this->routeIs('merchants.clients.*');
    }
}
