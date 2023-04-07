@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
<h1>Tipologia Pianficazioni</h1>
<h6 class="m-0 text-dark">
    Configurazione delle tipoliogie di pianificazione
</h6>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 ">

        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button class="btn btn-outline-success btn-block" onclick="Livewire.emit('modal.open', 'modal.plan-type-edit');">
                    <span class="fa fa-edit"></span> Crea nuovo piano
                </button>
            </div>
        </div>

    </div>
</div>
@stop

@section('content-fluid')
<div class="row">
    <div class="col-lg-12 ">

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista Pianificazioni</h3>
        
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div style="font-size: smaller">
                    <livewire:plan-type.plan-type-table />
                </div>
            </div>
        </div>

    </div>
</div>
@stop