<div>
    <div class="block block-rounded">
        <div class="block-header block-header-default bg-success">
            <h3 class="block-title text-white">INVENTARIO DE PRODUCTO</h3>
        </div>

        <div class="block-content">
            @if(empty($inventoryMovements))
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i>
                    Seleccione productos en los vuelos para gestionar el inventario.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>Producto</th>
                                <th>Stock Actual</th>
                                <th>Cantidad Requerida</th>
                                <th>Cliente deja producto</th>
                                <th>Cantidad Cliente</th>
                                <th>Diferencia</th>
                                <th>Agregar sobrante</th>
                                <th>Uso de Inventario</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventoryMovements as $index => $movement)
                                <tr>
                                    <td>
                                        <strong>{{ $this->getProductName($movement['product_id']) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $currentStock = $this->getProductStock($movement['product_id']);
                                            $stockClass = $currentStock > 0 ? 'bg-success' : 'bg-warning';
                                        @endphp
                                        <span class="badge {{ $stockClass }}">{{ number_format($currentStock, 2) }} L</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ number_format($movement['required_quantity'], 2) }} L</span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                wire:click="toggleClientProvidesProduct({{ $index }})"
                                                {{ $movement['client_provides_product'] ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label">
                                                {{ $movement['client_provides_product'] ? 'SÍ' : 'NO' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        @if($movement['client_provides_product'])
                                            <div class="input-group">
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="form-control"
                                                    wire:model.lazy="inventoryMovements.{{ $index }}.client_provided_quantity"
                                                    placeholder="0.00"
                                                >
                                                <span class="input-group-text">L</span>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($movement['client_provides_product'])
                                            @php
                                                $differenceText = $this->getDifferenceText($movement);
                                                $badgeClass = match($movement['difference_type']) {
                                                    'surplus' => 'bg-success',
                                                    'shortage' => 'bg-danger',
                                                    'exact' => 'bg-info',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $differenceText }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($movement['client_provides_product'] && $movement['difference_type'] === 'surplus')
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    wire:click="toggleAddSurplusToInventory({{ $index }})"
                                                    {{ $movement['add_surplus_to_inventory'] ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label">
                                                    Agregar al inventario
                                                </label>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $inventoryNote = $this->getInventoryUsageNote($movement);
                                            $statusClass = $this->getStockStatusClass($movement);
                                        @endphp
                                        @if($inventoryNote)
                                            <small class="{{ $statusClass }}">
                                                <i class="fa fa-info-circle me-1"></i>
                                                {{ $inventoryNote }}
                                            </small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            wire:model.lazy="inventoryMovements.{{ $index }}.notes"
                                            placeholder="Observaciones..."
                                        >
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(collect($inventoryMovements)->where('difference_type', 'shortage')->isNotEmpty())
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> Hay productos con faltante. Asegúrese de tener suficiente stock disponible.
                    </div>
                @endif

                @if(collect($inventoryMovements)->where('add_surplus_to_inventory', true)->isNotEmpty())
                    <div class="alert alert-success mt-3">
                        <i class="fa fa-check-circle me-2"></i>
                        <strong>Inventario:</strong> Los sobrantes seleccionados se agregarán automáticamente al inventario.
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
