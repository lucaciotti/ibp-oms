@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>File Excel Imported</h1>
@stop

@section('content-fluid')
<livewire:plan-import-file.content/>
@stop

@push('css')
<style>
    a .hide {
        display: none;
    }
</style>    
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush