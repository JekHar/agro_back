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
    :title="__('crud.categories.plural')"
></x-hero>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ __('crud.categories.categories_list') }}
            </h3>
            <div class="block-options">
                @can('categories.create')
                <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary p-2 rounded-pill text-white">
                    <i class="fa fa-plus me-1"></i>
                    {{ __('crud.categories.add') }}
                </a>
                @endcan
            </div>
        </div>
        @include('pages.categories.category-datatable')
    </div>
</div>
@endsection
