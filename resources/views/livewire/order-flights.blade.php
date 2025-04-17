<div>
    <div class="block block-rounded mb-2">
        <div class="block-header block-header-default bg-primary">
            <h3 class="block-title text-white">VUELOS</h3>
        </div>
        <div class="block-content">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <p class="fw-bold">HECTÁREAS TOTALES: {{ number_format($totalHectares, 1) }}</p>
                </div>
                <div class="col-md-6">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <label class="col-form-label">CANTIDAD VUELOS:</label>
                        </div>
                        <div class="col-auto">
                            <select wire:model.live="flightCount" class="form-select" {{ !$clientId ? 'disabled' : '' }}>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($flights as $flightIndex => $flight)
                <div class="block block-rounded block-themed border border-primary mb-3">
                    <div class="block-header bg-warning">
                        <h3 class="block-title">VUELO {{ $flightIndex + 1 }}</h3>
                    </div>
                    <div class="block-content">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Cantidad de hectáreas a realizar</label>
                                <input type="number"
                                       wire:model.live="flights.{{ $flightIndex }}.hectares_to_perform"
                                       class="form-control"
                                       step="0.1"
                                       min="0"
                                       max="{{ $totalHectares }}"
                                    {{ !$clientId ? 'disabled' : '' }}>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div>
                                    @if($remainingHectares < 0)
                                        <span class="badge bg-danger">Excedido por {{ abs($remainingHectares) }} hectáreas</span>
                                    @else
                                        <span class="badge bg-success">Quedan {{ $remainingHectares }} hectáreas</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Selección de lotes y orden</h5>

                        @foreach($flight['lots'] as $lotIndex => $lot)
                            <div class="row mb-2 align-items-center">
                                <div class="col-md-4">
                                    <select wire:model.live="flights.{{ $flightIndex }}.lots.{{ $lotIndex }}.lot_id"
                                            class="form-select"
                                        {{ !$clientId ? 'disabled' : '' }}>
                                        <option value="">Seleccione lote</option>
                                        @foreach($availableLots as $availableLot)
                                            <option value="{{ $availableLot->id }}">
                                                Lote {{ $availableLot->number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 text-center">
                                    <span class="fw-bold">Has del lote</span><br>
                                    <span>{{ number_format($lot['lot_hectares'] ?? 0, 1) }}</span>
                                </div>
                                <div class="col-md-3 text-center">
                                    <span class="fw-bold">Has a aplicar por lote</span><br>
                                    <span>{{ number_format($lot['hectares_to_apply'] ?? 0, 1) }}</span>
                                </div>
                                <div class="col-md-2">
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            wire:click="removeLotFromFlight({{ $flightIndex }}, {{ $lotIndex }})"
                                        {{ (count($flight['lots']) <= 1 || !$clientId) ? 'disabled' : '' }}>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        wire:click="addLotToFlight({{ $flightIndex }})"
                                    {{ !$clientId ? 'disabled' : '' }}>
                                    <i class="fa fa-plus me-1"></i> AGREGAR LOTE
                                </button>
                            </div>
                        </div>

                        @if(count($products) > 0)
                            <h5 class="mb-3">Productos para este vuelo</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-body-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Dosis</th>
                                        <th class="text-center">Cantidad total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($flight['products'] as $productItem)
                                        @php
                                            $productData = collect($products)->firstWhere('product_id', $productItem['product_id']);
                                            $productName = '';

                                            if ($productData && $productData['product_id']) {
                                                foreach ($availableProducts as $availableProduct) {
                                                    if ($availableProduct->id == $productData['product_id']) {
                                                        $productName = $availableProduct->name;
                                                        break;
                                                    }
                                                }
                                            }

                                            if (empty($productName)) {
                                                $productName = 'Producto #' . $productItem['product_id'];
                                            }

                                            $dosage = $productData ? ($productData['calculated_dosage'] ?? 0) : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $productName }}</td>
                                            <td class="text-center">{{ number_format($dosage, 2) }}</td>
                                            <td class="text-center">{{ number_format($productItem['quantity'] ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
