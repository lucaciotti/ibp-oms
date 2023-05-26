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
            <h1>Statistiche RAL {{ $planName }}</h1>
            <h3>Periodo: {{ $dtMin }} - {{ $dtMax }}</h3>
            <br>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif
    
    <div class="row">
        <h2>RAL / Piatto</h2>
        <table>
            <tr height="30px">
                <th colspan="2"></th>
                @foreach ($stats['basaments'] as $item)
                    <th width='20px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['rals'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($stats['basaments'] as $item)
                <th>{{ $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->where('ibp_ral', $ral)->count() }}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTALE</th>
                @foreach ($stats['basaments'] as $item)
                <th>{{ $tasks->where('ibp_basamento', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->count() }}</th>
            </tr>
        </table>
    </div>

    <div>
        <hr class="dividerPage">
    </div>

    <div class="row">
        <h2>RAL / Colonna (da verificare con effettivo campo Ral colonna)</h2>
        <table>
            <tr height="30px">
                <th colspan="2"></th>
                @foreach ($stats['colonne'] as $item)
                <th width='20px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['rals'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($stats['colonne'] as $item)
                <th>{{ $tasks->where('ibp_ral', $ral)->where('ibp_colonna', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->where('ibp_ral', $ral)->count() }}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTALE</th>
                @foreach ($stats['colonne'] as $item)
                <th>{{ $tasks->where('ibp_colonna', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->count() }}</th>
            </tr>
        </table>
    </div>
    
    <div>
        <hr class="dividerPage">
    </div>

    <div class="row">
        <h2>RAL / Carrello (da verificare con effettivo campo Ral carrello)</h2>
        <table>
            <tr height="30px">
                <th colspan="2"></th>
                @foreach ($stats['carrelli'] as $item)
                <th width='20px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['rals'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($stats['carrelli'] as $item)
                <th>{{ $tasks->where('ibp_ral', $ral)->where('ibp_carrello', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->where('ibp_ral', $ral)->count() }}</th>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTALE</th>
                @foreach ($stats['carrelli'] as $item)
                <th>{{ $tasks->where('ibp_carrello', $item)->count() }}</th>
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