<form action="{{ isset($item) ? route('services.update', $item) : route('services.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($item))
        @method('PUT')
    @endif

    <div class="row">
        <!-- Nombre del Servicio -->
        <div class="col-md-6">
            <div class="mb-4">
                <label class="form-label" for="name">{{ __('crud.items.fields.name') }}</label>
                <input type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       id="name"
                       name="name"
                       value="{{ old('name', $service->name ?? '') }}"
                       placeholder="{{ __('crud.items.fields.name') }}">
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
                <label class="form-label" for="description">{{ __('crud.items.fields.description') }}</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                          id="description"
                          name="description"
                          rows="3">{{ old('description', $item->description ?? '') }}</textarea>
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
                <label class="form-label" for="merchant_id">{{ __('crud.items.fields.merchant') }}</label>
                <select class="form-control @error('merchant_id') is-invalid @enderror" 
                        id="merchant_id" 
                        name="merchant_id">
                    <option value="">{{ __('crud.items.select_merchant') }}</option>
                    @foreach ($merchants as $id => $businessName)
                        <option value="{{ $id }}" 
                                {{ old('merchant_id', $service->merchant_id ?? '') == $id ? 'selected' : '' }}>
                            {{ $businessName }}
                        </option>
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
                <label class="form-label" for="price_per_hectare">{{ __('crud.items.fields.price_per_hectare') }}</label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number"
                           class="form-control @error('price_per_hectare') is-invalid @enderror"
                           id="price_per_hectare"
                           name="price_per_hectare"
                           value="{{ old('price_per_hectare', $item->price_per_hectare ?? '') }}"
                           placeholder="{{ __('crud.items.fields.price_per_hectare') }}">
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
            <i class="fa fa-fw fa-{{ isset($item) ? 'save' : 'plus' }} me-1"></i>
            {{ isset($item) ? __('crud.items.actions.edit') : __('crud.items.add') }}
        </button>
    </div>
</form>

@push('after_body')
    @vite('resources/js/plugins/filepond.js')
@endpush
