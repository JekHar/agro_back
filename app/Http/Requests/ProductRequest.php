<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'sku' => 'required|string|max:255|unique:products,sku,' . $this->productId,
            'category_id' => 'required|exists:categories,id',
            'concentration' => 'required|numeric|min:0',
            'dosage_per_hectare' => 'required|numeric|min:0',
            'application_volume_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ];
    }
}
