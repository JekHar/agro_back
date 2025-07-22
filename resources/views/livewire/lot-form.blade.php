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
            <label for="name_lot" class="form-label">{{ __('crud.lots.fields.name') }}</label>
            <input type="text" wire:model="name_lot" id="name_lot" class="form-control"
                placeholder="{{ __('crud.lots.fields.name_placeholder') }}">
            @error('name_lot')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="navigationPinCoordinates" class="form-label">{{ __('Pin de navegación') }}</label>
            <div class="form-control d-flex justify-content-between align-items-center">
                @if ($navigationPin['lat'] && $navigationPin['lng'])
                    <div class="flex-grow-1">
                        <span id="coordinatesText"
                            style="user-select: all;">{{ number_format($navigationPin['lat'], 6) }},
                            {{ number_format($navigationPin['lng'], 6) }}</span>
                    </div>
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyCoordinates()">
                            <i class="fa fa-copy"></i>
                        </button>
                        <a href="https://maps.google.com/?q={{ $navigationPin['lat'] }},{{ $navigationPin['lng'] }}"
                            target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fa fa-map-marker"></i>
                        </a>
                    </div>
                @else
                    <span class="text-muted">{{ __('Pin no seleccionado') }}</span>
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
        {{-- @if (config('app.debug'))
            <h6 class="card-title">{{ __('crud.lots.fields.coordinates') }}</h6>
            <pre id="coordinates" wire:ignore></pre>
        @endif --}}
    </div>
    <button wire:click="saveLot" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
        {{ __('crud.lots.actions.save') }}
    </button>
</div>

<script>
    function copyCoordinates() {
        const coordinatesText = document.getElementById('coordinatesText').textContent;

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(coordinatesText).then(() => {
                showCopySuccess();
            });
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = coordinatesText;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showCopySuccess();
        }
    }

    function showCopySuccess() {
        const notification = document.createElement('div');
        notification.className = 'alert alert-success position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; padding: 10px 15px; border-radius: 5px;';
        notification.textContent = 'Coordenadas copiadas al portapapeles';
        document.body.appendChild(notification);

        setTimeout(() => {
            document.body.removeChild(notification);
        }, 2000);
    }
</script>
