<div x-data="{
    showLotModal: @entangle('showLotModal'),
    mapInitialized: false,
    init() {
        this.$watch('showLotModal', (value) => {
            if (value && !this.mapInitialized) {
                this.$nextTick(() => {
                    setTimeout(() => {
                        if (typeof initializeMap === 'function') {
                            initializeMap();
                            setupDrawingControls();
                            this.mapInitialized = true;
                        }
                    }, 200);
                });
            } else if (!value) {
                // Reset the flag when modal is closed so it can be initialized again
                this.mapInitialized = false;
            }
        });
    }
}">
    <form wire:submit.prevent="submit">
        <div class="block block-rounded">
            <div class="block-header block-header-default bg-primary">
                <h3 class="block-title text-white">{{ $isEditing ? 'EDITAR ORDEN DE TRABAJO' : 'CREAR ORDEN DE TRABAJO' }}</h3>
            </div>

            <div class="block-content">
                @hasrole('Admin')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">Usuario de sistema</label>
                            <div class="input-group">
                                <select wire:model="tenant_id" class="form-select" {{ $isEditing ? 'disabled' : '' }}>
                                    @foreach ($tenants as $tenant)
                                        <option value="{{ $tenant->id }}">{{ $tenant->business_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endhasrole
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Fecha ingreso de Orden</label>
                                <input type="text" wire:model="order_date" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Responsable de Ingreso</label>
                                <div class="input-group">
                                    <select wire:model="responsible_id" class="form-select" {{ $isEditing ? 'disabled' : '' }}>
                                        @foreach ($groundSupports as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-alt-primary" disabled>
                                        <i class="fa fa-user"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Cliente</label>
                                <div class="input-group">
                                    <select wire:model.live="client_id" class="form-select">
                                        <option value="">Seleccione cliente</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->business_name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" wire:click="createNewClient">
                                        <i class="fa fa-add"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Servicio</label>
                                <div class="input-group">
                                    <select wire:model="service_id" class="form-select" {{ !$client_id ? 'disabled' : '' }}>
                                        <option value="">Seleccione servicio</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" wire:click="createNewService" {{ !$client_id ? 'disabled' : '' }}>
                                        <i class="fa fa-add"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Aeronave</label>
                                <div class="input-group">
                                    <select wire:model="aircraft_id" class="form-select" {{ !$client_id ? 'disabled' : '' }}>
                                        <option value="">Seleccione aeronave</option>
                                        @foreach ($aircrafts as $aircraft)
                                            <option value="{{ $aircraft->id }}">{{ $aircraft->brand }} {{ $aircraft->models }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" wire:click="createNewAircraft" {{ !$client_id ? 'disabled' : '' }}>
                                        <i class="fa fa-add"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Piloto</label>
                                <div class="input-group">
                                    <select wire:model="pilot_id" class="form-select" {{ !$client_id ? 'disabled' : '' }}>
                                        <option value="">Seleccione piloto</option>
                                        @foreach ($pilots as $pilot)
                                            <option value="{{ $pilot->id }}">{{ $pilot->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" wire:click="createNewPilot" {{ !$client_id ? 'disabled' : '' }}>
                                        <i class="fa fa-add"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group mb-3">
                                <label class="form-label">Apoyo de Tierra</label>
                                <div class="input-group">
                                    <select wire:model="ground_support_id" class="form-select" {{ !$client_id ? 'disabled' : '' }}>
                                        <option value="">Seleccione apoyo</option>
                                        @foreach ($groundSupports as $support)
                                            <option value="{{ $support->id }}">{{ $support->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" wire:click="createNewGroundSupport" {{ !$client_id ? 'disabled' : '' }}>
                                        <i class="fa fa-add"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Receta</label>
                                <input type="file" wire:model="prescription_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>
            </div>
        </div>

        <livewire:order-lots :clientId="$client_id" :existingLots="$selectedLots" />

        {{-- TODO: OrderProducts component removed - product selection moved to FlightWizard
        <livewire:order-products :clientId="$client_id" :existingProducts="$selectedProducts ?? []" :totalHectares="$totalHectares" />
        --}}

        <livewire:order-flights :clientId="$client_id" :existingFlights="$flights ?? []" :totalHectares="$totalHectares" :orderLots="$selectedLots" />

        <livewire:order-inventory :clientId="$client_id" :existingInventory="$inventoryMovements ?? []" :totalHectares="$totalHectares" />

        <div class="row mb-3">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-{{ $isEditing ? 'save' : 'plus-circle' }} me-1"></i>
                    {{ $isEditing ? 'Guardar Cambios' : 'Crear Orden' }}
                </button>
            </div>
        </div>
    </form>

    {{-- Modal Components - Reusing existing Livewire forms --}}
    @if($showClientModal)
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Crear Nuevo Cliente</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeClientModal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:merchant-form
                            :isClient="true"
                            :isModal="true"
                            :key="'client-modal-'.now()"
                        />
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if($showServiceModal)
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Crear Nuevo Servicio</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeServiceModal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:service-form
                            :isModal="true"
                            :key="'service-modal-'.now()"
                        />
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if($showAircraftModal)
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Crear Nueva Aeronave</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeAircraftModal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:aircraft-form
                            :isModal="true"
                            :key="'aircraft-modal-'.now()"
                        />
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if($showPilotModal)
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Crear Nuevo Piloto</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePilotModal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:user-form
                            :role="'Pilot'"
                            :isModal="true"
                            :key="'pilot-modal-'.now()"
                        />
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if($showGroundSupportModal)
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Crear Nuevo Apoyo de Tierra</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeGroundSupportModal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:user-form
                            :role="'Ground Support'"
                            :isModal="true"
                            :key="'ground-support-modal-'.now()"
                        />
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showLotModal)
        <div class="modal-backdrop fade show" style="z-index: 1040;"></div>
        <div class="modal fade show" style="display: block; z-index: 1050;" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Crear Nuevo Lote</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeLotModal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:lot-form
                            :isModal="true"
                            :key="'lot-modal-'.now()"
                        />
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            // Listen for lot-loaded event
            Livewire.on('lot-loaded', (data) => {
                if (typeof drawnItems !== 'undefined') {
                    drawnItems.clearLayers();
                    if (navigationPin) {
                        map.removeLayer(navigationPin);
                        navigationPin = null;
                    }

                    const coordinates = data[0]?.coordinates;
                    const holes = data[0].holes;
                    const hectares = data[0]?.hectares;
                    const navigationPinCoords = data[0]?.navigationPin;

                    if (coordinates && coordinates.length > 0) {
                        const mainCoords = coordinates.map(coord => [
                            parseFloat(coord.lat),
                            parseFloat(coord.lng)
                        ]);

                        const polygonLatLngs = [mainCoords];

                        if (holes && holes.length > 0) {
                            holes.forEach(holeGroup => {
                                const holeCoords = holeGroup.map(coord => [
                                    parseFloat(coord.lat),
                                    parseFloat(coord.lng)
                                ]);
                                polygonLatLngs.push(holeCoords);
                            });
                        }

                        const polygon = L.polygon(polygonLatLngs, {
                            color: 'orange',
                            fillColor: 'orange',
                            fillOpacity: 0.3
                        });

                        drawnItems.addLayer(polygon);

                        if (typeof updateCoordinatesDisplay === 'function') {
                            updateCoordinatesDisplay(coordinates, hectares);
                        }

                        map.fitBounds(polygon.getBounds());
                    }

                    if (navigationPinCoords && navigationPinCoords.lat && navigationPinCoords.lng) {
                        const latlng = L.latLng(parseFloat(navigationPinCoords.lat), parseFloat(navigationPinCoords.lng));
                        navigationPin = L.marker(latlng).addTo(map);
                        if (typeof updateNavigationPinDisplay === 'function') {
                            updateNavigationPinDisplay(latlng);
                        }
                    }
                }
            });
        });
    </script>
</div>
