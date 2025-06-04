
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
    :title="__('crud.orders.plural')"
></x-hero>

<div class="content">

    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ __('crud.orders.orders_list') }}
            </h3>
            <div class="block-options">
                @can('orders.create')
                    <a href="{{ route('orders.create') }}" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                        <i class="fa fa-plus me-1"></i>
                        {{ __('crud.orders.add') }}
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
