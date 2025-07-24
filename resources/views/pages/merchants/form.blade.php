@extends('layouts.backend')

@section('content')
    <x-hero :title="__('Empresas')" :subtitle="isset($id) ? __('Modificar Cliente') : __('Crear nuevo Cliente')"></x-hero>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ isset($id)
                        ? (request()->routeIs('clients.*')
                            ? __('Modificar Cliente')
                            : __('Modificar Empresa'))
                        : (request()->routeIs('clients.*')
                            ? __('Crear nuevo Cliente')
                            : __('Crear nueva Empresa')) }}
                </h3>
            </div>
            <div class="block-content p-4">
                <livewire:merchant-form :merchantId="$id ?? ''" :isClient="request()->routeIs('clients.merchants*')" />

            </div>
        </div>
    </div>
@endsection
