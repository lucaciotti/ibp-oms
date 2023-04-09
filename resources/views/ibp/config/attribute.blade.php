@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Attributi</h1>
<h6 class="m-0 text-dark">
    Attributi disponibili per le pianificazioni
</h6>
@stop

@section('content-fluid')
<livewire:attribute.content />
@stop