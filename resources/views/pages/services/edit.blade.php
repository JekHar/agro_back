@can('services.edit')
@extends('layouts.backend')
@section('content')
    <x-hero
        :title="__('crud.items.plural')"
        :subtitle="__('crud.items.Items list')"
        :breadcrumbs="[
            [
                'label' => 'Home',
                'url' => '/',
            ],
            [
                'label' => __('crud.items.plural'),
                'url' => route('items.index'),
            ],
            [
                'label' => __('crud.items.actions.edit'),
            ],
        ]"></x-hero>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.items.Items list') }}
                </h3>
            </div>
            <div class="block-content block-content-full">
                @include('pages.items.form')
            </div>
        </div>
    </div>
@endsection
@endcan
