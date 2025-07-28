<div>
    <!-- Flight Wizard Modal -->
    <div class="modal fade" id="flightWizardModal" tabindex="-1" aria-labelledby="flightWizardModalLabel" aria-hidden="true"
         wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="flightWizardModalLabel">
                        <i class="fa fa-plane me-2"></i>Configurar Vuelo
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Progress Steps -->
                <div class="modal-header bg-light">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="step-indicator {{ $currentStep >= 1 ? 'active' : '' }}">
                                            <span class="step-number">1</span>
                                            <span class="step-title">Lotes y Áreas</span>
                                        </div>
                                        <div class="step-divider"></div>
                                        <div class="step-indicator {{ $currentStep >= 2 ? 'active' : '' }}">
                                            <span class="step-number">2</span>
                                            <span class="step-title">Productos y Dosificación</span>
                                        </div>
                                    </div>
                                    <small class="text-muted">Paso {{ $currentStep }} de {{ $maxSteps }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    @if($currentStep === 1)
                        <!-- Step 1: Lot and Area Selection -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">
                                    <i class="fa fa-map-marked-alt me-2"></i>Selección de Lotes y Áreas
                                </h3>
                            </div>
                            <div class="block-content">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle me-2"></i>
                                            <strong>Instrucciones:</strong> Seleccione los lotes a tratar y defina las hectáreas a aplicar para cada uno.
                                        </div>
                                    </div>
                                </div>

                                @if(count($selectedFlightLots) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-vcenter">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 60px;">Seleccionar</th>
                                                    <th>Lote</th>
                                                    <th>Hectáreas Totales</th>
                                                    <th>Hectáreas Restantes</th>
                                                    <th>Hectáreas a Aplicar</th>
                                                    <th style="width: 150px;">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedFlightLots as $index => $lot)
                                                    <tr class="{{ $lot['selected'] ? 'table-success' : '' }}">
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                       wire:click="toggleLotSelection({{ $index }})"
                                                                       {{ $lot['selected'] ? 'checked' : '' }}>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <strong>{{ $lot['lot_number'] }}</strong>
                                                            @if($lot['lot_name'])
                                                                <br><small class="text-muted">{{ $lot['lot_name'] }}</small>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ number_format($lot['total_hectares'], 2) }} ha</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ number_format($lot['remaining_hectares'], 2) }} ha</span>
                                                        </td>
                                                        <td>
                                                            @if($lot['selected'])
                                                                <div class="input-group input-group-sm">
                                                                    <input type="number" step="0.01" min="0"
                                                                           max="{{ $lot['remaining_hectares'] }}"
                                                                           wire:model.lazy="selectedFlightLots.{{ $index }}.hectares_to_apply"
                                                                           class="form-control"
                                                                           onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                                                                    <span class="input-group-text">ha</span>
                                                                </div>
                                                                @error("selectedFlightLots.{$index}.hectares_to_apply")
                                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                                @enderror
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($lot['selected'] && $lot['remaining_hectares'] > 0)
                                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                                        wire:click="useRemainingHectares({{ $index }})"
                                                                        title="Usar todas las hectáreas restantes">
                                                                    <i class="fa fa-arrows-alt"></i> Usar total
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="4" class="text-end"><strong>Total Hectáreas del Vuelo:</strong></td>
                                                    <td colspan="2">
                                                        <span class="badge bg-primary fs-6">{{ number_format($totalFlightHectares, 2) }} ha</span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fa fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay lotes disponibles para este vuelo.</p>
                                        <small class="text-muted">Asegúrese de haber seleccionado lotes en la pestaña "Lotes" del formulario de orden.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif($currentStep === 2)
                        <!-- Step 2: Product Selection and Dosage -->
                        <div class="block block-rounded">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">
                                    <i class="fa fa-flask me-2"></i>Productos y Dosificación
                                </h3>
                            </div>
                            <div class="block-content">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle me-2"></i>
                                            <strong>Total de hectáreas del vuelo:</strong> {{ number_format($totalFlightHectares, 2) }} ha
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Método de Cálculo</label>
                                            <select wire:model.live="calculationMethod" class="form-select">
                                                <option value="by_quantity">Por Cantidad de Producto</option>
                                                <option value="by_dosage">Por Dosificación</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Information Summary -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-light border">
                                            <h6 class="mb-2"><i class="fa fa-calculator me-2"></i>Información para Cálculos</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>Hectáreas del vuelo:</strong><br>
                                                    <span class="badge bg-primary">{{ number_format($totalFlightHectares, 2) }} ha</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Productos agregados:</strong><br>
                                                    <span class="badge bg-info">{{ count($selectedFlightProducts) }}</span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Método actual:</strong><br>
                                                    <span class="badge bg-warning">
                                                        {{ $calculationMethod === 'by_quantity' ? 'Por Cantidad' : 'Por Dosificación' }}
                                                    </span>
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>Total productos disponibles:</strong><br>
                                                    <span class="badge bg-secondary">{{ count($this->getAvailableProductsForSelector()) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Selector -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0"><i class="fa fa-plus-circle me-2"></i>Agregar Producto</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <select wire:model="selectedProductId" class="form-select">
                                                            <option value="">Selecciona un producto...</option>
                                                            @foreach($this->getAvailableProductsForSelector() as $product)
                                                                <option value="{{ $product['id'] }}">
                                                                    {{ $product['name'] }}
                                                                    ({{ $product['category'] }})
                                                                    - Stock: {{ number_format($product['stock'], 2) }} {{ $product['unit'] }}
                                                                    @if($product['dosage_per_hectare'] > 0)
                                                                        - Dosificación: {{ number_format($product['dosage_per_hectare'], 2) }} {{ $product['unit'] }}/ha
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" wire:click="addProduct" class="btn btn-success">
                                                            <i class="fa fa-plus"></i> Agregar Producto
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Products Cards -->
                                @if(count($selectedFlightProducts) > 0)
                                    <div class="row">
                                        @foreach($selectedFlightProducts as $index => $product)
                                            <div class="col-md-6 mb-3">
                                                <div class="card border-primary">
                                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                        <h6 class="mb-0">
                                                            <i class="fa fa-flask me-2"></i>{{ $product['product_name'] }}
                                                        </h6>
                                                        <button type="button" wire:click="removeProduct({{ $index }})"
                                                                class="btn btn-sm btn-outline-light">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="card-body bg-body-dark">
                                                        <!-- Product Information -->
                                                        <div class="row mb-4">
                                                            <div class="col-4">
                                                                <small class="text-muted">Categoría:</small><br>
                                                                <span class="badge bg-info">{{ $product['category'] }}</span>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-muted">Unidad:</small><br>
                                                                <span class="badge bg-secondary">{{ $product['unit'] }}</span>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-muted">Dosage:</small><br>
                                                                <span class="badge bg-secondary">{{ number_format($product['dosage_per_hectare'], 2) }} {{ $product['unit'] }}/ha</span>
                                                            </div>
                                                        </div>

                                                        <!-- Stock and Can Information -->
                                                        <div class="row mb-3">
                                                            <div class="col-4">
                                                                <small class="text-muted">Stock disponible:</small><br>
                                                                <strong class="text-success">{{ number_format($product['stock'], 2) }} E</strong>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-muted">Litros/Envase:</small><br>
                                                                <strong class="text-primary">{{ number_format($product['liters_per_can'], 2) }} {{ $product['unit'] }}</strong>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="text-muted">Envases necesarios:</small><br>
                                                                <strong class="text-primary">
                                                                    {{ $product['total_quantity'] > 0 && $product['liters_per_can'] > 0 ? ceil($product['total_quantity'] / $product['liters_per_can']) : '-' }}
                                                                </strong>
                                                            </div>
                                                        </div>

                                                        @if($product['commercial_brand'])
                                                            <div class="mb-3">
                                                                <small class="text-muted">Marca comercial:</small>
                                                                <span class="badge bg-light text-dark">{{ $product['commercial_brand'] }}</span>
                                                            </div>
                                                        @endif

                                                        <!-- Calculation Inputs -->
                                                        @if($calculationMethod === 'by_dosage')
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label class="form-label small">Dosificación por Ha</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="number" step="0.01" min="0" max="999.99"
                                                                               wire:model.lazy="selectedFlightProducts.{{ $index }}.calculated_dosage_per_hectare"
                                                                               wire:change="updateProductCalculation({{ $index }})"
                                                                               class="form-control"
                                                                               placeholder="Ej: 2.50"
                                                                               onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                                                                        <span class="input-group-text">{{ $product['unit'] }}/ha</span>
                                                                    </div>
                                                                    @error("selectedFlightProducts.{$index}.calculated_dosage_per_hectare")
                                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label small text-dark">Cantidad Total (Calculada)</label>
                                                                    <div class="form-control form-control-sm bg-dark">
                                                                        {{ number_format($product['total_quantity'], 2) }} {{ $product['unit'] }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <label class="form-label small">Cantidad Total</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="number" step="0.01" min="0" max="99999.99"
                                                                               wire:model.lazy="selectedFlightProducts.{{ $index }}.total_quantity"
                                                                               wire:change="updateProductCalculation({{ $index }})"
                                                                               class="form-control"
                                                                               placeholder="Ej: 20.00"
                                                                               onblur="this.value = parseFloat(this.value || 0).toFixed(2)">
                                                                        <span class="input-group-text">{{ $product['unit'] }}</span>
                                                                    </div>
                                                                    @error("selectedFlightProducts.{$index}.total_quantity")
                                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-6">
                                                                    <label class="form-label small text-dark">Dosificación por Ha (Calculada)</label>
                                                                    <div class="form-control form-control-sm bg-dark">
                                                                        {{ number_format($product['calculated_dosage_per_hectare'], 2) }} {{ $product['unit'] }}/ha
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <!-- Stock Validation -->
                                                        @if($product['total_quantity'] > $product['stock'])
                                                            <div class="alert alert-warning mt-2 py-2">
                                                                <small><i class="fa fa-exclamation-triangle me-1"></i>
                                                                Cantidad requerida ({{ number_format($product['total_quantity'], 2) }}) excede el stock disponible ({{ number_format($product['stock'], 2) }})
                                                                </small>
                                                            </div>
                                                        @elseif($product['total_quantity'] > 0 && $product['dosage_per_hectare'] > 0)
                                                            <div class="alert alert-success mt-2 py-2">
                                                                <small><i class="fa fa-check me-1"></i>Producto configurado correctamente</small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle me-2"></i>
                                        No hay productos agregados. Utiliza el selector de arriba para agregar productos al vuelo.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            @if($currentStep > 1)
                                <button type="button" class="btn btn-outline-secondary" wire:click="previousStep">
                                    <i class="fa fa-arrow-left me-2"></i>Anterior
                                </button>
                            @endif
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            @if($currentStep < $maxSteps)
                                <button type="button" class="btn btn-primary" wire:click="nextStep">
                                    Siguiente <i class="fa fa-arrow-right ms-2"></i>
                                </button>
                            @else
                                <button type="button" class="btn btn-success" wire:click="saveFlight">
                                    <i class="fa fa-check me-2"></i>Crear Vuelo
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for step indicators -->
    <style>
        .step-indicator {
            display: flex;
            align-items: center;
            padding: 10px 18px;
            border-radius: 25px;
            background: #6c757d;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .step-indicator.active {
            background: #0d6efd;
            color: white;
            transform: scale(1.05);
        }

        .step-number {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-right: 10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .step-indicator.active .step-number {
            background: white;
            color: #0d6efd;
            border-color: white;
        }

        .step-title {
            font-weight: 600;
            font-size: 14px;
        }

        .step-divider {
            width: 50px;
            height: 3px;
            background: #dee2e6;
            margin: 0 20px;
            border-radius: 2px;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- JavaScript for modal handling -->
    <script>
        document.addEventListener('livewire:init', () => {
            let modal = null;

            // Initialize modal when component loads
            if (document.getElementById('flightWizardModal')) {
                modal = new bootstrap.Modal(document.getElementById('flightWizardModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
            }

            // Listen for wizard open event
            Livewire.on('openFlightWizard', () => {
                if (modal) {
                    modal.show();
                }
            });

            // Handle modal close events
            document.getElementById('flightWizardModal')?.addEventListener('hidden.bs.modal', function () {
                @this.closeWizard();
            });

            // Watch for showModal changes from Livewire
            Livewire.hook('morph.updated', () => {
                if (@this.showModal && modal && !document.querySelector('.modal-backdrop')) {
                    modal.show();
                } else if (!@this.showModal && modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>
