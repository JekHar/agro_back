<div>
    <div class="block block-rounded">
        <div class="block-content">
            <form wire:submit.prevent="save">
                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label" for="name">
                                <span class="text-danger">*</span> {{ __('crud.products.fields.name') }}
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name" wire:model="name"
                                   placeholder="Ingrese el nombre del producto">
                            @error('name')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Marca comercial -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label" for="commercial_brand">
                                {{ __('crud.products.fields.brand') }}
                            </label>
                            <input type="text"
                                   class="form-control @error('commercial_brand') is-invalid @enderror"
                                   id="commercial_brand" wire:model="commercial_brand"
                                   placeholder="Ingrese la marca comercial">
                            @error('commercial_brand')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Categoría -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label" for="category_id">
                                <span class="text-danger">*</span> {{ __('crud.products.fields.category') }}
                            </label>
                            <select class="form-select js-select2 @error('category_id') is-invalid @enderror"
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
                </div>

                <div class="row">
                    <!-- Comerciante (solo visible para Admin) -->
                    @if(!$isTenant)
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="merchant_id">
                                    {{ __('crud.services.fields.merchant') }}
                                </label>
                                <select class="form-select js-select2 @error('merchant_id') is-invalid @enderror"
                                        id="merchant_id" wire:model="merchant_id">
                                    <option value="">{{ __('crud.services.select_merchant') }}</option>
                                    @foreach ($merchants as $id => $businessName)
                                        <option value="{{ $id }}">{{ $businessName }}</option>
                                    @endforeach
                                </select>
                                @error('merchant_id')
                                <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <!-- Si es tenant, mantenemos el merchant_id como un campo oculto -->
                        <input type="hidden" wire:model="merchant_id">
                    @endif
                </div>

                <div class="row">
                    <!-- Dosis por Hectárea -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label" for="dosage_per_hectare">
                                {{ __('crud.products.fields.dosage_per_hectare') }}
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('dosage_per_hectare') is-invalid @enderror"
                                       id="dosage_per_hectare" wire:model="dosage_per_hectare"
                                       step="0.01" placeholder="0.00">
                                <span class="input-group-text">L/ha</span>
                            </div>
                            @error('dosage_per_hectare')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Litros por bidón -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label" for="liters_per_can">
                                {{ __('crud.products.fields.liters_per_container') }}
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('liters_per_can') is-invalid @enderror"
                                       id="liters_per_can" wire:model="liters_per_can"
                                       step="0.01" placeholder="0.00">
                                <span class="input-group-text">L</span>
                            </div>
                            @error('liters_per_can')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <!-- Stock -->
                    <div class="col-md-4">
                        <div class="mb-4">
                            <label class="form-label" for="stock">
                                {{ __('crud.products.fields.stock') }}
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       id="stock" wire:model="stock"
                                       step="1" placeholder="0">
                                <span class="input-group-text">bidones</span>
                            </div>
                            @error('stock')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                            <i class="fa fa-fw fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                            {{ $isEditing ? __('crud.products.actions.edit') : __('crud.products.add') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if($isEditing && $productId)
        <!-- Inventory Movements Section -->
        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    <i class="fa fa-boxes me-2"></i>
                    Movimientos de Inventario
                </h3>
                <div class="block-options">
                    <button type="button" class="btn btn-sm btn-alt-secondary" wire:click="toggleInventoryMovements">
                        <i class="fa fa-{{ $showInventoryMovements ? 'minus' : 'plus' }} me-1"></i>
                        {{ $showInventoryMovements ? 'Ocultar' : 'Mostrar' }} Movimientos
                    </button>
                    <button type="button" class="btn btn-sm btn-primary ms-2" wire:click="openInventoryMovementModal">
                        <i class="fa fa-plus me-1"></i>
                        Crear Movimiento
                    </button>
                </div>
            </div>

            @if($showInventoryMovements)
                <div class="block-content">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Información:</strong> Aquí se muestran todos los movimientos de inventario relacionados con este producto en las órdenes de trabajo.
                    </div>

                    @php
                        $inventoryMovements = $this->getInventoryMovements();
                    @endphp

                    @if($inventoryMovements->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-vcenter">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Cliente</th>
                                        <th>Tipo de Producto</th>
                                        <th>Cantidad Aportada (L)</th>
                                        <th>Cantidad Requerida (L)</th>
                                        <th>Estado</th>
                                        <th>Agregado a Inventario</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventoryMovements as $movement)
                                        <tr>
                                            <td>{{ $movement->order->order_number ?? 'N/A' }}</td>
                                            <td>
                                                @if($movement->client_provided && $movement->merchant)
                                                    {{ $movement->merchant->business_name }}
                                                @elseif($movement->client_provided)
                                                    {{ $movement->order->client->business_name ?? 'Cliente' }}
                                                @else
                                                    <span class="text-muted">Empresa</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($movement->client_provided)
                                                    <span class="badge bg-primary">Cliente</span>
                                                @else
                                                    <span class="badge bg-info">Empresa</span>
                                                @endif
                                            </td>
                                            <td class="text-end">{{ number_format($movement->quantity, 2) }}</td>
                                            <td class="text-end">{{ number_format($movement->required_quantity, 2) }}</td>
                                            <td class="text-center">
                                                @if($movement->client_provided)
                                                    @if($movement->hasSurplus())
                                                        <span class="badge bg-success">Sobrante: {{ number_format($movement->getSurplusQuantity(), 2) }} L</span>
                                                    @elseif($movement->hasShortage())
                                                        <span class="badge bg-danger">Faltante: {{ number_format($movement->getShortageQuantity(), 2) }} L</span>
                                                    @else
                                                        <span class="badge bg-info">Cantidad exacta</span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-secondary">Adición de empresa</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($movement->add_surplus_to_inventory)
                                                    <span class="badge bg-success">SÍ</span>
                                                @else
                                                    <span class="badge bg-light text-dark">NO</span>
                                                @endif
                                            </td>
                                            <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            No se encontraron movimientos de inventario para este producto.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    @endif

    <!-- Include the InventoryMovementForm component -->
    @if($isEditing && $productId)
        @livewire('inventory-movement-form', ['productId' => $productId], key('inventory-movement-form-'.$productId))
    @endif

</div>
