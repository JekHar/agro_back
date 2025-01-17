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
            'sku' => 'string|max:100|unique:products,sku,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'merchant_id' => 'required|exists:merchants,id',
            'concentration' => 'required|numeric|min:0, max:100',
            'dosage_per_hectare' => 'required|numeric|min:0',
            'application_volume_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.max' => 'El nombre del producto no puede tener más de 255 caracteres.', 
            'sku.max' => 'El SKU no puede tener más de 100 caracteres.',
            'sku.unique' => 'El SKU ya está registrado. Por favor, utilice uno diferente.',
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
