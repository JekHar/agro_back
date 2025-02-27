let map, drawnItems;

function initializeMap() {
    map = L.map('map').setView([-34.6037, -58.3816], 13);
    
    const satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    const terrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    satellite.addTo(map);

    L.control.layers({
        "Satélite": satellite,
        "Terreno": terrain
    }).addTo(map);
}

function setupDrawingControls() {
    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const drawControl = new L.Control.Draw({
        draw: {polygon: {
                allowIntersection: false,
                showArea: true,
                metric: true,
                drawError: {
                    color: '#ff6600',
                    timeout: 1000
                },
                shapeOptions: {
                    color: '#ff6600',
                    fillColor: 'orange',
                    fillOpacity: 0.3 
                },
                completeShape: true
            },
            rectangle: false,
            circle: false,
            circlemarker: false,
            marker: false,
            polyline: false
        },
        edit: {
            featureGroup: drawnItems,
            remove: true,
            edit: true
        }
    });
    



    map.addControl(drawControl);
    map.on('draw:created', handleDrawCreated);
    map.on('draw:edited', handleDrawEdited);
}

function handleDrawCreated(e) {
    const layer = e.layer;
    drawnItems.clearLayers();
    drawnItems.addLayer(layer);

    const coords = layer.getLatLngs()[0];
    const area = L.GeometryUtil.geodesicArea(coords);
    const hectares = (area / 10000).toFixed(3);

    const formattedCoords = coords.map(point => ({
        lat: point.lat,
        lng: point.lng
    }));

    updateCoordinatesDisplay(formattedCoords, hectares);

    Livewire.dispatch('updateCoordinates', {
        coords: formattedCoords,
        hectares: hectares
    });
}

function handleDrawEdited(e) {
    const layers = e.layers;
    layers.eachLayer(function(layer) {
        const coords = layer.getLatLngs()[0];
        const area = L.GeometryUtil.geodesicArea(coords);
        const hectares = (area / 10000).toFixed(3);
        
        const formattedCoords = coords.map(point => ({
            lat: point.lat,
            lng: point.lng
        }));

        updateCoordinatesDisplay(formattedCoords, hectares);
        
        Livewire.dispatch('updateCoordinates', {
            coords: formattedCoords,
            hectares: hectares
        });
    });
}

function startDrawing() {
    if (drawnItems.getLayers().length > 0) {
        const userConfirmed = confirm('Ya existe un polígono dibujado. ¿Desea eliminarlo para dibujar uno nuevo?');
        if (!userConfirmed) {
            return;
        }
        drawnItems.clearLayers(); 
    }
    new L.Draw.Polygon(map, {
        shapeOptions: {
            color: '#ff6600',
            fillColor: 'orange',
            fillOpacity: 0.3
        }
    }).enable();
}
function editButton() {
    if (drawnItems.getLayers().length > 0) {
        const layer = drawnItems.getLayers()[0];
        layer.editing.enable();
        document.getElementById('saveButton').style.display = 'inline-block'; 
    } else {
        alert('No hay ningún polígono para editar.');
    }
}

function saveDrawing() {
    if (drawnItems.getLayers().length > 0) {
        const layer = drawnItems.getLayers()[0];
        layer.editing.disable(); 

        map.fire('draw:edited', { layers: drawnItems });
        document.getElementById('saveButton').style.display = 'none';
    } else {
        alert('No hay ningún polígono para guardar.');
    }
}


function exportKML() {
    const geojson = drawnItems.toGeoJSON();
    const kml = tokml(geojson);
    
    const blob = new Blob([kml], { type: 'text/xml' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'lotes.kml';
    a.click();
}

document.addEventListener('livewire:init', () => {
    initializeMap();
    setupDrawingControls();

    Livewire.on('lot-loaded', (data) => {
    drawnItems.clearLayers();
    
    const coordinates = data[0]?.coordinates;
    const hectares = data[0]?.hectares;
    
    if (coordinates && coordinates.length > 0) {

        const coords = coordinates.map(coord => [
            parseFloat(coord.lat),
            parseFloat(coord.lng)
        ]);
        
        const polygon = L.polygon(coords, {
            color: 'orange'
        });
        drawnItems.addLayer(polygon);
        
        updateCoordinatesDisplay(coordinates, hectares);
        
        map.fitBounds(polygon.getBounds());
    }
});
});

function importKML() {
    document.getElementById('kmlFileInput').click();
}

function handleKMLImport(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const parser = new DOMParser();
            const kml = parser.parseFromString(e.target.result, 'text/xml');

            drawnItems.clearLayers();
            
            const coordinates = kml.getElementsByTagName('coordinates');
            let bounds = L.latLngBounds([]);
            
            for (let coord of coordinates) {
                const points = parseKMLCoordinates(coord.textContent);
                
                if (points.length > 2) {
                    const latLngs = points.map(point => L.latLng(point[0], point[1]));
                    
                    const polygon = L.polygon(latLngs, { 
                        color: '#ff6600',
                        fillColor: 'orange',
                        fillOpacity: 0.3
                    });
                    drawnItems.addLayer(polygon);
                    bounds.extend(polygon.getBounds());

                    const polygonCoords = polygon.getLatLngs()[0];
                    
                    const area = L.GeometryUtil.geodesicArea(polygonCoords);
                    const hectares = (area / 10000).toFixed(3);
                    
                    const formattedCoords = polygonCoords.map(point => ({
                        lat: point.lat,
                        lng: point.lng
                    }));
                    
                    updateCoordinatesDisplay(formattedCoords, hectares);
                    
                    Livewire.dispatch('updateCoordinates', {
                        coords: formattedCoords,
                        hectares: hectares
                    });
                }
            }
            
            if (bounds.isValid()) {
                map.fitBounds(bounds, {
                    padding: [50, 50],
                    maxZoom: 18
                });
            }
        } catch (error) {
            console.error('Error parsing KML file:', error);
            alert('Error al cargar el archivo KML. Por favor, verifique el formato del archivo.');
        }
        event.target.value = '';
    };
    reader.readAsText(file);
}

function parseKMLCoordinates(coordText) {
    return coordText.trim()
        .split(/\s+/)
        .map(point => {
            const [lng, lat] = point.split(',').map(Number);
            return [lat, lng];
        })
        .filter(coord => !isNaN(coord[0]) && !isNaN(coord[1]));
}

function updateCoordinatesDisplay(coords, hectares) {
    const coordsElement = document.getElementById('coordinates');
    if (coordsElement) {
        coordsElement.innerText = `Área: ${hectares} hectáreas\n\nCoordenadas:\n${JSON.stringify(coords, null, 2)}`;
    }
}