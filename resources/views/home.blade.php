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

        @foreach ($planTiles as $planRow)
            <div class="row">
                @if (count($planRow)==2)
                    @foreach ($planRow as $planTile)
                    <div class="col-lg-6 col-6 ml-auto">
                        <div class="small-box bg-{{ $planTile['color'] }}">
                            <div class="inner">
                                <h3>{{ $planTile['count'] }}</h3>

                                <p>{{ $planTile['title'] }}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-clipboard-list"></i>
                            </div>
                            <a href="{{ route('planned_tasks', $planTile['id']) }}" class="small-box-footer">Visualizza <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-lg-3 col-3 ml-auto">
                    </div>
                    <div class="col-lg-6 col-6 ml-auto">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $planRow[0]['count'] }}</h3>

                                <p>{{ $planRow[0]['title'] }}</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-clipboard-list"></i>
                            </div>
                            <a href="{{ route('planned_tasks', $planRow[0]['id']) }}" class="small-box-footer">Visualizza <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-3 ml-auto">
                    </div>
                @endif
            </div>
        @endforeach
        
        <hr>
        <div class="row ">
            <div class="col-lg-12 col-12 ml-auto">
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
