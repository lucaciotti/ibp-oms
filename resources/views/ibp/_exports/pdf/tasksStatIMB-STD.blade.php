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
            <h3>Periodo Produzione: {{ $dtMin }} - {{ $dtMax }}</h3>
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
                <td width='100px'></td>
                @foreach ($stats['imb_tipo'] as $item)
                    <th width='50px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['imb_dim'] as $imb_dim)
            <tr>
                <th>{{ $imb_dim }}</th>
                @php
                $countPolistirolo = 0;
                $countNONPolistirolo = 0;
                $totRow= 0;
                @endphp
                @foreach ($stats['imb_tipo'] as $item)
                @if (strpos(strtolower($item), 'polistirolo'))
                @php
                    $countPolistirolo = $tasks->where('ibp_imballo_dim', $imb_dim)->where('ibp_imballo_tipo', substr($item, 0, strpos($item, ' +')))
                        ->filter(function ($task) { return strpos(strtolower($task->ibp_imballo_note), 'polistirolo') !== false || strpos(strtolower($task->ibp_note_imballo2), 'polistirolo') !== false; })->count()/4;
                    $countNONPolistirolo = 0;
                @endphp
                <td>{{ $countPolistirolo }}</td>
                @else
                @php
                    $countPolistirolo = 0;
                    if(strpos(strtolower($item), 'macchina')!==false){
                        $countNONPolistirolo = $tasks->where('ibp_imballo_dim', $imb_dim)->where('ibp_imballo_tipo', $item)->count();
                    } else {
                        $countNONPolistirolo = $tasks->where('ibp_imballo_dim', $imb_dim)->where('ibp_imballo_tipo', $item)
                        ->filter(function ($task) { return strpos(strtolower($task->ibp_imballo_note), 'polistirolo') === false &&
                        strpos(strtolower($task->ibp_note_imballo2),'polistirolo') === false; })->count();
                    }
                @endphp
                <td>{{ $countNONPolistirolo }}</td>
                @endif
                @php
                    $totRow = $totRow + $countPolistirolo + $countNONPolistirolo;
                @endphp
                @endforeach
                {{-- <th>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->count() }}</th> --}}
                <th>{{ $totRow }}</th>
            </tr>
            @endforeach
            <tfoot>
            <tr>
                <th>TOTALE</th>
                @php
                    $countPolistirolo = 0;
                    $countNONPolistirolo = 0;
                    $totTable= 0;
                @endphp
                @foreach ($stats['imb_tipo'] as $item)
                @if (strpos(strtolower($item), 'polistirolo'))
                @php
                    $countPolistirolo = $tasks->where('ibp_imballo_tipo', substr($item, 0, strpos($item, ' +')))
                        ->filter(function ($task) { return strpos(strtolower($task->ibp_imballo_note), 'polistirolo') !== false || strpos(strtolower($task->ibp_note_imballo2), 'polistirolo') !== false; })->count()/4;
                    $countNONPolistirolo = 0;
                @endphp
                <th>{{ $countPolistirolo }}</th>
                @else
                @php
                    $countPolistirolo = 0;
                    if(strpos(strtolower($item), 'macchina')!==false){
                        $countNONPolistirolo = $tasks->where('ibp_imballo_tipo', $item)->count();
                    } else {
                        $countNONPolistirolo = $tasks->where('ibp_imballo_tipo', $item)
                            ->filter(function ($task) { return strpos(strtolower($task->ibp_imballo_note), 'polistirolo') === false && strpos(strtolower($task->ibp_note_imballo2),'polistirolo') === false; })->count();
                    }
                @endphp
                <th>{{ $countNONPolistirolo }}</th>
                @endif
                @php
                    $totTable = $totTable + $countPolistirolo + $countNONPolistirolo;
                @endphp
                {{-- <th>{{ $tasks->where('ibp_imballo_tipo', $item)->count() }}</th> --}}
                @endforeach
                {{-- <th>{{ $tasks->count() }}</th> --}}
                <th>{{ $totTable }}</th>
            </tr>
            </tfoot>
        </table>
        @if ($countPolistirolo>0)
            <p><small><i>* => su ogni "BANCALE + POLISTIROLO" ci sono 4 macchine</i></small></p>
        @endif
    </div>

    <div>
        <hr class="dividerPage">
    </div>

</p>

@endsection