@extends('layouts.backend')
@section('content')
    <x-hero
        :title="__('crud.merchants.plural')"
        :subtitle="__('crud.merchants.merchants list')"
        :breadcrumbs="[
            [
                'label' => 'Home',
                'url' => '/',
            ],
            [
                'label' => __('crud.merchants.plural'),
                'url' => route('merchants.tenants.merchants.index'),
            ],
            [
                'label' => __('crud.merchants.actions.edit'),
            ],
        ]"></x-hero>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.merchants.Merchants list') }}
                </h3>
            </div>
            <div class="block-content block-content-full">
                @include('pages.merchants.form')
            </div>
        </div>
    </div>
@endsection
