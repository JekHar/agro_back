
@extends('layouts.backend')
@push('css')
<!-- Page JS Plugins CSS -->
<link rel="stylesheet" href="{{ asset('js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
@endpush
@push('scripts')
@include('partials.datatables-js')
{{ $dataTable->scripts() }}
@endpush
@section('content')
<x-hero
    :title="__('crud.merchants.plural')"
    :subtitle="__('crud.merchants.Merchants_list')"
    :breadcrumbs="[
            [
                'label' => __('crud.merchants.breadcrumbs.management'),
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
                {{ __('crud.merchants.Merchants_list') }}
            </h3>
            <div class="block-options">
                @can('clients.merchants.create')
                <a href="{{ route(request()->routeIs('clients.merchants.*') ? 'clients.merchants.create' : 'tenants.merchants.create') }}" class="btn btn-sm btn-primary p-2 rounded-pill text-white" >
                    <i class="fa fa-plus me-1"></i>
                    {{ __('crud.merchants.add') }}
                </a>
                @endcan
            </div>
        </div>
        <div class="block-content block-content-full">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection
