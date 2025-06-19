let map, drawnItems;
let cropMode = false;
let cropPolygon = null;

function initializeMap() {
    map = L.map('map', { zoomControl: false }).setView([-34.6037, -58.3816], 13);
    
    // Use hybrid layer (satellite + roads) instead of just satellite
    const satellite = L.tileLayer('http://{s}.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    const terrain = L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });

    satellite.addTo(map);

    // Translate layer control labels
    const layerLabels = {
        "en": { "satellite": "Satellite", "terrain": "Terrain", "zoomIn": "Zoom in", "zoomOut": "Zoom out" },
        "es": { "satellite": "Satélite", "terrain": "Terreno", "zoomIn": "Acercar", "zoomOut": "Alejar" }
    };

    // Get current language from HTML lang attribute or default to English
    const lang = document.documentElement.lang || 'en';
    const labels = layerLabels[lang] || layerLabels['en'];

    L.control.layers({
        [labels.satellite]: satellite,
        [labels.terrain]: terrain
    }).addTo(map);
    
    L.control.zoom({
        zoomInTitle: labels.zoomIn,
        zoomOutTitle: labels.zoomOut
    }).addTo(map);
    // Estilos para los botones
    const style = document.createElement('style');
    style.innerHTML = `
        .leaflet-bar a {
            background-color: white !important;
            font-weight: bold !important;
        }
        .leaflet-draw-actions {
            background-color: #151211 !important;
            font-weight: bold !important;
        }
        .leaflet-draw-actions a {
            background-color: #151211 !important;
            font-weight: bold !important;
            display: inline-block;
        }
        /* Make vertex points smaller */
        .leaflet-editing-icon {
            width: 8px !important;
            height: 8px !important;
            margin-left: -4px !important;
            margin-top: -4px !important;
            border-radius: 4px !important;
        }
    `;
    document.head.appendChild(style);
}

function setupDrawingControls() {
    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    const translations = {
        "en": {
            "drawPolygon": "Draw a polygon",
            "drawRectangle": "Draw a rectangle",
            "drawCircle": "Draw a circle",
            "drawMarker": "Draw a marker",
            "drawPolyline": "Draw a polyline",
            "cancel": "Cancel drawing",
            "finish": "Finish drawing",
            "undo": "Delete last point",
            "startTooltip": "Click to start drawing shape",
            "contTooltip": "Click to continue drawing shape",
            "endTooltip": "Click first point to close this shape",
            "edit": "Edit shapes",
            "editDisabled": "No shapes to edit",
            "remove": "Delete shapes",
            "removeDisabled": "No shapes to delete",
            "save": "Save changes",
            "cancelEdit": "Cancel editing",
            "editTooltip": "Drag handles to edit shapes",
            "editSubTooltip": "Click cancel to undo changes",
            "removeTooltip": "Click a shape to remove",
        },
        "es": {
            "drawPolygon": "Dibujar polígono",
            "drawRectangle": "Dibujar rectángulo",
            "drawCircle": "Dibujar círculo",
            "drawMarker": "Dibujar marcador",
            "drawPolyline": "Dibujar línea",
            "cancel": "Cancelar dibujo",
            "finish": "Finalizar dibujo",
            "undo": "Eliminar último punto",
            "startTooltip": "Haz clic para empezar a dibujar la forma",
            "contTooltip": "Haz clic para continuar dibujando la forma",
            "endTooltip": "Haz clic en el primer punto para cerrar la forma",
            "edit": "Editar polígonos",
            "editDisabled": "No hay polígonos para editar",
            "remove": "Eliminar polígonos",
            "removeDisabled": "No hay polígonos para eliminar",
            "save": "Guardar cambios",
            "cancelEdit": "Cancelar edición",
            "editTooltip": "Arrastra los puntos para editar las formas",
            "editSubTooltip": "Haz clic en cancelar para deshacer los cambios",
            "removeTooltip": "Haz clic en una forma para eliminarla",
            "crop": "Recortar área",
            "cropTooltip": "Haz clic para dibujar el área a recortar",
            "cropCancel": "Cancelar recorte",
        }
    };

    const lang = document.documentElement.lang || 'en';
    const t = translations[lang] || translations['en'];

    L.drawLocal.draw.toolbar.buttons.polygon = t.drawPolygon;
    L.drawLocal.draw.toolbar.buttons.rectangle = t.drawRectangle;
    L.drawLocal.draw.toolbar.buttons.circle = t.drawCircle;
    L.drawLocal.draw.toolbar.buttons.marker = t.drawMarker;
    L.drawLocal.draw.toolbar.buttons.polyline = t.drawPolyline;
    
    L.drawLocal.draw.toolbar.actions.title = t.cancel;
    L.drawLocal.draw.toolbar.actions.text = t.cancel;
    L.drawLocal.draw.toolbar.finish.title = t.finish;
    L.drawLocal.draw.toolbar.finish.text = t.finish;
    L.drawLocal.draw.toolbar.undo.title = t.undo;
    L.drawLocal.draw.toolbar.undo.text = t.undo;
    
    L.drawLocal.draw.handlers.polygon.tooltip.start = t.startTooltip;
    L.drawLocal.draw.handlers.polygon.tooltip.cont = t.contTooltip;
    L.drawLocal.draw.handlers.polygon.tooltip.end = t.endTooltip;
    
    L.drawLocal.edit.toolbar.buttons.edit = t.edit;
    L.drawLocal.edit.toolbar.buttons.editDisabled = t.editDisabled;
    L.drawLocal.edit.toolbar.buttons.remove = t.remove;
    L.drawLocal.edit.toolbar.buttons.removeDisabled = t.removeDisabled;
    
    L.drawLocal.edit.toolbar.actions.save.title = t.save;
    L.drawLocal.edit.toolbar.actions.save.text = t.save;
    L.drawLocal.edit.toolbar.actions.cancel.title = t.cancelEdit;
    L.drawLocal.edit.toolbar.actions.cancel.text = t.cancelEdit;
    
    L.drawLocal.edit.handlers.edit.tooltip.text = t.editTooltip;
    L.drawLocal.edit.handlers.edit.tooltip.subtext = t.editSubTooltip;
    L.drawLocal.edit.handlers.remove.tooltip.text = t.removeTooltip;

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
                // Make vertices smaller while drawing
                icon: new L.DivIcon({
                    iconSize: new L.Point(8, 8),
                    className: 'leaflet-div-icon leaflet-editing-icon'
                }),
                touchIcon: new L.DivIcon({
                    iconSize: new L.Point(16, 16),
                    className: 'leaflet-div-icon leaflet-editing-icon'
                }),
                vertices: {
                    size: 8,
                    icon: new L.DivIcon({
                        iconSize: new L.Point(8, 8),
                        className: 'leaflet-div-icon leaflet-editing-icon'
                    })
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
    
    if (cropMode) {
        applyCrop(layer);
        return;
    }
    
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
        const confirmMessage = window.translations?.lots?.fields?.draw_confirm || 'There is already a drawn polygon. Do you want to delete it to draw a new one?';
        const userConfirmed = confirm(confirmMessage);
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
        },
        icon: new L.DivIcon({
            iconSize: new L.Point(8, 8),
            className: 'leaflet-div-icon leaflet-editing-icon'
        }),
        touchIcon: new L.DivIcon({
            iconSize: new L.Point(16, 16),
            className: 'leaflet-div-icon leaflet-editing-icon'
        })
    }).enable();
}

function editButton() {
    if (drawnItems.getLayers().length > 0) {
        const layer = drawnItems.getLayers()[0];
        layer.editing.enable();
        document.getElementById('saveButton').style.display = 'inline-block'; 
    } else {
        const noPolygonMessage = window.translations?.lots?.no_polygon_edit || 'No hay ningún polígono para editar.';
        alert(noPolygonMessage);
    }
}

function saveDrawing() {
    if (drawnItems.getLayers().length > 0) {
        const layer = drawnItems.getLayers()[0];
        layer.editing.disable(); 

        map.fire('draw:edited', { layers: drawnItems });
        document.getElementById('saveButton').style.display = 'none';
    } else {
        const noPolygonMessage = window.translations?.lots?.no_polygon_save || 'No hay ningún polígono para guardar.';
        alert(noPolygonMessage);
    }
}

function startCrop() {
    if (drawnItems.getLayers().length === 0) {
        const noPolygonMessage = window.translations?.lots?.no_polygon_crop || 'No hay ningún polígono para recortar.';
        alert(noPolygonMessage);
        return;
    }
    
    cropMode = true;
    document.getElementById('cropButton').style.display = 'none';
    document.getElementById('cropCancelButton').style.display = 'inline-block';
    
    new L.Draw.Polygon(map, {
        shapeOptions: {
            color: '#ff0000',
            fillColor: 'red',
            fillOpacity: 0.3
        },
        icon: new L.DivIcon({
            iconSize: new L.Point(8, 8),
            className: 'leaflet-div-icon leaflet-editing-icon'
        })
    }).enable();
}

function cancelCrop() {
    cropMode = false;
    if (cropPolygon) {
        map.removeLayer(cropPolygon);
        cropPolygon = null;
    }
    document.getElementById('cropButton').style.display = 'inline-block';
    document.getElementById('cropCancelButton').style.display = 'none';
}

function applyCrop(cropLayer) {
    if (drawnItems.getLayers().length === 0) return;
    
    const originalPolygon = drawnItems.getLayers()[0];
    const originalCoords = originalPolygon.getLatLngs()[0];
    const cropCoords = cropLayer.getLatLngs()[0];
    
    try {
        const originalGeoJSON = {
            type: "Feature",
            geometry: {
                type: "Polygon",
                coordinates: [originalCoords.map(coord => [coord.lng, coord.lat])]
            }
        };
        
        const cropGeoJSON = {
            type: "Feature",
            geometry: {
                type: "Polygon",
                coordinates: [cropCoords.map(coord => [coord.lng, coord.lat])]
            }
        };
        
        const isWithin = turf.booleanWithin(cropGeoJSON, originalGeoJSON);
        
        let result;
        
        if (isWithin) {
            result = {
                type: "Feature",
                geometry: {
                    type: "Polygon",
                    coordinates: [
                        originalGeoJSON.geometry.coordinates[0],
                        cropGeoJSON.geometry.coordinates[0] 
                    ]
                }
            };
        } else {
            result = turf.difference(originalGeoJSON, cropGeoJSON);
        }
        
        if (result && result.geometry && result.geometry.coordinates) {
            drawnItems.clearLayers();

            if (result.geometry.coordinates.length > 1) {
                const outerRing = result.geometry.coordinates[0].map(coord => [coord[1], coord[0]]);
                const holes = result.geometry.coordinates.slice(1).map(hole => 
                    hole.map(coord => [coord[1], coord[0]])
                );
                
                const resultPolygon = L.polygon([outerRing, ...holes], {
                    color: '#ff6600',
                    fillColor: 'orange',
                    fillOpacity: 0.3
                });
                
                drawnItems.addLayer(resultPolygon);

                const leafletCoords = resultPolygon.getLatLngs()[0];
                const area = L.GeometryUtil.geodesicArea(leafletCoords);
                
                let totalHoleArea = 0;
                if (resultPolygon.getLatLngs().length > 1) {
                    for (let i = 1; i < resultPolygon.getLatLngs().length; i++) {
                        const holeCoords = resultPolygon.getLatLngs()[i];
                        totalHoleArea += L.GeometryUtil.geodesicArea(holeCoords);
                    }
                }
    
                
                const finalArea = area - totalHoleArea;
                const hectares = (finalArea / 10000).toFixed(3);
                
                const formattedCoords = leafletCoords.map(point => ({
                    lat: point.lat,
                    lng: point.lng 
                }));
                
                updateCoordinatesDisplay(formattedCoords, hectares);
                
                Livewire.dispatch('updateCoordinates', {
                    coords: formattedCoords,
                    hectares: hectares
                });
                
            } else {
                // Polígono simple (sin agujero)
                const resultCoords = result.geometry.coordinates[0].map(coord => [coord[1], coord[0]]);
                const resultPolygon = L.polygon(resultCoords, {
                    color: '#ff6600',
                    fillColor: 'orange',
                    fillOpacity: 0.3
                });
                
                drawnItems.addLayer(resultPolygon);
                
                // Calcular nueva área
                const area = L.GeometryUtil.geodesicArea(resultCoords);
                const hectares = (area / 10000).toFixed(3);
                
                const formattedCoords = resultCoords.map(point => ({
                    lat: point[0],
                    lng: point[1]
                }));
                
                updateCoordinatesDisplay(formattedCoords, hectares);
                
                Livewire.dispatch('updateCoordinates', {
                    coords: formattedCoords,
                    hectares: hectares
                });
            }
        } else {
            alert('No se puede aplicar el recorte. El área seleccionada no es válida.');
        }
        
    } catch (error) {
        console.error('Error al aplicar recorte:', error);
        alert('Error al aplicar el recorte. Inténtalo de nuevo.');
    }
    
    // Limpiar modo de recorte
    map.removeLayer(cropLayer);
    cancelCrop();
}

function createPolygonWithHoles(outerCoords, holes) {
    // Crear el polígono principal
    const mainPolygon = L.polygon(outerCoords, {
        color: '#ff6600',
        fillColor: 'orange',
        fillOpacity: 0.3
    });
    
    // Crear los agujeros como polígonos separados con fondo transparente
    holes.forEach(holeCoords => {
        const hole = L.polygon(holeCoords, {
            color: '#ff6600',
            fillColor: 'white',
            fillOpacity: 1,
            weight: 1
        });
        drawnItems.addLayer(hole);
    });
    
    return mainPolygon;
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
            // Use translation for error message
            const errorMessage = window.translations?.lots?.kml_error || 'Error al cargar el archivo KML. Por favor, verifique el formato del archivo.';
            alert(errorMessage);
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
        const areaLabel = window.translations?.lots?.area_label || 'Área';
        const coordsLabel = window.translations?.lots?.coords_label || 'Coordenadas';
        coordsElement.innerText = `${areaLabel}: ${hectares} hectáreas\n\n${coordsLabel}:\n${JSON.stringify(coords, null, 2)}`;
    }
}