<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Merchant;
use App\Types\MerchantType;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

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
    public $roles;
    public $merchants;
    public $isEditing = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'merchant_id' => 'required|exists:merchants,id',
            'role' => 'required|exists:roles,id', // Changed to validate against role IDs
        ];

        if (!$this->isEditing) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    public function mount($userId = null)
    {
        $this->merchants = Merchant::where('merchant_type', MerchantType::CLIENT)
            ->pluck('business_name', 'id');

        // Get only Pilot and Ground Support roles
        $this->roles = Role::whereIn('name', ['Pilot', 'Ground Support'])
            ->pluck('name', 'id');

        if ($userId) {
            $this->isEditing = true;
            $this->userId = $userId;
            $this->user = User::find($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->merchant_id = $this->user->merchant_id;

            // Get the role ID instead of name
            $userRole = $this->user->roles()->first();
            $this->role = $userRole ? $userRole->id : null;
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        if ($this->isEditing) {
            $this->user->update([
                'name' => $validatedData['name'],
                'merchant_id' => $validatedData['merchant_id'],
            ]);

            // Get the role by ID and sync
            $role = Role::find($validatedData['role']);
            if ($role) {
                $this->user->syncRoles([$role->name]);
            }
        } else {
            $userData = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'merchant_id' => $validatedData['merchant_id'],
                'password' => Hash::make($validatedData['password'])
            ];

            $user = User::create($userData);

            // Get the role by ID and assign
            $role = Role::find($validatedData['role']);
            if ($role) {
                $user->assignRole($role->name);
            }
        }

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
