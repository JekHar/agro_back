@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h1 class="h3 mb-0">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        Mapa de Lotes
                    </h1>
                </div>
                <div class="card-body">
                    @include('map.partials.controls')
                    @include('map.partials.map-view')
                    @include('map.partials.coordinates')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection