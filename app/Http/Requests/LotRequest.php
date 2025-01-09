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
            'number' => 'required|numeric|max:255',
            'hectares' => 'required|numeric|min:0',
            'coordinates' => 'required|array|min:3',
            'coordinates.*.lat' => 'required|numeric|between:-90,90',
            'coordinates.*.lng' => 'required|numeric|between:-180,180',
        ];
    }

    public function messages()
    {
        return [
            'merchant_id.required' => 'Please select a merchant.',
            'merchant_id.exists' => 'The selected merchant is invalid.',
            'number.required' => 'The number field is required.',
            'number.numeric' => 'The number must be a number.',
            'number.max' => 'The number may not be greater than 255 characters.',
            'hectares.required' => 'The hectares field is required.',
            'hectares.numeric' => 'The hectares must be a number.',
            'hectares.min' => 'The hectares must be at least 0.',
            'coordinates.required' => 'The coordinates field is required.',
            'coordinates.array' => 'The coordinates must be an array.',
            'coordinates.min' => 'The coordinates must have at least 3 items.',
            'coordinates.*.lat.required' => 'The coordinates.lat field is required.',
            'coordinates.*.lat.numeric' => 'The coordinates.lat must be a number.',
            'coordinates.*.lat.between' => 'The coordinates.lat must be between -90 and 90.',
            'coordinates.*.lng.required' => 'The coordinates.lng field is required.',
            'coordinates.*.lng.numeric' => 'The coordinates.lng must be a number.',
            'coordinates.*.lng.between' => 'The coordinates.lng must be between -180 and 180.',
        ];
    }
}
