<div>
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

        <div class="position-relative mb-3">
            <div id="map" style="height: 600px;" wire:ignore></div>

            <div class="map-controls-overlay">
                <!-- Panel de Dibujo -->
                <div class="control-panel">
                    <div class="control-panel-header">
                        <i class="fa fa-pencil me-1"></i>
                        <span>Dibujo</span>
                    </div>
                    <div class="control-panel-body">
                        <button onclick="startDrawing()" class="btn btn-map-control btn-primary"
                            title="{{ __('crud.lots.actions.draw') }}">
                            <i class="fa fa-pencil"></i>
                            <span class="btn-text">Dibujar</span>
                        </button>
                        <button onclick="editButton()" class="btn btn-map-control btn-secondary"
                            title="{{ __('crud.lots.actions.edit') }}">
                            <i class="fa fa-edit"></i>
                            <span class="btn-text">Editar</span>
                        </button>
                        <button id='saveButton' onclick="saveDrawing()" class="btn btn-map-control btn-success"
                            style="display: none;" title="{{ __('crud.lots.actions.save_edit') }}">
                            <i class="fa fa-save"></i>
                            <span class="btn-text">Guardar</span>
                        </button>
                    </div>
                </div>

                <div class="control-panel">
                    <div class="control-panel-header">
                        <i class="fa fa-tools me-1"></i>
                        <span>Herramientas</span>
                    </div>
                    <div class="control-panel-body">
                        <button id='cropButton' onclick="startCrop()" class="btn btn-map-control btn-warning"
                            title="Recortar área">
                            <i class="fa fa-cut"></i>
                            <span class="btn-text">Recortar</span>
                        </button>
                        <button id='cropCancelButton' onclick="cancelCrop()" class="btn btn-map-control btn-danger"
                            style="display: none;" title="Cancelar recorte">
                            <i class="fa fa-times"></i>
                            <span class="btn-text">Cancelar</span>
                        </button>
                        <button onclick="startDrawingPin()" class="btn btn-map-control btn-info"
                            title="Colocar pin de navegación">
                            <i class="fa fa-map-marker"></i>
                            <span class="btn-text">Pin</span>
                        </button>
                    </div>
                </div>

                <div class="control-panel">
                    <div class="control-panel-header">
                        <i class="fa fa-file me-1"></i>
                        <span>Archivos</span>
                    </div>
                    <div class="control-panel-body">
                        <button onclick="exportKML()" class="btn btn-map-control btn-success"
                            title="{{ __('crud.lots.actions.export') }}">
                            <i class="fas fa-download"></i>
                            <span class="btn-text">Exportar</span>
                        </button>
                        <button onclick="importKML()" class="btn btn-map-control btn-success"
                            title="{{ __('crud.lots.actions.import') }}">
                            <i class="fas fa-upload"></i>
                            <span class="btn-text">Importar</span>
                        </button>
                    </div>
                </div>
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
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                onclick="copyCoordinates()">
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
        </div>
        <button wire:click="saveLot" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
            {{ __('crud.lots.actions.save') }}
        </button>
    </div>

    <style>
        /* Estilos para la botonera flotante optimizada */
        .map-controls-overlay {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #ccc transparent;
        }

        .map-controls-overlay::-webkit-scrollbar {
            width: 3px;
        }

        .map-controls-overlay::-webkit-scrollbar-track {
            background: transparent;
        }

        .map-controls-overlay::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .control-panel {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 8px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            transition: all 0.2s ease;
            min-width: 160px;
        }

        .control-panel:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.16);
            transform: translateY(-1px);
        }

        .control-panel-header {
            background: linear-gradient(135deg, #FF6600 0%, #FF8533 100%);
            color: white;
            padding: 6px 10px;
            font-size: 0.8rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .control-panel-body {
            padding: 6px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .btn-map-control {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 6px 10px;
            border: none;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            color: white;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            width: 100%;
            min-height: 32px;
        }

        .btn-map-control:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .btn-map-control:active {
            transform: translateY(0);
        }

        .btn-map-control i {
            margin-right: 6px;
            width: 14px;
            text-align: center;
            font-size: 0.8rem;
        }

        .btn-text {
            font-size: 0.75rem;
            font-weight: 500;
        }

        .btn-map-control.btn-primary {
            background: linear-gradient(135deg, #4a90e2, #357abd);
        }

        .btn-map-control.btn-primary:hover {
            background: linear-gradient(135deg, #357abd, #2968a3);
        }

        .btn-map-control.btn-secondary {
            background: linear-gradient(135deg, #6c757d, #545b62);
        }

        .btn-map-control.btn-secondary:hover {
            background: linear-gradient(135deg, #545b62, #454c53);
        }

        .btn-map-control.btn-success {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .btn-map-control.btn-success:hover {
            background: linear-gradient(135deg, #1e7e34, #155724);
        }

        .btn-map-control.btn-warning {
            background: linear-gradient(135deg, #FF6600, #e55a00);
            color: white;
        }

        .btn-map-control.btn-warning:hover {
            background: linear-gradient(135deg, #e55a00, #cc5200);
            color: white;
        }

        .btn-map-control.btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .btn-map-control.btn-danger:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
        }

        .btn-map-control.btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .btn-map-control.btn-info:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
        }

        @media (max-width: 768px) {
            .map-controls-overlay {
                position: static;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
                margin-bottom: 10px;
                max-height: none;
                overflow: visible;
                gap: 4px;
            }

            .control-panel {
                min-width: auto;
                flex: 1;
                min-width: 130px;
            }

            .control-panel-header {
                font-size: 0.75rem;
                padding: 5px 8px;
            }

            .btn-map-control {
                padding: 5px 8px;
                font-size: 0.7rem;
                min-height: 28px;
            }

            .btn-text {
                display: none;
            }

            .btn-map-control i {
                margin-right: 0;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .map-controls-overlay {
                gap: 3px;
            }

            .control-panel {
                min-width: 100px;
            }

            .control-panel-header {
                padding: 4px 6px;
            }

            .btn-map-control {
                padding: 4px 6px;
                min-height: 26px;
            }
        }

        .leaflet-draw {
            display: none !important;
        }

        .leaflet-control-layers {
            top: 10px !important;
            right: 10px !important;
            left: auto !important;
        }

        .leaflet-control-zoom {
            top: 60px !important;
            right: 10px !important;
            left: auto !important;
        }

        .leaflet-control-layers,
        .leaflet-control-zoom {
            border-radius: 8px !important;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .leaflet-control-layers-toggle {
            border-radius: 8px !important;
        }

        .leaflet-control-zoom a {
            border-radius: 6px !important;
            margin: 1px !important;
        }
    </style>

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
</div>