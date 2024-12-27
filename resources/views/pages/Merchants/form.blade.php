@extends('layouts.backend')

@section('content')
<x-hero
    :title="__('Empresas')"
    :subtitle="__('Lista de Empresas')"
    :breadcrumbs="[
            [
                'label' => 'Home',
                'url' => '/',
            ],
            [
                'label' => __('Empresas'),
                'url' => route('merchants.tenants.merchants.index'),
            ],
            [
                'label' => __('Agregar Empresa'),
            ],
        ]"></x-hero>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ __('Nueva Empresa') }}</h3>
        </div>
        <div class="block-content p-4">
            <livewire:merchant-form
                :merchantId="$id ?? ''"
                :isClient="request()->routeIs('merchants.clients.*')" />

        </div>
    </div>
</div>
@endsection