@extends('layouts.backend')
@section('content')
<x-hero :title="isset($id) ? __('crud.products.actions.edit') : __('crud.products.add')"
    :subtitle="isset($id) ? __('crud.products.actions.edit') : __('crud.products.add')"
    :breadcrumbs="[
        [
            'label' => __('crud.products.breadcrumbs.catalog'),
            'url' => '/dashboard',
        ],
        [
            'label' => __('crud.products.plural'),
            'url' => route('products.index'),
        ],
        [
            'label' => isset($id) ? __('crud.products.actions.edit') : __('crud.products.add'),
        ],
    ]"></x-hero>
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