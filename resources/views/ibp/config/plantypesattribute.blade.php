@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Associa Attributi</h1>
<h6 class="m-0 text-dark">
    Tipoliogie di pianificazione - <strong>{{ $planType->name }}</strong>
</h6>
@stop

@section('content-fluid')
    <div class="row justify-content-center">            
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista Attributi Disponibili</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            
                <div class="card-body">
                    <div style="">
                        <livewire:plan-type-attribute.attribute-table type_id='{{ $planType->id }}'/>
                    </div>
                </div>
            </div>
        </div>

        <div class="col" style="flex-grow: 0.2;">
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista Attributi Associati</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            
                <div class="card-body">
                    <div style="">
                        <livewire:plan-type-attribute.plan-type-attribute-table type_id='{{ $planType->id }}'/>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('js')
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
@endpush