@extends('layouts.backend')

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
                    title: message.title,
                    text: message.text,
                    icon: message.type,
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
@endpush
