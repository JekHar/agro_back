<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'merchant_id' => ['required', 'exists:merchants,id'],
            'role' => ['required', Rule::in(['Piloto', 'Apoyo a Tierra'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es requerido',
            'merchant_id.required' => 'El ID del comerciante es requerido',
            'merchant_id.exists' => 'El comerciante seleccionado no existe',
            'role.required' => 'El rol es requerido',
            'role.in' => 'El rol debe ser Piloto o Apoyo a Tierra',
        ];
    }
