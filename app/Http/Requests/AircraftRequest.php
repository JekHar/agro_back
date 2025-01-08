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
            'brand' => 'required|string|max:255',
            'models' => 'required|string|max:255',
            'manufacturing_year' => 'nullable|numeric|min:0',
            'acquisition_date' => 'required|date',
            'working_width' => 'required|numeric|min:0',
        ];
    }
}
