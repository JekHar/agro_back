@extends('layouts.backend')

@push('css')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endpush


@push('scripts')
    @include('partials.datatables-js')
    {{ $dataTable->scripts() }}
@endpush

@section('content')
    <x-hero :title="__('crud.services.plural')" :subtitle="__('crud.services.services_list')" :breadcrumbs="[
        [
            'label' => 'Home',
            'url' => '/',
        ],
        [
            'label' => __('crud.services.plural'),
        ],
    ]"></x-hero>

    <div class="content">

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.services.services_list') }}
                </h3>
                <div class="block-options">
                    <a href="{{ route('services.create') }}" class="btn btn-sm btn-primary">
                        <i class="fa fa-plus me-1"></i>
                        {{ __('crud.services.add') }}
                    </a>
                </div>
            </div>
            @include('pages.services.service-datatable')
        </div>
    </div>
@endsection
