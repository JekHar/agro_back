@extends('layouts.backend')
@section('content')
    <x-hero
        :title="__('crud.services.plural')"
        :subtitle="__('crud.services.services list')"
        :breadcrumbs="[
            [
                'label' => 'Home',
                'url' => '/',
            ],
            [
                'label' => __('crud.services.plural'),
                'url' => route('services.index'),
            ],
            [
                'label' => __('crud.services.actions.edit'),
            ],
        ]"></x-hero>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.services.services list') }}
                </h3>
            </div>
            <div class="block-content block-content-full">
                @include('pages.services.form')
            </div>
        </div>
    </div>
@endsection
