@extends('layouts.backend')
@section('content')
    @livewireScripts
    @livewireStyles
    
    <x-hero :title="isset($id) ? __('crud.aircrafts.actions.edit') : __('crud.aircrafts.add')"
            :subtitle="isset($id) ? __('crud.aircrafts.actions.edit') : __('crud.aircrafts.add')"
            :breadcrumbs="[
        [
            'label' => 'Home',
            'url' => '/dashboard',
        ],
        [
            'label' => __('crud.aircrafts.plural'),
            'url' => route('aircrafts.index'),
        ],
        [
            'label' => isset($id) ? __('crud.aircrafts.actions.edit') : __('crud.aircrafts.add'),
        ],
    ]"></x-hero>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    {{ isset($id) ? __('crud.aircrafts.actions.edit') : __('crud.aircrafts.add') }}
                </h3>
            </div>
            <div class="block-content block-content-full">
                <livewire:aircraft-form :aircraftId="$id ?? ''" />
            </div>
        </div>
    </div>
@endsection 
