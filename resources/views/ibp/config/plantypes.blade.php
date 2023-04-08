@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Tipologia Pianficazioni</h1>
<h6 class="m-0 text-dark">
    Configurazione delle tipoliogie di pianificazione
</h6>
@stop

@section('content-fluid')
    <livewire:plan-type.content />
@stop