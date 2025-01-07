<div>
    <div id="map" style="height: 600px;" wire:ignore></div>

    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <div class="btn-group">
                    <button onclick="startDrawing()" class="btn btn-primary">Dibujar Lote</button>
                    <button onclick="exportKML()" class="btn btn-success">Exportar KML</button>
                    <button onclick="importKML()" class="btn btn-success">Importar KML</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="mb-3">
            <input type="file"
                id="kmlFileInput"
                accept=".kml"
                onchange="handleKMLImport(event)"
                class="d-none">
        </div>
        <div class="mb-3">
            <label for="merchant_id" class="form-label">Merchant</label>
            <select wire:model="merchant_id" id="merchant_id" class="form-select">
                <option value="">Seleccione un Merchant</option>
                @foreach($merchants as $merchant)
                <option value="{{ $merchant->id }}">{{ $merchant->business_name }}</option>
                @endforeach
            </select>
            @error('merchant_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="number" class="form-label">Número de Lote</label>
            <input type="number"
                wire:model="number"
                id="number"
                class="form-control">
            @error('number')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="hectares" class="form-label">Hectáreas</label>
            <input type="number"
                wire:model="hectares"
                id="hectares"
                step="0.001"
                class="form-control">
            @error('hectares')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <h6 class="card-title">Coordenadas del lote:</h6>
        <pre id="coordinates" wire:ignore></pre>
    </div>
    <button wire:click="saveLot" class="btn btn-primary">
        Guardar Lote
    </button>
</div>