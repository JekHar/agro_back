<div>
    <form wire:submit.prevent="submit">
        <div class="block block-rounded">
            <div class="block-header block-header-default bg-primary">
                <h3 class="block-title text-white">{{ $isEditing ? 'EDITAR ORDEN DE TRABAJO' : 'CREAR ORDEN DE TRABAJO' }}</h3>
            </div>

            <div class="block-content">
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
                                    <button type="button" class="btn btn-primary">
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

        <livewire:order-products :clientId="$client_id" :existingProducts="$selectedProducts ?? []" :totalHectares="$totalHectares" />

        <livewire:order-flights :clientId="$client_id" :existingFlights="$flights ?? []" :totalHectares="$totalHectares" :products="$selectedProducts ?? []" />

        <div class="row mb-3">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-{{ $isEditing ? 'save' : 'plus-circle' }} me-1"></i>
                    {{ $isEditing ? 'Guardar Cambios' : 'Crear Orden' }}
                </button>
            </div>
        </div>
    </form>
</div>
