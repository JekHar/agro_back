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
            'sku' => 'required|string|max:255|unique:products,sku,' . $productId,
            'category_id' => 'required|exists:categories,id',
            'merchant_id' => 'required|exists:merchants,id',
            'concentration' => 'required|numeric|min:0, max:100',
            'dosage_per_hectare' => 'required|numeric|min:0',
            'application_volume_per_hectare' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0'
        ];
    }
}
