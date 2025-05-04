<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AircraftRequest extends FormRequest
{
    private ?string $aircraftId;

    public function setAircraftId(?string $aircraftId): void
    {
        $this->aircraftId = $aircraftId;
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
            'merchant_id' => 'required|exists:merchants,id',
            'brand' => 'required|string|max:100',
            'alias' => 'required|string|max:100',
            'models' => 'required|string|max:100',
            'manufacturing_year' => 'nullable|numeric|min:0',
            'acquisition_date' => 'nullable|date',
            'working_width' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'merchant_id.required' => 'El campo Empresa es obligatorio.',
            'brand.required' => 'El campo Marca es obligatorio.',
            'brand.max' => 'El campo Marca debe tener como máximo 100 caracteres.',
            'models.required' => 'El campo Modelo es obligatorio.',
            'models.max' => 'El campo Modelo debe tener como máximo 100 caracteres.',
            'manufacturing_year.min' => 'El campo Año de Fabricación debe ser como mínimo 0.',
            'acquisition_date.required' => 'El campo Fecha de Adquisición es obligatorio.',
            'working_width.required' => 'El campo Ancho de Trabajo es obligatorio.',
            'working_width.min' => 'El campo Ancho de Trabajo debe ser mayor que 0.',
            ];
    }

}
