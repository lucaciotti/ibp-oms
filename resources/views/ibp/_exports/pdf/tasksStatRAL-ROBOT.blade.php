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
            <h3>Periodo Produzione: {{ $dtMin }} - {{ $dtMax }}</h3>
            <br>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif
    
    <div class="row">
        <h2>RAL / Braccio (da verificare)</h2>
        <table>
            <tr height="30px">
                <td colspan="2"></td>
                @foreach ($stats['braccio'] as $item)
                    <th width='20px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['rals_colbraccio'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($stats['braccio'] as $item)
                <td>{{ $tasks->where('ibp_ral_colbraccio', $ral)->where('ibp_braccio', $item)->count() }}</td>
                @endforeach
                <td>{{ $tasks->where('ibp_ral_colbraccio', $ral)->where('ibp_braccio', '!=', '')->count() }}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th colspan="2">TOTALE</th>
                    @foreach ($stats['braccio'] as $item)
                    <th>{{ $tasks->where('ibp_braccio', $item)->count() }}</th>
                    @endforeach
                    <th>{{ $tasks->where('ibp_braccio', '!=', '')->count() }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div>
        <hr class="dividerPage">
    </div>

    <div class="row">
        <h2>RAL / Colonna (da verificare)</h2>
        <table>
            <tr height="30px">
                <td colspan="2"></td>
                @foreach ($stats['colonne'] as $item)
                <th width='20px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['rals_colbraccio'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($stats['colonne'] as $item)
                <td>{{ $tasks->where('ibp_ral_colbraccio', $ral)->where('ibp_colonna', $item)->count() }}</td>
                @endforeach
                <td>{{ $tasks->where('ibp_ral_colbraccio', $ral)->where('ibp_colonna', '!=', '')->count() }}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th colspan="2">TOTALE</th>
                    @foreach ($stats['colonne'] as $item)
                    <th>{{ $tasks->where('ibp_colonna', $item)->count() }}</th>
                    @endforeach
                    <th>{{ $tasks->where('ibp_colonna', '!=', '')->count() }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div>
        <hr class="dividerPage">
    </div>

    <div class="row">
        <h2>RAL Guscio / Carrello (da verificare)</h2>
        <table>
            <tr height="30px">
                <td colspan="2"></td>
                @foreach ($stats['carrelli'] as $item)
                <th width='20px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['rals_guscio'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($stats['carrelli'] as $item)
                <td>{{ $tasks->where('ibp_ral_guscio', $ral)->where('ibp_carrello', $item)->count() }}</td>
                @endforeach
                <td>{{ $tasks->where('ibp_ral_guscio', $ral)->count() }}</td>
            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th colspan="2">TOTALE</th>
                    @foreach ($stats['carrelli'] as $item)
                    <th>{{ $tasks->where('ibp_carrello', $item)->count() }}</th>
                    @endforeach
                    <th>{{ $tasks->count() }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <div>
        <hr class="dividerPage">
    </div>
    
</p>

@endsection