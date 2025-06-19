<div>
    <div class="mb-3">
        <label for="merchant_id" class="form-label">{{ __('crud.lots.fields.merchant') }}</label>
        <select wire:model="merchant_id" id="merchant_id" class="form-select"
            placeholder="{{ __('crud.lots.fields.merchant') }}">
            <option value="">{{ __('crud.lots.select_merchant') }}</option>
            @foreach ($merchants as $merchant)
                <option value="{{ $merchant->id }}">{{ $merchant->business_name }}</option>
            @endforeach
        </select>
        @error('merchant_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div id="map" style="height: 600px;" wire:ignore></div>

    <div class="d-flex flex-row justify-content-between mb-3 mt-3">

        <div class="btn-group">
            <button onclick="startDrawing()" class="btn btn-primary text-white">
                <i class="fa fa-pencil"></i> {{ __('crud.lots.actions.draw') }}
            </button>
            <button onclick="editButton()" class="btn btn-secondary text-white">
                <i class="fa fa-edit"></i> {{ __('crud.lots.actions.edit') }}
            </button>
            <button id='saveButton' onclick="saveDrawing()" class="btn btn-primary text-white"style="display: none;">
                <i class="fa fa-save"></i> {{ __('crud.lots.actions.save_edit') }}
            </button>
            <button id='cropButton' onclick="startCrop()" class="btn btn-warning text-white">
                <i class="fa fa-cut"></i> {{ __('Recortar') }}
            </button>
            <button id='cropCancelButton' onclick="cancelCrop()" class="btn btn-danger text-white"
                style="display: none;">
                <i class="fa fa-times"></i> {{ __('Cancelar') }}
            </button>
            <button onclick="startDrawingPin()" class="btn btn-info text-white"> {{-- Nuevo botón para el pin --}}
                <i class="fa fa-map-marker"></i> {{ __('Colocar pin') }}
            </button>
        </div>
        <div class="btn-group">
            <button onclick="exportKML()" class="btn btn-success">
                <i class="fas fa-download"></i> {{ __('crud.lots.actions.export') }}
            </button>
            <button onclick="importKML()" class="btn btn-success">
                <i class="fas fa-upload"></i> {{ __('crud.lots.actions.import') }}
            </button>
        </div>
    </div>
    <div class="row">
        <div class="mb-3">
            <input type="file" id="kmlFileInput" accept=".kml" onchange="handleKMLImport(event)" class="d-none">
        </div>

        <div class="mb-3">
            <label for="number" class="form-label">{{ __('crud.lots.fields.number') }}</label>
            <input type="number" wire:model="number" id="number" class="form-control"
                {{ $isCreateMode && !$merchant_id ? 'readonly' : '' }}>
            <small
                class="text-muted">{{ $isCreateMode && !$merchant_id ? __('crud.lots.fields.number_auto_assigned') : '' }}</small>
            @error('number')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="navigationPinCoordinates"
                class="form-label">{{ __('crud.lots.fields.navigation_pin') }}</label>
            <div id="navigationPinCoordinates" class="form-control">
                @if ($navigationPin['lat'] && $navigationPin['lng'])
                    Pin: Lat: {{ number_format($navigationPin['lat'], 6) }}, Lng:
                    {{ number_format($navigationPin['lng'], 6) }}
                @else
                    {{ __('Pin no seleccionado') }}
                @endif
            </div>
            @error('navigationPin.lat')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            @error('navigationPin.lng')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="hectares" class="form-label">{{ __('crud.lots.fields.hectares') }}</label>
            <input type="number" wire:model="hectares" id="hectares" step="0.001" class="form-control">
            @error('hectares')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        @if (config('app.debug'))
            <h6 class="card-title">{{ __('crud.lots.fields.coordinates') }}</h6>
            <pre id="coordinates" wire:ignore></pre>
        @endif
    </div>
    <button wire:click="saveLot" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
        {{ __('crud.lots.actions.save') }}
    </button>
</div>
