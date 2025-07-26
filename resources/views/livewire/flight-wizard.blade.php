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
                                                                           wire:model.live="selectedFlightLots.{{ $index }}.hectares_to_apply"
                                                                           class="form-control">
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

                                @if(count($selectedFlightProducts) > 0)
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
                                                        <strong>Productos disponibles:</strong><br>
                                                        <span class="badge bg-info">{{ count($selectedFlightProducts) }}</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Productos seleccionados:</strong><br>
                                                        <span class="badge bg-success">{{ collect($selectedFlightProducts)->where('selected', true)->count() }}</span>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <strong>Método actual:</strong><br>
                                                        <span class="badge bg-warning">
                                                            {{ $calculationMethod === 'by_quantity' ? 'Por Cantidad' : 'Por Dosificación' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-vcenter">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 50px;">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="selectAllProducts">
                                                        </div>
                                                    </th>
                                                    <th>Producto</th>
                                                    <th>Descripción</th>
                                                    <th>Categoría</th>
                                                    <th>Unidad</th>
                                                    @if($calculationMethod === 'by_quantity')
                                                        <th>Cantidad Total</th>
                                                        <th>Dosificación (Calculada)</th>
                                                    @else
                                                        <th>Dosificación por Ha</th>
                                                        <th>Cantidad Total (Calculada)</th>
                                                    @endif
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedFlightProducts as $index => $product)
                                                    <tr class="{{ $product['selected'] ? 'table-success' : '' }}">
                                                        <td class="text-center">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                       wire:click="toggleProductSelection({{ $index }})"
                                                                       {{ $product['selected'] ? 'checked' : '' }}
                                                                       id="product_{{ $index }}">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <label for="product_{{ $index }}" class="fw-bold mb-0" style="cursor: pointer;">
                                                                {{ $product['product_name'] }}
                                                            </label>
                                                        </td>
                                                        <td>
                                                            <small class="text-muted">
                                                                {{ $product['description'] ?? 'Sin descripción' }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">{{ $product['category'] }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary">{{ $product['unit'] }}</span>
                                                        </td>
                                                        @if($calculationMethod === 'by_quantity')
                                                            <td>
                                                                @if($product['selected'])
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="number" step="0.01" min="0"
                                                                               wire:model.live="selectedFlightProducts.{{ $index }}.total_quantity"
                                                                               class="form-control"
                                                                               placeholder="Ej: 20.00">
                                                                        <span class="input-group-text">{{ $product['unit'] }}</span>
                                                                    </div>
                                                                    @error("selectedFlightProducts.{$index}.total_quantity")
                                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($product['selected'])
                                                                    <span class="badge bg-info">
                                                                        {{ number_format($product['dosage_per_hectare'], 2) }} {{ $product['unit'] }}/ha
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        @else
                                                            <td>
                                                                @if($product['selected'])
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="number" step="0.01" min="0"
                                                                               wire:model.live="selectedFlightProducts.{{ $index }}.dosage_per_hectare"
                                                                               class="form-control"
                                                                               placeholder="Ej: 2.50">
                                                                        <span class="input-group-text">{{ $product['unit'] }}/ha</span>
                                                                    </div>
                                                                    @error("selectedFlightProducts.{$index}.dosage_per_hectare")
                                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                                    @enderror
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($product['selected'])
                                                                    <span class="badge bg-info">
                                                                        {{ number_format($product['total_quantity'], 2) }} {{ $product['unit'] }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td>
                                                            @if($product['selected'])
                                                                @if($product['total_quantity'] > 0 && $product['dosage_per_hectare'] > 0)
                                                                    <span class="badge bg-success">
                                                                        <i class="fa fa-check"></i> Configurado
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning">
                                                                        <i class="fa fa-exclamation-triangle"></i> Incompleto
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-secondary">
                                                                    <i class="fa fa-circle"></i> No seleccionado
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <strong>Total productos seleccionados: </strong>
                                                        <span class="badge bg-primary">
                                                            {{ collect($selectedFlightProducts)->where('selected', true)->count() }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <!-- Dosage Summary per Lot -->
                                    @if(collect($selectedFlightProducts)->where('selected', true)->count() > 0)
                                        <div class="mt-4">
                                            <h5 class="mb-3">
                                                <i class="fa fa-chart-bar me-2"></i>Resumen de Dosificación por Lote
                                            </h5>
                                            <div class="row">
                                                @foreach(collect($selectedFlightLots)->where('selected', true) as $lot)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="block block-rounded block-bordered">
                                                            <div class="block-header block-header-default bg-light">
                                                                <h6 class="block-title mb-0">
                                                                    Lote {{ $lot['lot_number'] }}
                                                                    <small class="text-muted">({{ number_format($lot['hectares_to_apply'], 2) }} ha)</small>
                                                                </h6>
                                                            </div>
                                                            <div class="block-content p-3">
                                                                @foreach(collect($selectedFlightProducts)->where('selected', true) as $product)
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <span class="fw-medium">{{ $product['product_name'] }}:</span>
                                                                        <div class="text-end">
                                                                            <small class="text-muted d-block">
                                                                                {{ number_format($product['dosage_per_hectare'], 2) }} {{ $product['unit'] }}/ha
                                                                            </small>
                                                                            <span class="badge bg-primary">
                                                                                {{ number_format($product['dosage_per_hectare'] * $lot['hectares_to_apply'], 2) }} {{ $product['unit'] }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <i class="fa fa-flask fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No hay productos disponibles para este vuelo.</p>
                                        <small class="text-muted">Los productos se cargan automáticamente desde el catálogo disponible para el cliente.</small>
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
