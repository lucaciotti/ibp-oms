@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Ciao,</h1>
    <h6 class="m-0 text-dark">
        Questa è la tua dashbord di <strong>IBP-oms</strong>
    </h6>
@stop

@section('content')
<div class="row">

    <div class="col-lg-12 ">
        <br><br><br>
        <div class="row">
            <div class="col-lg-6 col-6 ml-auto">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>#</h3>

                        <p>Lavori in sospeso</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clipboard-list"></i>
                    </div>
                    <a href="#" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-6 col-6 ml-auto">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>#</h3>

                        <p>Clienti</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users"></i>
                    </div>
                    <a href="#" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-6 ml-auto">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>#</h3>

                        <p>Modelli</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-barcode"></i>
                    </div>
                    <a href="#" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-6 col-6 ml-auto">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>#</h3>

                        <p>Imballi</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-box"></i>
                    </div>
                    <a href="#" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
</div>
</div>
@stop

{{-- @section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop --}}

{{-- @section('js')
<script>
    console.log('Hi!'); 
</script>
@stop --}}
