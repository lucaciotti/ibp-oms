@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Configurazioni Import Excel</h1>
<h6 class="m-0 text-dark">
    Tipoliogie di import - <strong>{{ $planType->name }}</strong>
</h6>
@stop

@section('content-fluid')
<livewire:plan-import-type.content type_id='{{ $planType->id }}'/>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush