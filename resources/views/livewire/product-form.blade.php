<div class="block block-rounded">
    <div class="block-content">
        <form wire:submit.prevent="save">
            <div class="row">
                <!-- Nombre -->
                <div class="col-md-4">
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
                <!-- Marca comercial -->
                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="form-label" for="commercial_brand"> 
                            {{ __('crud.products.fields.brand') }}
                        </label>
                        <input type="text" 
                            class="form-control @error('commercial_brand') is-invalid @enderror" 
                            id="commercial_brand" wire:model="commercial_brand" 
                            placeholder="Ingrese la marca comercial">
                        @error('commercial_brand')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- Categoría -->
                <div class="col-md-4">
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
                <!-- Comerciante (solo visible para Admin) -->
                @if(!$isTenant)
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label" for="merchant_id"> 
                            {{ __('crud.services.fields.merchant') }}
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
                @else
                <!-- Si es tenant, mantenemos el merchant_id como un campo oculto -->
                <input type="hidden" wire:model="merchant_id">
                @endif
            </div>

            <div class="row">
                <!-- Dosis por Hectárea -->
                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="form-label" for="dosage_per_hectare">
                            {{ __('crud.products.fields.dosage_per_hectare') }}
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
                <!-- Litros por bidón -->
                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="form-label" for="liters_per_can">
                            {{ __('crud.products.fields.liters_per_container') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                class="form-control @error('liters_per_can') is-invalid @enderror" 
                                id="liters_per_can" wire:model="liters_per_can" 
                                step="0.01" placeholder="0.00">
                            <span class="input-group-text">L</span>
                        </div>
                        @error('liters_per_can')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <!-- Stock -->
                <div class="col-md-4">
                    <div class="mb-4">
                        <label class="form-label" for="stock">
                            {{ __('crud.products.fields.stock') }}
                        </label>
                        <div class="input-group">
                            <input type="number" 
                                class="form-control @error('stock') is-invalid @enderror" 
                                id="stock" wire:model="stock" 
                                step="1" placeholder="0">
                            <span class="input-group-text">bidones</span>
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