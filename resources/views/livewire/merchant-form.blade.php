<div>
    <form wire:submit="save" class="space-y-4">
        <div class="block block-rounded">
            <div class="block-content">
                <!-- Fila 1: Nombres -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Nombre Comercial') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-building"></i>
                            </span>
                            <input type="text"
                                class="form-control @error('business_name') is-invalid @enderror"
                                wire:model="business_name"
                                placeholder="{{ __('Ingrese nombre comercial') }}">
                            @error('business_name')
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
                                class="form-control @error('trade_name') is-invalid @enderror"
                                wire:model="trade_name"
                                placeholder="{{ __('Ingrese nombre fiscal') }}">
                            @error('trade_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fila 2: Empresa y CUIT -->
                <div class="row mb-4">
                    @if(!auth()->user()->hasRole('Tenant'))
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Empresa') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-building"></i>
                            </span>
                            <select id="merchant_id" class="form-select @error('merchant_id') is-invalid @enderror" wire:model="merchant_id">
                                <option value="">{{ __('Seleccione empresa') }}</option>
                                @foreach($tenants as $id => $businessName)
                                <option value="{{$id}}">{{ $businessName }}</option>
                                @endforeach
                            </select>
                            @error('merchant_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <label class="form-label">{{ __('CUIT') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-id-card"></i>
                            </span>
                            <input type="number"
                                class="form-control @error('fiscal_number') is-invalid @enderror"
                                wire:model="fiscal_number"
                                placeholder="{{ __('Ingrese CUIT') }}">
                            @error('fiscal_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fila 3: Actividad, Email y Teléfono -->
                <div class="row mb-4">
                    @if($showMainActivity)
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Actividad Principal') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-briefcase"></i>
                            </span>
                            <input type="text"
                                class="form-control @error('main_activity') is-invalid @enderror"
                                wire:model="main_activity"
                                placeholder="{{ __('Ingrese actividad principal') }}">
                            @error('main_activity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    @endif

                    <div class="col-md-4">
                        <label class="form-label">{{ __('Email') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-envelope"></i>
                            </span>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                wire:model="email"
                                placeholder="{{ __('correo@ejemplo.com') }}">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">{{ __('Teléfono') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-phone"></i>
                            </span>
                            <input type="number"
                                class="form-control @error('phone') is-invalid @enderror"
                                wire:model="phone"
                                placeholder="{{ __('Teléfono') }}">
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Fila 4: Localidad y Dirección -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Localidad') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa fa-map-marker"></i>
                            </span>
                            <input type="text"
                                class="form-control @error('locality') is-invalid @enderror"
                                wire:model="locality"
                                placeholder="{{ __('Ingrese localidad') }}">
                            @error('locality')
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
                                class="form-control @error('address') is-invalid @enderror"
                                wire:model="address"
                                placeholder="{{ __('Ingrese dirección') }}">
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <input type="hidden" wire:model="merchant_type">

                <!-- Botones -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save me-1"></i>
                        {{ $isEditing ? __('Actualizar') : __('Crear') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>