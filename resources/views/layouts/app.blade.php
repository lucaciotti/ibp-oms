{{-- Extends the AdminLTE page layout  --}}
@extends('adminlte::page')


{{-- Browser Title --}}
@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle') | @yield('subtitle') @endif
@stop

{{-- Page Content Header --}}
@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-lightblue">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Page Content Body --}}
@section('content')
    @yield('content_body')
@stop

{{-- Page Footer --}}
@section('footer')
    <div class="float-right d-none d-sm-block">
        {{-- Version number here --}}
    </div>

    <strong>
       {{-- Company name here --}}
    </strong>
@stop

{{-- Extra common CSS --}}
@push('css')
    @vite(['resources/js/app.js'])
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('vendor/wire-elements-pro/css/bootstrap-overlay-component.css') }}">
    <style>
        .wep-slide-over-container-inner-content {
        padding-top: 2.8rem;
        }

        .table td, .table th {
            padding: 0rem;
            padding-left: 0.75rem;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
            vertical-align: unset;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('vendor/wire-elements-pro/js/overlay-component.js') }}"></script>
@endpush