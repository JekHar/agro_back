<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotRequest extends FormRequest
{
    protected $lotId;

    public function setLotId($Id)
    {
        $this->lotId = $Id;

        return $this;
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'merchant_id' => 'required|exists:merchants,id',
            'number' => 'required|numeric|min:0|max:255',
            'hectares' => 'required|numeric|min:0, max:100',
            'coordinates' => 'required|array|min:3',
            'coordinates.*.lat' => 'required|numeric|between:-90,90',
            'coordinates.*.lng' => 'required|numeric|between:-180,180',
        ];
    }

    public function messages()
    {
        return [
            'merchant_id.required' => 'Por favor, elija un cliente.',
            'number.required' => 'El número de Lote es requerido.',
            'number.max' => 'El número no debe se mayor a 255 caracteres.',
            'number.min' => 'El número debe se mayor que 0.',
            'hectares.required' => 'La cantidad de hectáreas es requerida.',
            'hectares.min' => 'La cantidad de hectáreas debe ser mayor que 0.',
            'coordinates.required' => 'El campo de Coordenadas es requerido',
            'coordinates.min' => 'El campo de coordenadas debe tener al menos 3 puntos.',
            'coordinates.*.lat.required' => 'El campo de Latitud es requerido',
            'coordinates.*.lat.between' => 'La latitud debe ser un número entre -90 y 90.',
            'coordinates.*.lng.required' => 'El campo de Longitud es requerido',
            'coordinates.*.lng.between' => 'La longitud debe ser un número entre -180 y 180.',
        ];
    }
}
