@can('categories.create')
@extends('layouts.backend')
@section('content')

<x-hero :title="isset($id) ? __('crud.categories.actions.edit') : __('crud.categories.add')"
    :subtitle="isset($id) ? __('crud.categories.actions.edit') : __('crud.categories.form')"
    :breadcrumbs="[
        [
            'label' => __('crud.categories.breadcrumbs.catalog'),
            'url' => '/dashboard',
        ],
        [
            'label' => __('crud.categories.plural'),
            'url' => route('categories.index'),
        ],
        [
            'label' => isset($id) ? __('crud.categories.actions.edit') : __('crud.categories.add'),
        ],
    ]"></x-hero>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ isset($id) ? __('crud.categories.actions.edit') : __('crud.categories.add') }}
            </h3>
        </div>
        <div class="block-content block-content-full">
            <livewire:category-form :categoryId="$id ?? ''" />
        </div>
    </div>
</div>
@endsection
@endcan