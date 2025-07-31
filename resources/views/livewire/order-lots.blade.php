<div>
    <div class="block block-rounded">
        <div class="block-header block-header-default bg-primary">
            <h3 class="block-title text-white">LOTES</h3>
            <div class="block-options">
                <button type="button" class="btn btn-sm btn-primary" wire:click="createNewLot" {{ !$clientId ? 'disabled' : '' }}>
                    <i class="fa fa-add me-1"></i> Nuevo Lote de cliente
                </button>
            </div>
        </div>
        <div class="block-content">
            @if(count($selectedLots) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="bg-body-light">
                        <tr>
                            <th>Selección Lote</th>
                            <th class="text-center">Cantidad de Has</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($selectedLots as $index => $lot)
                            <tr>
                                <td>
                                    <select wire:model.live="selectedLots.{{ $index }}.lot_id" class="form-select">
                                        <option value="">Seleccione lote</option>
                                        @foreach($availableLots as $availableLot)
                                            @php
                                                $isSelected = false;
                                                foreach($selectedLots as $i => $selectedLot) {
                                                    if ($i != $index && isset($selectedLot['lot_id']) && $selectedLot['lot_id'] == $availableLot->id) {
                                                        $isSelected = true;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $availableLot->id }}" {{ $isSelected ? 'disabled' : '' }}>
                                                {{ $availableLot->name_lot }} {{ $availableLot->number }} {{ $isSelected ? '(Ya seleccionado)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ number_format($lot['hectares'] ?? 0, 2) }}</span>
                                </td>
                                <td class="text-center">
                                    <select wire:model="selectedLots.{{ $index }}.status" class="form-select form-select-sm">
                                        <option value="pending">Pendiente</option>
                                        <option value="in_process">En Progreso</option>
                                        <option value="finished">Completado</option>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger" wire:click="removeLot({{ $index }})">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-outline-primary me-2" wire:click="addLot" {{ !$clientId ? 'disabled' : '' }}>
                        <i class="fa fa-plus me-1"></i> AGREGAR LOTE
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
