@extends('layouts.backend')
@section('content')
<x-hero :title="__('crud.aircrafts.plural')"></x-hero>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ isset($id) ? __('crud.aircrafts.actions.edit') : __('crud.aircrafts.add') }}
            </h3>
        </div>
        <div class="block-content block-content-full">
            <livewire:aircraft-form :aircraftId="$id ?? ''" />
        </div>
    </div>
</div>
@endsection