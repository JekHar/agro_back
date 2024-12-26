<div>
    <form wire:submit="save" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
            <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        @if(!$isEditing)
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <input type="email" wire:model="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Contraseña') }}</label>
            <input type="password" wire:model="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('Confirmar Contraseña') }}</label>
            <input type="password" wire:model="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        @endif

        <div>
            <label for="merchant_id" class="block text-sm font-medium text-gray-700">{{ __('Comerciante') }}</label>
            <select wire:model="merchant_id" id="merchant_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">{{ __('Seleccionar comerciante') }}</option>
                @foreach($this->merchants as $merchant)
                <option value="{{ $merchant->id }}">{{ $merchant->name }}</option>
                @endforeach
            </select>
            @error('merchant_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700">{{ __('Rol') }}</label>
            <select wire:model="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">{{ __('Seleccionar rol') }}</option>
                @foreach($this->roles as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ $isEditing ? __('Actualizar') : __('Crear') }}
            </button>
        </div>
    </form>
</div>