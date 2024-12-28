<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->userId;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'merchant_id' => ['required', 'exists:merchants,id'],
            'role' => [
                'required',
                'exists:roles,id',
                Rule::in(
                    \Spatie\Permission\Models\Role::whereIn('name', ['Pilot', 'Ground Support'])
                        ->pluck('id')
                        ->toArray()
                )
            ],
        ];

        if (!$userId) {
            $rules['email'] = ['required', 'email', 'unique:users,email'];
            $rules['password'] = ['required', 'min:8', 'confirmed'];
            $rules['password_confirmation'] = ['required'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'merchant_id.required' => 'Please select a merchant.',
            'role.required' => 'Please select a role.',
        ];
    }
}