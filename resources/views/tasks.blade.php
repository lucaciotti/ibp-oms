@extends('adminlte::page')

{{-- @section('title_postfix', '- Search Products') --}}

@section('content_header')
<br>
<h1 class="m-0 text-dark">
    Lista Attività
</h1>
<br>
@stop

@section('content-fluid')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                @livewire('upload-tasks')
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista Lavori Macchina</h3>
        
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <livewire:machine-jobs-table />
            </div>
        </div>
    </div>
</div>
@stop

{{-- @section('extra_script')
@endsection --}}