@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Statistica Pianificazioni</h1>
@stop

@section('content-fluid')
@if ($planType)
<livewire:stat-planned-task.completed-content/>
@endif
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush

@section('plugins.TempusDominusBs4', true)