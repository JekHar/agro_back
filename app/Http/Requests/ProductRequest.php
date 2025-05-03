<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    protected ?string $productId = null;

    public function setProductId(?string $productId): void
    {
        $this->productId = $productId;
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
        $productId = $this->productId;

        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'merchant_id' => 'nullable|exists:merchants,id',
            'stock' => 'nullable|numeric|min:0',
            'dosage_per_hectare' => 'nullable|numeric|min:0',
            'commercial_brand' => 'nullable|string|max:100',
            'liters_per_can' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre del producto no puede tener más de 255 caracteres.', 
            'category_id.required' => 'La categoría es obligatoria.',
            'merchant_id.required' => 'El campo Empresa es obligatorio.',
            'concentration.required' => 'La concentración es obligatoria.',
            'concentration.min' => 'La concentración no puede ser menor que 0.',
            'concentration.max' => 'La concentración no puede ser mayor que 100.',
            'dosage_per_hectare.required' => 'La dosis por hectárea es obligatoria.',
            'dosage_per_hectare.min' => 'La dosis por hectárea no puede ser menor que 0.',
            'application_volume_per_hectare.required' => 'El volumen de aplicación por hectárea es obligatorio.',
            'application_volume_per_hectare.min' => 'El volumen de aplicación por hectárea no puede ser menor que 0.',
            'stock.required' => 'El stock es obligatorio.',
            'stock.min' => 'El stock no puede ser menor que 0.',
        ];
    }
}
