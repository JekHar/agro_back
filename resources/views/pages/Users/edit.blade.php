@extends('layouts.backend')
@section('content')
    <x-hero
        :title="__('crud.users.plural')"
        :subtitle="__('crud.users.Users list')"
        :breadcrumbs="[
            [
                'label' => 'Home',
                'url' => '/',
            ],
            [
                'label' => __('crud.users.plural'),
                'url' => route('users.index'),
            ],
            [
                'label' => __('crud.users.actions.edit'),
            ],
        ]"></x-hero>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ __('crud.users.Userslist') }}
                </h3>
            </div>
            <div class="block-content block-content-full">
                @include('pages.users.form')
            </div>
        </div>
    </div>
@endsection