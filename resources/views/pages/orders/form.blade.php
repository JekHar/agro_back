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
    <x-hero :title="__('crud.orders.plural')"></x-hero>

    <div class="content">
        <livewire:order-form :orderId="$order->id ?? null" />
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showAlert', (message) => {
                console.log(message);
                Swal.fire({
                    title: message[0].title,
                    text: message[0].text,
                    icon: message[0].type,
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
@endpush
