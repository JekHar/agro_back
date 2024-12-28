<?php

namespace App\Livewire;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Merchant;
use App\Types\MerchantType;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public $user;
    public $userId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $merchant_id = '';
    public $role = '';
    public $roles;
    public $merchants;
    public $isEditing = false;
    public bool $isModal = false;

    protected function rules()
    {
        return (new UserRequest())->rules();
    }

    public function mount($userId = null)
    {
        $this->merchants = Merchant::where('merchant_type', MerchantType::CLIENT)
            ->pluck('business_name', 'id');
        $this->roles = Role::whereIn('name', ['Pilot', 'Ground Support'])
            ->pluck('name', 'id');

        if ($userId) {
            $this->isEditing = true;
            $this->userId = $userId;
            $this->user = User::find($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->merchant_id = $this->user->merchant_id;
            $userRole = $this->user->roles()->first();
            $this->role = $userRole ? $userRole->id : null;
        }
    }

    public function save()
    {
        try {
            $validatedData = $this->validate();

            if ($this->isEditing) {
                $this->user->update([
                    'name' => $validatedData['name'],
                    'merchant_id' => $validatedData['merchant_id'],
                ]);

                if ($validatedData['role']) {
                    $this->user->syncRoles([Role::find($validatedData['role'])->name]);
                }
            } else {
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'merchant_id' => $validatedData['merchant_id'],
                    'password' => Hash::make($validatedData['password'])
                ]);

                if ($validatedData['role']) {
                    $user->assignRole(Role::find($validatedData['role'])->name);
                }
            }

            $this->dispatch('swal', [
                'title' => __('Success!'),
                'message' => __($this->isEditing ? 'crud.users.actions.updated' : 'crud.users.actions.created'),
                'icon' => 'success',
                'redirect' => route('users.index'),
            ]);

            if ($this->isModal) {
                $this->dispatch('close-modal');
            }

            if (!$this->isEditing) {
                $this->reset(['name', 'email', 'password', 'password_confirmation', 'merchant_id', 'role']);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => __('Error'),
                'message' => __('crud.users.actions.error'),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
