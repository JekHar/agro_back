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
                @foreach($inventoryMovements as $index => $movement)
                    <div class="row mb-4 p-3 border rounded">
                        <!-- Product Name -->
                        <div class="col-12 mb-3">
                            <h5 class="mb-1 text-dark">
                                <i class="fa fa-flask me-2 text-primary"></i>
                                <strong>{{ $this->getProductName($movement['product_id']) }}</strong>
                            </h5>
                        </div>

                        <!-- First Row: Stock Info and Required Quantity -->
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label text-dark fw-bold small">Stock Actual</label>
                                    @php
                                        $stockInfo = $this->getStockDisplayInfo($movement['product_id']);
                                        $stockClass = $stockInfo['total_liters'] > 0 ? 'bg-success text-white' : 'bg-warning text-dark';
                                    @endphp
                                    <div>
                                        <span class="badge {{ $stockClass }}" title="Stock total en litros">
                                            {{ $stockInfo['display_text'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-dark fw-bold small">Cantidad Requerida</label>
                                    <div>
                                        <span class="badge bg-primary text-white">{{ number_format($movement['required_quantity'], 2) }} L</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second Row: Client and Company Product Quantities -->
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <label class="form-label text-dark fw-bold small">Producto dejado por cliente</label>
                                    <div class="input-group mb-2">
                                        <input
                                            type="number"
                                            min="0"
                                            class="form-control form-control-sm"
                                            wire:model.lazy="inventoryMovements.{{ $index }}.client_provided_quantity"
                                            value="{{ $movement['client_provided_quantity'] ?? 0 }}"
                                        >
                                        <span class="input-group-text bg-primary text-white">L</span>
                                    </div>
                                    @if(($movement['client_provided_quantity'] ?? 0) > ($movement['required_quantity'] ?? 0) && ($movement['client_provided_quantity'] ?? 0) > 0)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                wire:model.lazy="inventoryMovements.{{ $index }}.add_client_surplus_to_inventory"
                                                id="clientSurplus{{ $index }}"
                                            >
                                            <label class="form-check-label small text-success" for="clientSurplus{{ $index }}">
                                                Agregar sobrante al inventario
                                            </label>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-dark fw-bold small">Producto agregado por empresa</label>
                                    <div class="input-group mb-2">
                                        <input
                                            type="number"
                                            min="0"
                                            class="form-control form-control-sm"
                                            wire:model.lazy="inventoryMovements.{{ $index }}.tenant_quantity_to_add"
                                            value="{{ $movement['tenant_quantity_to_add'] ?? 0 }}"
                                        >
                                        <span class="input-group-text bg-info text-white">L</span>
                                    </div>
                                    @if(($movement['tenant_quantity_to_add'] ?? 0) > 0)
                                        <small class="text-info">
                                            <i class="fa fa-info-circle me-1"></i>
                                            Se agregará automáticamente al inventario
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Usage Notes and General Notes Row -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Uso de Inventario</label>
                            @php
                                $inventoryNote = $this->getInventoryUsageNote($movement);
                                $statusClass = $this->getStockStatusClass($movement);
                            @endphp
                            @if($inventoryNote)
                                <div class="{{ $statusClass }}">
                                    <small>
                                        <i class="fa fa-info-circle me-1"></i>
                                        {{ $inventoryNote }}
                                    </small>
                                </div>
                            @else
                                <div>
                                    <span class="text-muted">No requiere stock del inventario</span>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold small">Notas</label>
                            <input
                                type="text"
                                class="form-control form-control-sm"
                                wire:model.lazy="inventoryMovements.{{ $index }}.notes"
                                placeholder="Observaciones..."
                                value="{{ $movement['notes'] ?? '' }}"
                            >
                        </div>
                    </div>
                @endforeach

                @if(collect($inventoryMovements)->where(function($movement) {
                    return $this->hasInventoryShortage($movement);
                })->isNotEmpty())
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Atención:</strong> Hay productos con faltante. Asegúrese de tener suficiente stock disponible.
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
