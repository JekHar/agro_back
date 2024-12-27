<div>
    <form wire:submit.prevent="save">
        <div class="row">
            <!-- Nombre -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="name">{{ __('crud.products.fields.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                        id="name" wire:model="name">
                    @error('name')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- SKU -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="sku">{{ __('crud.products.fields.sku') }}</label>
                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                        id="sku" wire:model="sku">
                    @error('sku')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Categoría -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="category_id">{{ __('crud.products.fields.category') }}</label>
                    <select class="form-control @error('category_id') is-invalid @enderror" 
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

            <!-- Concentración -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="concentration">{{ __('crud.products.fields.concentration') }}</label>
                    <input type="number" class="form-control @error('concentration') is-invalid @enderror" 
                        id="concentration" wire:model="concentration" step="0.01">
                    @error('concentration')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Dosis por Hectárea -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="dosage_per_hectare">{{ __('crud.products.fields.dosage_per_hectare') }}</label>
                    <input type="number" class="form-control @error('dosage_per_hectare') is-invalid @enderror" 
                        id="dosage_per_hectare" wire:model="dosage_per_hectare" step="0.01">
                    @error('dosage_per_hectare')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Volumen de Aplicación por Hectárea -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="application_volume_per_hectare">
                        {{ __('crud.products.fields.application_volume_per_hectare') }}
                    </label>
                    <input type="number" class="form-control @error('application_volume_per_hectare') is-invalid @enderror" 
                        id="application_volume_per_hectare" wire:model="application_volume_per_hectare" step="0.01">
                    @error('application_volume_per_hectare')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Stock -->
            <div class="col-md-6">
                <div class="mb-4">
                    <label class="form-label" for="stock">{{ __('crud.products.fields.stock') }}</label>
                    <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                        id="stock" wire:model="stock" step="1">
                    @error('stock')
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
                {{ $isEditing ? __('crud.products.actions.edit') : __('crud.products.add') }}
            </button>
        </div>
    </form>
</div>