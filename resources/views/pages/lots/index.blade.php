@can('lots.index')
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
<x-hero
    :title="__('crud.lots.plural')"
    :subtitle="__('crud.lots.Lots_list')"
    :breadcrumbs="[
            [
                'label' => __('crud.lots.breadcrumbs.home'),
                'url' => '/',
            ],
            [
                'label' => __('crud.lots.plural'),
            ],
        ]"></x-hero>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ __('crud.lots.Lots_list') }}
            </h3>
            <div class="block-options">
                @can('lots.create')
                <a href="{{ route('lots.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus me-1"></i>
                    {{ __('crud.lots.add') }}
                </a>
                @endcan
            </div>
        </div>
        @include('pages.lots.lot-datatable')
    </div>
</div>
@endsection
@endcan