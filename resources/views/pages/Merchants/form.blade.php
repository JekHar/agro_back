@extends('layouts.backend')

@section('content')
<x-hero
    :title="__('Empresas')"
    :subtitle="isset($id) ? __('Modificar Empresa') : __('Crear nueva Empresa')"
    :breadcrumbs="[
            [
                'label' => __('crud.merchants.breadcrumbs.management'),
                'url' => '/',
            ],
            [
                'label' => __('Empresas'),
                'url' => route('tenants.merchants.index'),
            ],
            [
                'label' => __('Agregar Empresa'),
            ],
        ]"></x-hero>

<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">{{ isset($id) ? __('Modificar Empresa') : __('Crear nueva Empresa') }}</h3>
        </div>
        <div class="block-content p-4">
            <livewire:merchant-form
                :merchantId="$id ?? ''"
                :isClient="request()->routeIs('merchants.clients.*')" />

        </div>
    </div>
</div>
@endsection