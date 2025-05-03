@extends('layouts.backend')
@section('content')
<x-hero :title="__('crud.products.plural')"></x-hero>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ isset($id) ? __('crud.products.actions.edit') : __('crud.products.add') }}
            </h3>
        </div>
        <div class="block-content block-content-full">
            <livewire:product-form :productId="$id ?? ''" />
        </div>
    </div>
</div>
@endsection