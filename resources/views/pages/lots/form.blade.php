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