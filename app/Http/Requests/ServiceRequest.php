<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    private ?string $serviceId;

    public function setServiceId(?string $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'merchant_id' => 'required|exists:merchants,id',
            'price_per_hectare' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El campo nombre no puede tener más de 255 caracteres',
            'merchant_id.required' => 'La empresa es obligatoria',
            'price_per_hectare.required' => 'El precio por hectárea es obligatorio',
            'price_per_hectare.min' => 'El precio por hectárea debe ser mayor que 0',
        ];
        }
}
