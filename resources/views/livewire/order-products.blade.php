<div>
    <div class="block block-rounded mb-2">
        <div class="block-header block-header-default bg-primary">
            <h3 class="block-title text-white">PRODUCTOS</h3>
            <div class="block-options">
                <button type="button" class="btn btn-sm btn-primary" {{ !$clientId ? 'disabled' : '' }}>
                    <i class="fa fa-add me-1"></i> Nuevo Producto
                </button>
            </div>
        </div>
        <div class="block-content">
            <div>
                <p class="fw-bold">HECTÁREAS TOTALES: {{ number_format($totalHectares, 1) }}</p>
            </div>
            @if (count($selectedProducts) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead class="bg-body-light">
                            <tr>
                                <th>PRODUCTO</th>
                                <th class="text-center">CANTIDAD DE PRODUCTO<br>DEJADO POR CLIENTE</th>
                                <th class="text-center">CANTIDAD TOTAL de<br>PRODUCTO manual</th>
                                <th class="text-center">DOSIS/HA manual</th>
                                <th class="text-center">CANTIDAD<br>TOTAL A<br>UTILIZAR</th>
                                <th class="text-center">DOSIS</th>
                                <th class="text-center">SOBRA/FALTA<br>PRODUCTO</th>
                                <th class="text-center">CANTIDAD<br>sobrante/faltante</th>
                                <th class="text-center">OBSERVACIÓN</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedProducts as $index => $product)
                                <tr>
                                    <td>
                                        <select wire:model.live="selectedProducts.{{ $index }}.product_id"
                                            class="form-select">
                                            <option value="">Seleccione producto</option>
                                            @foreach ($availableProducts as $availableProduct)
                                                <option value="{{ $availableProduct->id }}">{{ $availableProduct->id }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-2">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model.live="selectedProducts.{{ $index }}.use_client_quantity"
                                                    id="use_client_{{ $index }}">
                                            </div>
                                            <input type="number"
                                                wire:model.live="selectedProducts.{{ $index }}.client_provided_quantity"
                                                class="form-control" step="0.01" min="0">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <input type="number"
                                            wire:model.live="selectedProducts.{{ $index }}.manual_total_quantity"
                                            class="form-control {{ (isset($product['use_client_quantity']) && $product['use_client_quantity']) || (isset($product['use_manual_dosage']) && $product['use_manual_dosage']) ? 'bg-light text-muted' : '' }}"
                                            step="0.01" min="0"
                                            {{ (isset($product['use_client_quantity']) && $product['use_client_quantity']) || (isset($product['use_manual_dosage']) && $product['use_manual_dosage']) ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex align-items-center">
                                            <div class="form-check me-2">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model.live="selectedProducts.{{ $index }}.use_manual_dosage"
                                                    id="use_dosage_{{ $index }}">
                                            </div>
                                            <input type="number"
                                                wire:model.live="selectedProducts.{{ $index }}.manual_dosage_per_hectare"
                                                class="form-control {{ isset($product['use_client_quantity']) && $product['use_client_quantity'] ? 'bg-light text-muted' : '' }}"
                                                step="0.01" min="0"
                                                {{ isset($product['use_client_quantity']) && $product['use_client_quantity'] ? 'disabled' : '' }}>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold">{{ number_format($product['total_quantity_to_use'] ?? 0, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold">{{ number_format($product['calculated_dosage'] ?? 0, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if (isset($product['product_status']))
                                            @if ($product['product_status'] == 'Falta producto')
                                                <span class="badge bg-danger">Falta producto</span>
                                            @elseif($product['product_status'] == 'Sobra producto')
                                                <span class="badge bg-success">Sobra producto</span>
                                            @else
                                                <span class="badge bg-info">n/a</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold">{{ number_format($product['product_difference'] ?? 0, 2) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <input type="text"
                                            wire:model.live="selectedProducts.{{ $index }}.difference_observation"
                                            class="form-control">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            wire:click="removeProduct({{ $index }})">
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
                    <button type="button" class="btn btn-outline-primary" wire:click="addProduct"
                        {{ !$clientId ? 'disabled' : '' }}>
                        <i class="fa fa-plus me-1"></i> AGREGAR PRODUCTO
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
