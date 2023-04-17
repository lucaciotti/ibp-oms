@extends('layouts.app')

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
                        <h3>{{ $stdplan }}</h3>

                        <p>Pianificazioni STD</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clipboard-list"></i>
                    </div>
                    <a href="{{ route('planned_tasks', 1) }}" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-6 col-6 ml-auto">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $robotplan }}</h3>

                        <p>Pianificazioni ROBOT</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clipboard-list"></i>
                    </div>
                    <a href="{{ route('planned_tasks', 2) }}" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-3 col-3 ml-auto">
            </div>
            <div class="col-lg-6 col-6 ml-auto">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>&NonBreakingSpace;</h3>

                        <p>Importa Xls</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-file-excel"></i>
                    </div>
                    <a href="{{ route('plan_xls') }}" class="small-box-footer">Visualizza <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-3 ml-auto">
            </div>
            {{-- <div class="col-lg-6 col-6 ml-auto">
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
            </div> --}}
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
