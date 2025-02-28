@extends('layouts.backend')

@section('content')
    <x-hero
        :title="__('crud.orders.plural')"
        :subtitle="isset($order) ? __('crud.orders.edit') : __('crud.orders.create')"
        :breadcrumbs="[
            [
                'label' => __('crud.lots.breadcrumbs.home'),
                'url' => '/',
            ],
            [
                'label' => __('crud.orders.plural'),
                'url' => route('orders.index'),
            ],
            [
                'label' => isset($order) ? __('crud.orders.edit') : __('crud.orders.create'),
            ],
        ]"></x-hero>

    <div class="content">
        <livewire:order-form :orderId="$order->id ?? null" />
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showAlert', (message) => {
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
