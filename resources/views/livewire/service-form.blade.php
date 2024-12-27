<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Nombre del Servicio -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="name">{{ __('crud.services.fields.name') }}</label>
                    <input type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           id="name"
                           wire:model="name"
                           placeholder="{{ __('crud.services.fields.name') }}">
                    @error('name')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <!-- Descripción -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="description">{{ __('crud.services.fields.description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              id="description"
                              wire:model="description"
                              rows="3"></textarea>
                    @error('description')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Selección de Merchant -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="merchant_id">{{ __('crud.services.fields.merchant') }}</label>
                    <select class="form-control @error('merchant_id') is-invalid @enderror" 
                            id="merchant_id" 
                            wire:model="merchant_id">
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

            <!-- Precio por Hectárea -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="price_per_hectare">{{ __('crud.services.fields.price_per_hectare') }}</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number"
                               class="form-control @error('price_per_hectare') is-invalid @enderror"
                               id="price_per_hectare"
                               step="0.01"
                               wire:model="price_per_hectare"
                               placeholder="{{ __('crud.services.fields.price_per_hectare') }}">
                    </div>
                    @error('price_per_hectare')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                {{ $isEditing ? __('crud.services.actions.edit') : __('crud.services.add') }}
            </button>
        </div>
    </form>
</div>