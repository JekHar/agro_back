<div>
    <form wire:submit="save" class="space-y-4">
        <div class="block">
            <div class="row items-center">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Nombre Comercial') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-building"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.business_name') is-invalid @enderror"
                            wire:model="merchant.business_name"
                            placeholder="{{ __('Ingrese nombre comercial') }}">
                        @error('merchant.business_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Nombre Fiscal') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-file-text"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.trade_name') is-invalid @enderror"
                            wire:model="merchant.trade_name"
                            placeholder="{{ __('Ingrese nombre fiscal') }}">
                        @error('merchant.trade_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('NIF/CIF') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-id-card"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.fiscal_number') is-invalid @enderror"
                            wire:model="merchant.fiscal_number"
                            placeholder="{{ __('Ingrese NIF/CIF') }}">
                        @error('merchant.fiscal_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                @if($showMainActivity)
                <div class="col-md-6">
                    <label class="form-label">{{ __('Actividad Principal') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-briefcase"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.main_activity') is-invalid @enderror"
                            wire:model="merchant.main_activity"
                            placeholder="{{ __('Ingrese actividad principal') }}">
                        @error('merchant.main_activity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                @endif

            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Email') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-envelope"></i>
                        </span>
                        <input type="email"
                            class="form-control @error('merchant.email') is-invalid @enderror"
                            wire:model="merchant.email"
                            placeholder="{{ __('correo@ejemplo.com') }}">
                        @error('merchant.email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Teléfono') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-phone"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.phone') is-invalid @enderror"
                            wire:model="merchant.phone"
                            placeholder="{{ __('Teléfono') }}">
                        @error('merchant.phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <input type="hidden" wire:model="merchant.merchant_type">
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Localidad') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-map-marker"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.locality') is-invalid @enderror"
                            wire:model="merchant.locality"
                            placeholder="{{ __('Ingrese localidad') }}">
                        @error('merchant.locality')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('Dirección') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa fa-location-arrow"></i>
                        </span>
                        <input type="text"
                            class="form-control @error('merchant.address') is-invalid @enderror"
                            wire:model="merchant.address"
                            placeholder="{{ __('Ingrese dirección') }}">
                        @error('merchant.address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 space-x-2">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-1"></i>
                {{ $merchant->exists ? __('Actualizar') : __('Crear') }}
            </button>

            @if($merchant->exists)
            <button type="button" class="btn btn-danger" wire:click="delete">
                <i class="fa fa-trash me-1"></i>
                {{ __('Eliminar') }}
            </button>
            @endif
        </div>
    </form>
</div>