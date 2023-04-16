@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Righe Importate</h1>
@stop

@section('content-fluid')
<livewire:plan-import-file.temp-task.content file_id='{{ $id }}'/>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush