@extends('ibp._exports.pdf._masterPage.masterPdf')

@section('pdf-main')
<style>
    table,
    th,
    td {
        border: 1px solid #96D4D4;
        border-collapse: collapse;
        text-align: center;
        border-style: groove;
        height: 20px;
        vertical-align: center;
    }
</style>

@php
    $ralHelper = new RALHelper();
    $firstPage=true;
@endphp

<p class="page">
    @if ($firstPage)
        <div class="row" style="text-align: center">
            <h1>Statistiche IMBALLI {{ $planName }}</h1>
            <h3>Periodo: {{ $dtMin }} - {{ $dtMax }}</h3>
            <br>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif
    
    <div class="row">
        <h2>Tipologia Imballi + Misure</h2>
        <table>
            <tr height="20px">
                <th></th>
                @foreach ($stats['imb_tipo'] as $item)
                    <th width='50px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['imb_dim'] as $imb_dim)
            <tr>
                <th width='100px'>{{ $imb_dim }}</th>
                @foreach ($stats['imb_tipo'] as $item)
                <th>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->where('ibp_imballo_tipo', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->count() }}</th>
            </tr>
            @endforeach
            <tr>
                <th>TOTALE</th>
                @foreach ($stats['imb_tipo'] as $item)
                <th>{{ $tasks->where('ibp_imballo_tipo', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->count() }}</th>
            </tr>
        </table>
    </div>

    <div>
        <hr class="dividerPage">
    </div>

</p>

@endsection