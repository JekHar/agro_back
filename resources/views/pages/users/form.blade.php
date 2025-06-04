@extends('layouts.backend')
@section('content')

<x-hero :title="__('crud.users.plural')"></x-hero>
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                {{ isset($id) ? __('crud.users.actions.edit') : __('crud.users.add') }}
            </h3>
        </div>
        <div class="block-content block-content-full">
            <livewire:user-form :userId="$id ?? ''" />
        </div>
    </div>
</div>
@endsection