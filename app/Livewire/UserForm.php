<?php

namespace App\Livewire;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserForm extends Component
{
    public ?string $userId;
    public ?User $user = null;
    public bool $isModal = false;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';
    public ?int $merchant_id = null;
    public bool $isEditing = false;

    public function mount($userId = null)
    {
        $this->userId = $userId;

        if ($this->userId) {
            $this->user = User::findOrFail($this->userId);
            $this->isEditing = true;
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->merchant_id = $this->user->merchant_id;
            $this->role = $this->user->roles->first()?->name ?? '';
        }
    }

    #[Computed]
    public function roles(): array
    {
        return [
            'Pilot' => __('Pilot'),
            'Apoyo a Tierra' => __('Apoyo a Tierra'),
        ];
    }

    #[Computed]
    public function merchants()
    {
        return Merchant::all();
    }

    public function save(): void
    {
        $request = new UserRequest();
        $validated = $this->validate($request->rules(), $request->messages());

        try {
            $userData = [
                'name' => $validated['name'],
                'merchant_id' => $validated['merchant_id'],
            ];

            if (!$this->isEditing) {
                $userData['email'] = $validated['email'];
                $userData['password'] = Hash::make($validated['password']);
            }

            if ($this->user && $this->user->exists) {
                $this->user->update($userData);
                $this->user->syncRoles([$validated['role']]);
                $message = __('Usuario actualizado exitosamente');
            } else {
                $user = User::create($userData);
                $user->assignRole($validated['role']);
                $message = __('Usuario creado exitosamente');
            }

            $this->dispatch('user-saved');

            $this->dispatch('swal', [
                'title' => __('¡Éxito!'),
                'message' => $message,
                'icon' => 'success',
                'redirect' => route('users.index'),
            ]);

            if ($this->isModal) {
                $this->dispatch('close-modal');
            }

            if (!$this->user || !$this->user->exists) {
                $this->reset(['name', 'email', 'password', 'password_confirmation', 'role', 'merchant_id']);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => __('Error'),
                'message' => __('Ocurrió un error al procesar la solicitud'),
                'icon' => 'error',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user-form');
    }
}
