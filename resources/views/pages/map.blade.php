@extends('layouts.app')

@section('content')
<div class="min-h-screen p-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Mapa de Lotes</h1>

        @include('components.map')
    </div>
</div>
@endsection