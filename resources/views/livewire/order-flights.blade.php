<div>
    <!-- Include Flight Wizard Component -->
    @livewire('flight-wizard', [
        'orderId' => $orderId ?? null,
        'clientId' => $clientId,
        'orderLots' => $orderLots ?? [],
        'orderProducts' => $products
    ])

    <div class="block block-rounded mb-2">
        <div class="block-header block-header-default bg-primary">
            <h3 class="block-title text-white">
                <i class="fa fa-plane me-2"></i>CONFIGURACIÓN DE VUELOS
            </h3>
            <div class="block-options">
                <button type="button"
                        class="btn btn-success btn-sm"
                        wire:click="openFlightWizard"
                        {{ !$clientId ? 'disabled' : '' }}>
                    <i class="fa fa-plus me-2"></i>AGREGAR VUELO
                </button>
            </div>
        </div>
        <div class="block-content">
            <!-- Flight Summary -->
            <div class="row align-items-center mb-4">
                <div class="col-md-4">
                    <div class="bg-info-light p-3 rounded">
                        <h6 class="mb-1 text-info">HECTÁREAS TOTALES</h6>
                        <p class="h4 mb-0 text-info">{{ number_format($totalHectares, 2) }} ha</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-success-light p-3 rounded">
                        <h6 class="mb-1 text-success">VUELOS CONFIGURADOS</h6>
                        <p class="h4 mb-0 text-success">{{ count($flights) }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-{{ $remainingHectares < 0 ? 'danger' : 'info' }}-light p-3 rounded">
                        <h6 class="mb-1 text-{{ $remainingHectares < 0 ? 'danger' : 'info' }}">HECTÁREAS RESTANTES</h6>
                        <p class="h4 mb-0 text-{{ $remainingHectares < 0 ? 'danger' : 'info' }}">
                            {{ number_format($remainingHectares, 2) }} ha
                        </p>
                    </div>
                </div>
            </div>

            @if($remainingHectares < 0)
                <div class="alert alert-danger d-flex align-items-center mb-4">
                    <i class="fa fa-exclamation-triangle me-2"></i>
                    <strong>Atención:</strong> Se han excedido las hectáreas totales por {{ number_format(abs($remainingHectares), 2) }} hectáreas.
                </div>
            @endif

            <!-- Flight List -->
            @if(count($flights) > 0)
                @foreach($flights as $flightIndex => $flight)
                    <div class="block block-rounded block-themed border border-primary mb-3">
                        <div class="block-header bg-warning">
                            <h3 class="block-title">
                                <i class="fa fa-plane me-2"></i>VUELO {{ $flightIndex + 1 }}
                            </h3>
                            <div class="block-options">
                                <span class="badge bg-primary">{{ number_format($flight['total_hectares'] ?? 0, 2) }} ha</span>
                                <button type="button" class="btn btn-danger btn-sm ms-2" wire:click="removeFlight({{ $flightIndex }})">
                                    <i class="fa fa-trash"></i> Eliminar vuelo
                                </button>
                            </div>
                        </div>
                        <div class="block-content">
                            <!-- Flight Lots -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="mb-3">
                                        <i class="fa fa-map-marked-alt me-2"></i>Lotes del Vuelo
                                    </h6>
                                    @if(isset($flight['lots']) && count($flight['lots']) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Lote</th>
                                                        <th class="text-center">Hectáreas del Lote</th>
                                                        <th class="text-center">Hectáreas a Aplicar</th>
                                                        <th class="text-center">% del Lote</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($flight['lots'] as $lot)
                                                        @php
                                                            $lot['lot_hectares'] = $lot['lot_total_hectares'];
                                                            $lotData = $availableLots->firstWhere('id', $lot['lot_id']);
                                                            $percentage = $lot['lot_hectares'] > 0 ? ($lot['hectares_to_apply'] / $lot['lot_hectares']) * 100 : 0;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <strong>Lote {{ $lotData->number ?? $lot['lot_id'] }}</strong>
                                                                @if($lotData && $lotData->name)
                                                                    <br><small class="text-muted">{{ $lotData->name }}</small>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-secondary">{{ number_format($lot['lot_hectares'] ?? 0, 2) }} ha</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-primary">{{ number_format($lot['hectares_to_apply'] ?? 0, 2) }} ha</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-info">{{ number_format($percentage, 1) }}%</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3 text-muted">
                                            <i class="fa fa-map-marked-alt fa-2x mb-2"></i>
                                            <p>No hay lotes configurados para este vuelo</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Flight Products -->
                            @if(isset($flight['products']) && count($flight['products']) > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="mb-3">
                                            <i class="fa fa-flask me-2"></i>Productos del Vuelo
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Producto</th>
                                                        <th class="text-center">Dosificación</th>
                                                        <th class="text-center">Cantidad Total Litros</th>
                                                        <th class="text-center">Cantidad Total Envases</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($flight['products'] as $productItem)
                                                        @php
                                                            $productData = collect($products)->firstWhere('product_id', $productItem['product_id']);
                                                            $productInfo = collect($availableProducts)->firstWhere('id', $productItem['product_id']);
                                                            $dosage = $productItem['calculated_dosage_per_hectare'] ?? 0;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $productItem['name'] ?? 'Producto #' . $productItem['product_id'] }}</strong>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-info">{{ number_format($dosage, 3) }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-success">{{ number_format($productItem['quantity'] ?? 0, 2) }} {{ $productInfo->unit ?? 'L' }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-warning">
                                                                    @if(array_key_exists('liters_per_can', $productItem))
                                                                        {{ number_format($productItem['quantity'] / ($productItem['liters_per_can'] != 0 ? $productItem['liters_per_can'] : 1)) }} envases
                                                                    @else
                                                                        N/A
                                                                    @endif
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Dosage Summary per Lot -->
{{--                            @if(isset($flight['lots']) && count($flight['lots']) > 0 && isset($flight['products']) && count($flight['products']) > 0)--}}
{{--                                <div class="row mt-4">--}}
{{--                                    <div class="col-12">--}}
{{--                                        <h6 class="mb-3">--}}
{{--                                            <i class="fa fa-chart-bar me-2"></i>Resumen de Aplicación por Lote--}}
{{--                                        </h6>--}}
{{--                                        <div class="row">--}}
{{--                                            @foreach($flight['lots'] as $lot)--}}
{{--                                                @php--}}
{{--                                                    $lotData = $availableLots->firstWhere('id', $lot['lot_id']);--}}
{{--                                                @endphp--}}
{{--                                                <div class="col-md-6 mb-3">--}}
{{--                                                    <div class="block block-rounded block-bordered">--}}
{{--                                                        <div class="block-header block-header-default bg-light">--}}
{{--                                                            <h6 class="block-title mb-0">--}}
{{--                                                                Lote {{ $lotData->number ?? $lot['lot_id'] }}--}}
{{--                                                                <small class="text-muted">({{ number_format($lot['hectares_to_apply'], 2) }} ha)</small>--}}
{{--                                                            </h6>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="block-content p-3">--}}
{{--                                                            @foreach($flight['products'] as $productItem)--}}
{{--                                                                @php--}}
{{--                                                                    $productInfo = collect($availableProducts)->firstWhere('id', $productItem['product_id']);--}}
{{--                                                                    $dosage = $productItem['dosage_per_hectare'] ?? 0;--}}
{{--                                                                    $totalForLot = $dosage * $lot['hectares_to_apply'];--}}
{{--                                                                @endphp--}}
{{--                                                                <div class="d-flex justify-content-between align-items-center mb-2">--}}
{{--                                                                    <span class="fw-medium">{{ $productInfo->name ?? 'Producto' }}:</span>--}}
{{--                                                                    <div class="text-end">--}}
{{--                                                                        <small class="text-muted d-block">--}}
{{--                                                                            {{ number_format($dosage, 3) }} {{ $productInfo->unit ?? 'L' }}/ha--}}
{{--                                                                        </small>--}}
{{--                                                                        <span class="badge bg-primary">--}}
{{--                                                                            {{ number_format($totalForLot, 2) }} {{ $productInfo->unit ?? 'L' }}--}}
{{--                                                                        </span>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            @endforeach--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fa fa-plane fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted mb-3">No hay vuelos configurados</h5>
                    <p class="text-muted mb-4">
                        Utilice el botón "ADD FLIGHT" para configurar vuelos usando el asistente guiado.
                        <br>El asistente le permitirá seleccionar lotes y configurar productos con dosificación precisa.
                    </p>
                    @if($clientId)
                        <button type="button"
                                class="btn btn-primary btn-lg"
                                wire:click="openFlightWizard">
                            <i class="fa fa-plus me-2"></i>Crear Primer Vuelo
                        </button>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i>
                            Debe seleccionar un cliente antes de configurar vuelos.
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
