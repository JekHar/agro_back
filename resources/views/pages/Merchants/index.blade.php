@extends('layouts.backend')
@section('css')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endsection
@push('after_body')
    @include('partials.datatables-js')
    {{ $dataTable->scripts() }}
@endpush
@section('content')
    <x-hero
        :title="__('crud.merchants.plural')"
        :subtitle="__('crud.merchants.Merchants list')"
        :breadcrumbs="[
            [
                'label' => 'Home',
                'url' => '/',
            ],
            [
                'label' => __('crud.merchants.plural'),
            ],
        ]"></x-hero>

    <div class="content">

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.merchants.Merchants list') }}
                </h3>
                <div class="block-options">
                    <button type="button" class="btn btn-sm btn-primary" >
                        <a href={{route('merchants.tenants.merchants.create')}} class="fa fa-plus me-1"></a>
                        {{ __('crud.merchants.add') }}
                    </button>
                </div>
            </div>
            <div class="block-content block-content-full">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
