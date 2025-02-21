<div class="block block-rounded">
    <div class="block-content">
        <form wire:submit.prevent="save">
            <div class="row">
                <!-- Nombre -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label" for="name"> 
                            <span class="text-danger">*</span> {{ __('crud.products.fields.name') }}
                        </label>
                        <input type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" wire:model="name" 
                            placeholder="Ingrese el nombre del producto">
                        @error('name')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- Categoría -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label" for="category_id"> 
                            <span class="text-danger">*</span> {{ __('crud.products.fields.category') }}
                        </label>
                        <select class="form-select js-select2 @error('category_id') is-invalid @enderror" 
                            id="category_id" wire:model="category_id">
                            <option value="">{{ __('crud.products.select_category') }}</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Comerciante -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label" for="merchant_id"> 
                            <span class="text-danger">*</span> {{ __('crud.services.fields.merchant') }}
                        </label>
                        <select class="form-select js-select2 @error('merchant_id') is-invalid @enderror" 
                            id="merchant_id" wire:model="merchant_id">
                            <option value="">{{ __('crud.services.select_merchant') }}</option>
                            @foreach ($merchants as $id => $businessName)
                                <option value="{{ $id }}">{{ $businessName }}</option>
                            @endforeach
                        </select>
                        @error('merchant_id')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Concentración -->
                <div class="col-md-3">
                    <div class="mb-4">
                        <label class="form-label" for="concentration">
                            <span class="text-danger">*</span> {{ __('crud.products.fields.concentration') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                class="form-control @error('concentration') is-invalid @enderror" 
                                id="concentration" wire:model="concentration" 
                                step="0.01" placeholder="0.00">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('concentration')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- Dosis por Hectárea -->
                <div class="col-md-3">
                    <div class="mb-4">
                        <label class="form-label" for="dosage_per_hectare">
                            <span class="text-danger">*</span> {{ __('crud.products.fields.dosage_per_hectare') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                class="form-control @error('dosage_per_hectare') is-invalid @enderror" 
                                id="dosage_per_hectare" wire:model="dosage_per_hectare" 
                                step="0.01" placeholder="0.00">
                            <span class="input-group-text">L/ha</span>
                        </div>
                        @error('dosage_per_hectare')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- Volumen de Aplicación por Hectárea -->
                <div class="col-md-3">
                    <div class="mb-4">
                        <label class="form-label" for="application_volume_per_hectare">
                            <span class="text-danger">*</span> {{ __('crud.products.fields.application_volume_per_hectare') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                class="form-control @error('application_volume_per_hectare') is-invalid @enderror" 
                                id="application_volume_per_hectare" wire:model="application_volume_per_hectare" 
                                step="0.01" placeholder="0.00">
                            <span class="input-group-text">L/ha</span>
                        </div>
                        @error('application_volume_per_hectare')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- Stock -->
                <div class="col-md-3">
                    <div class="mb-4">
                        <label class="form-label" for="stock">
                            <span class="text-danger">*</span> {{ __('crud.products.fields.stock') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                class="form-control @error('stock') is-invalid @enderror" 
                                id="stock" wire:model="stock" 
                                step="1" placeholder="0">
                            <span class="input-group-text">unidades</span>
                        </div>
                        @error('stock')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                        <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                        {{ $isEditing ? __('crud.products.actions.edit') : __('crud.products.add') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
