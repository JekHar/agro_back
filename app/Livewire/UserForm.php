<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Merchant;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm extends Component
{
    public $user;
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $merchant_id;
    public $role;
    public $merchants;
    public $isEditing = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'merchant_id' => 'required|exists:merchants,id',
            'role' => 'required|in:Piloto,Apoyo a Tierra'
        ];

        if (!$this->isEditing) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'name.required' => 'El nombre es requerido',
            'email.required' => 'El correo electrónico es requerido',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es requerida',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'merchant_id.required' => 'El comerciante es requerido',
            'merchant_id.exists' => 'El comerciante seleccionado no existe',
            'role.required' => 'El rol es requerido',
            'role.in' => 'El rol debe ser Piloto o Apoyo a Tierra'
        ];
    }

    public function mount($userId = null)
    {
        $this->merchants = Merchant::pluck('name', 'id');

        if ($userId) {
            $this->isEditing = true;
            $this->userId = $userId;
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->merchant_id = $this->user->merchant_id;
            $this->role = $this->user->roles->first()?->name;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->user->update($validatedData);
            $this->user->syncRoles([$validatedData['role']]);
        } else {
            $userData = array_merge($validatedData, [
                'password' => Hash::make($validatedData['password'])
            ]);

            $user = User::create($userData);
            $user->assignRole($validatedData['role']);
        }

        session()->flash(
            'success',
            $this->isEditing ? 'Usuario actualizado exitosamente.' : 'Usuario creado exitosamente.'
        );

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user-form', [
            'roles' => [
                'Piloto' => 'Piloto',
                'Apoyo a Tierra' => 'Apoyo a Tierra'
            ]
        ]);
    }
}
