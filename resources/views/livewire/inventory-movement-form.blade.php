<div>
    @if($showModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-boxes me-2"></i>
                            {{ $isEditing ? 'Editar' : 'Crear' }} Movimiento de Inventario
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="row">
                                <!-- Product Selection -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <span class="text-danger">*</span> Producto
                                    </label>
                                    <select class="form-select @error('product_id') is-invalid @enderror"
                                            wire:model="product_id"
                                            {{ $parentProductId ? 'disabled' : '' }}>
                                        <option value="">Seleccionar producto...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($parentProductId)
                                        <small class="text-muted">Producto preseleccionado desde el formulario de productos</small>
                                    @endif
                                </div>

                                <!-- Movement Type -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Producto agregado de cliente</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" wire:model.live="client_provided" id="clientProvided">
                                        <label class="form-check-label fw-bold" for="clientProvided">
                                            {{ $client_provided ? 'SÍ' : 'NO' }}
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        {{ $client_provided ? 'Producto aportado por un cliente' : 'Producto agregado por la empresa' }}
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Quantity -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <span class="text-danger">*</span> Cantidad
                                    </label>
                                    <div class="input-group">
                                        <input type="number"
                                               class="form-control @error('quantity') is-invalid @enderror"
                                               wire:model="quantity"
                                               step="0.01"
                                               min="0"
                                               placeholder="0.00">
                                        <span class="input-group-text">L</span>
                                    </div>
                                    @error('quantity')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Client Selection (only when client_provided is true) -->
                                @if($client_provided)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Cliente</label>
                                        <select class="form-select @error('merchant_id') is-invalid @enderror" wire:model="merchant_id">
                                            <option value="">Seleccionar cliente...</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->business_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('merchant_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Cliente que proporciona el producto</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info Display -->
                            @if($product_id && $this->getSelectedProduct())
                                @php $selectedProduct = $this->getSelectedProduct(); @endphp
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="card bg-info">
                                            <div class="card-body">
                                                <h6 class="card-title">Información del Producto</h6>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-white">Stock Actual:</small><br>
                                                        @if($selectedProduct->liters_per_can > 0)
                                                            <strong class="text-white">{{ $selectedProduct->stock * $selectedProduct->liters_per_can }} L ({{ $selectedProduct->stock }} envases)</strong>
                                                        @else
                                                            <strong class="text-white">{{ $selectedProduct->stock }} L</strong>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-white">Litros por Envase:</small><br>
                                                        <strong class="text-white">{{ $selectedProduct->liters_per_can ?: 'N/A' }}</strong>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-white">Marca:</small><br>
                                                        <strong class="text-white">{{ $selectedProduct->commercial_brand ?: 'N/A' }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Notes -->
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Notas</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              wire:model="notes"
                                              rows="3"
                                              placeholder="Observaciones sobre este movimiento de inventario..."></textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                <i class="fa fa-times me-1"></i> Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-{{ $isEditing ? 'save' : 'plus' }} me-1"></i>
                                {{ $isEditing ? 'Actualizar' : 'Crear' }} Movimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
