@can('lots.create')
@extends('layouts.backend')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tokml/tokml.js"></script>
<script src="https://makinacorpus.github.io/Leaflet.GeometryUtil/leaflet.geometryutil.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Turf.js/6.5.0/turf.min.js"></script>

<script src="{{ asset('js/map-utils.js') }}"></script>
<script>
    document.addEventListener('livewire:init', () => {
        initializeMap();
        setupDrawingControls();

        Livewire.on('lot-loaded', (data) => {
            drawnItems.clearLayers();
            if (navigationPin) {
                map.removeLayer(navigationPin);
                navigationPin = null;
            }

            const coordinates = data[0]?.coordinates;
            const holes = data[0].holes;
            const hectares = data[0]?.hectares;
            const navigationPinCoords = data[0]?.navigationPin;


            if (coordinates && coordinates.length > 0) {
                const mainCoords = coordinates.map(coord => [
                    parseFloat(coord.lat),
                    parseFloat(coord.lng)
                ]);


                const polygonLatLngs = [mainCoords];

                if (holes && holes.length > 0) {
                    holes.forEach(holeGroup => {
                        const holeCoords = holeGroup.map(coord => [
                            parseFloat(coord.lat),
                            parseFloat(coord.lng)
                        ]);
                        polygonLatLngs.push(holeCoords);
                    });
                }

                const polygon = L.polygon(polygonLatLngs, {
                    color: 'orange',
                    fillColor: 'orange',
                    fillOpacity: 0.3
                });

                drawnItems.addLayer(polygon);

                updateCoordinatesDisplay(coordinates, hectares);

                map.fitBounds(polygon.getBounds());
            }
            if (navigationPinCoords && navigationPinCoords.lat && navigationPinCoords.lng) {
                const latlng = L.latLng(parseFloat(navigationPinCoords.lat), parseFloat(navigationPinCoords.lng));
                navigationPin = L.marker(latlng).addTo(map);
                updateNavigationPinDisplay(latlng);
            }
        });
    });
</script>
@endpush

@section('content')

<x-hero :title="__('crud.lots.plural')"></x-hero>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ isset($id) ? __('crud.lots.actions.edit') : __('crud.lots.add') }}
            </h3>
        </div>
        <div class="block-content block-content-full">
            <livewire:lot-form :lotId="$id ?? ''" />
        </div>
    </div>
</div>
@endsection
@endcan
