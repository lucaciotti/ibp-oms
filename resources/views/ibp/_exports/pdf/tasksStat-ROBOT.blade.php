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
            <h1>Statistiche {{ $planName }}</h1>
            <h3>Periodo: {{ $dtMin }} - {{ $dtMax }}</h3>
            <br>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif
    
    <div class="row">
        <h2>RAL / Basamento</h2>
        <table>
            <tr height="30px">
                <th colspan="2"></th>
                @foreach ($statRal['basaments'] as $item)
                    <th width='20px'>{{ $item }}</th>
                @endforeach
            </tr>
            @foreach ($statRal['rals'] as $ral)
            @php
            $ralRGB = $ralHelper->getRGB($ral);
            @endphp
            <tr>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
                <th width='20px'>{{ $ral }}</th>
                @foreach ($statRal['basaments'] as $item)
                <th>{{ $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $item)->count() }}</th>
                @endforeach
            </tr>
            @endforeach
        </table>
    </div>

    <div>
        <hr class="dividerPage">
    </div>

    <div class="row">
        <h2>Impianti</h2>
        <table>
            <thead>
                <tr height="20px">
                    <th width='150px' rowspan="2"></th>
                    @foreach ($statImpianti['prods'] as $item)
                    <th colspan="3">{{ $item }}</th>
                    @endforeach
                </tr>
                <tr height="20px">
                    @foreach ($statImpianti['prods'] as $item)
                    <th >TASTI</th>
                    <th >TOUCH</th>
                    <th >TOT.</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($statImpianti['colonne'] as $col)
            <tr>
                <th>{{ $col }}</th>
                @foreach ($statImpianti['prods'] as $item)
                <td>{{ $tasks->where('ibp_colonna', $col)->where('ibp_prodotto_tipo', $item)->count() }}</td>
                <td>{{ $tasks->where('ibp_colonna', $col)->where('ibp_prodotto_tipo', $item.' TOUCH')->count() }}</td>
                <th>{{ $tasks->where('ibp_colonna', $col)->filter(function ($task) use ($item) { return false !== stripos($task, $item); })->count() }}</th>
                @endforeach
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    
    <div>
        <hr class="dividerPage">
    </div>

    <div class="row">
        <h2>Carrelli</h2>
        <table>
            <tbody>
                @foreach ($statCarrelli as $cart)
                <tr>
                    <th width='300px'>{{ $cart }}</th>
                    <td width='150px'>{{ $tasks->where('ibp_carrello', $cart)->count() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div>
        <hr class="dividerPage">
    </div>
</p>

@endsection