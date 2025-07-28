<?php

namespace App\Livewire;

use App\Http\Requests\UserRequest;
use App\Models\Merchant;
use App\Models\User;
use App\Types\MerchantType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
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
        $userRequest = new UserRequest;
        $userRequest->setuserId($this->userId);

        return $userRequest->rules();
    }

    public function mount($userId = null, $role = null)
    {
        $this->merchants = Merchant::where('merchant_type', MerchantType::TENANT)
            ->pluck('business_name', 'id');
        if (auth()->user()->hasRole('Admin')) {
            $this->roles = Role::whereIn('name', ['Admin', 'Tenant', 'Pilot', 'Ground Support'])
                ->pluck('name', 'id');
        } else {
            $this->roles = Role::whereIn('name', ['Pilot', 'Ground Support'])
                ->pluck('name', 'id');
        }

        // If role is passed (from modal), set it automatically
        if ($role && $this->isModal) {
            $roleModel = Role::where('name', $role)->first();
            if ($roleModel) {
                $this->role = $roleModel->id;
            }
        }

        // Set tenant_id for modal mode
        if ($this->isModal && auth()->user()->hasRole('Tenant')) {
            $this->merchant_id = auth()->user()->merchant_id;
        }

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
        if (auth()->user()->hasRole('Tenant')) {
            $this->merchant_id = auth()->user()->merchant_id;
        }

        $validatedData = $this->validate();
        try {

            if ($this->isEditing) {
                $this->user->update([
                    'name' => $validatedData['name'],
                    'merchant_id' => $validatedData['merchant_id'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                ]);

                if ($validatedData['role']) {
                    $this->user->syncRoles([Role::find($validatedData['role'])->name]);
                }
            } else {
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'merchant_id' => $validatedData['merchant_id'],
                    'password' => Hash::make($validatedData['password']),
                ]);

                if ($validatedData['role']) {
                    $user->assignRole(Role::find($validatedData['role'])->name);
                }

                // If in modal mode, emit event to parent component
                if ($this->isModal) {
                    $roleName = Role::find($validatedData['role'])->name;
                    $entityType = $roleName === 'Pilot' ? 'pilot' : 'ground_support';
                    $this->dispatch('entityCreated', $entityType, $user->id);
                    return;
                }
            }

            $this->dispatch('swal', [
                'title' => 'Éxito!',
                'message' => __($this->isEditing ? 'crud.users.actions.updated' : 'crud.users.actions.created'),
                'icon' => 'success',
                'redirect' => route('users.index'),
            ]);

            if ($this->isModal) {
                $this->dispatch('close-modal');
            }

            if (! $this->isEditing) {
                $this->reset(['name', 'email', 'password', 'password_confirmation', 'merchant_id', 'role']);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());

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
