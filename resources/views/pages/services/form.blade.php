@extends('layouts.backend')
@section('content')
<x-hero :title="__('crud.services.plural')"></x-hero>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ isset($id) ? __('crud.services.actions.edit') : __('crud.services.add') }}
            </h3>
        </div>
        <div class="block-content block-content-full">
            <livewire:service-form :serviceId="$id ?? ''" />
        </div>
    </div>
</div>
@endsection