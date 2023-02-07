@extends('adminlte::page')

{{-- @section('title_postfix', '- Search Products') --}}

@section('content_header')
<br>
<h1 class="m-0 text-dark">
    Lista Imballi
</h1>
<br>
@stop

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Anagrafica Imballi</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <livewire:package-table />
            </div>
        </div>
    </div>
</div>
@stop