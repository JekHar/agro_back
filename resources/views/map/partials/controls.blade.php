<div class="btn-group mb-3">
    <button onclick="startDrawing()" class="btn btn-primary">
        <i class="bi bi-pencil me-2"></i>
        Dibujar Lote
    </button>
    <button onclick="exportKML()" class="btn btn-success">
        <i class="bi bi-download me-2"></i>
        Exportar KML
    </button>
    <label class="btn btn-secondary">
        <i class="bi bi-upload me-2"></i>
        Importar KML
        <input type="file" accept=".kml" onchange="importKML(event)" class="d-none">
    </label>
</div>