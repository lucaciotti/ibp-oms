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
    <h2>RAL / Basamento</h2>
    <table>
        <tr height="30px">
            <td colspan="2"></td>
            @foreach ($stats['basaments'] as $item)
            @if (!str_contains($item, 'PIATTO'))
            <th width='20px'>{{ $item }}</th>
            @endif
            @endforeach
            <th width='15px'>Tot.</th>
        </tr>
        @foreach ($stats['rals_basamcol'] as $ral)
        @php
        $ralRGB = $ralHelper->getRGB($ral);
        @endphp
        <tr>
            <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
            <th width='20px'>{{ $ral }}</th>
            @foreach ($stats['basaments'] as $item)
            @if (!str_contains($item, 'PIATTO'))
            <td>{{ $tasks->where('ibp_ral_basamcol', $ral)->where('ibp_basamento', $item)->count() }}</td>
            @endif
            @endforeach
            <td>{{ $tasks->where('ibp_ral_basamcol', $ral)->where('ibp_basamento', '!=', '')->count() }}</td>
        </tr>
        @endforeach
            <tfoot>
            <tr>
                <th colspan="2">TOTALE</th>
                @foreach ($stats['basaments'] as $item)
                    @if (!str_contains($item, 'PIATTO'))    
                        <th>{{ $tasks->where('ibp_basamento', $item)->count() }}</th>
                    @endif
                @endforeach
                <th>{{ $tasks->where('ibp_basamento', '!=', '')->count() }}</th>
            </tr>
            </tfoot>
    </table>
</div>

<div>
    <hr class="dividerPage">
</div>

<div class="row">
    <h2>RAL / Colonna</h2>
    <table>
        <tr height="30px">
            <td colspan="2"></td>
            @foreach ($stats['colonne'] as $item)
            <th width='20px'>{{ $item }}</th>
            @endforeach
            <th width='15px'>Tot.</th>
        </tr>
        @foreach ($stats['rals_basamcol'] as $ral)
        @php
        $ralRGB = $ralHelper->getRGB($ral);
        @endphp
        <tr>
            <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
            <th width='20px'>{{ $ral }}</th>
            @foreach ($stats['colonne'] as $item)
            <td>{{ $tasks->where('ibp_ral_basamcol', $ral)->where('ibp_colonna', $item)->count() }}</td>
            @endforeach
            <td>{{ $tasks->where('ibp_ral_basamcol', $ral)->where('ibp_colonna', '!=', '')->count() }}</td>
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
    <h2>RAL / Basamento</h2>
    <table>
        <tr height="30px">
            <td colspan="2"></td>
            @foreach ($stats['colonne'] as $item)
            <th width='20px'>{{ $item }}</th>
            @endforeach
            <th width='15px'>Tot.</th>
        </tr>
        @foreach ($stats['rals_basamcol'] as $ral)
        @php
        $ralRGB = $ralHelper->getRGB($ral);
        @endphp
        <tr>
            <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;" width='10px'></td>
            <th width='20px'>{{ $ral }}</th>
            @foreach ($stats['colonne'] as $item)
            <td>{{ $tasks->where('ibp_ral_basamcol', $ral)->where('ibp_colonna', $item)->count() }}</td>
            @endforeach
            <td>{{ $tasks->where('ibp_ral_basamcol', $ral)->where('ibp_colonna', '!=', '')->count() }}</td>
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
        <h2>RAL / Basamento</h2>
        <table>
            <tr height="30px">
                <td colspan="2"></td>
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
                @if (stripos($item, 'PIATTO') !== false)
                @php
                $dim_basamento = substr($item, 0, strrpos($item, '-')-0);
                @endphp
                @if (str_contains($item, 'PIATTO 8 MM'))
                <td>
                    {{
                    $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO 8 MM') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO 8 MM') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO 8 MM') ;
                    })
                    ->count()
                    }}
                </td>
                @endif
        
                @if (str_contains($item, 'PIATTO TP-BILANCIA'))
                <td>
                    {{
                    $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO TP-BILANCIA') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO TP-BILANCIA') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO TP-BILANCIA') ;
                    })
                    ->count()
                    }}
                </td>
                @endif
        
                @if (str_contains($item, 'PIATTO PINZA'))
                <td>
                    {{
                    $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO PINZA') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO PINZA') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO PINZA') ;
                    })
                    ->count()
                    }}
                </td>
                @endif
        
                @if (str_contains($item, 'PIATTO ANTISCIVOLO'))
                <td>
                    {{
                    $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO ANTISCIVOLO') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO ANTISCIVOLO') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO ANTISCIVOLO') ;
                    })
                    ->count()
                    }}
                </td>
                @endif
                @else
                <td>{{
                    $tasks->where('ibp_ral', $ral)->where('ibp_basamento', $item)
                    ->filter(function ($task) {
                    return
                    !str_contains($task->ibp_basamento_opt, 'PIATTO 8 MM') &&
                    !str_contains($task->ibp_basamento_opt, 'PIATTO TP-BILANCIA') &&
                    !str_contains($task->ibp_basamento_opt, 'PIATTO PINZA') &&
                    !str_contains($task->ibp_basamento_opt, 'PIATTO ANTISCIVOLO') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO 8 MM') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO TP-BILANCIA') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO PINZA') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO ANTISCIVOLO') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO 8 MM') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO TP-BILANCIA') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO PINZA') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO ANTISCIVOLO') ;
                    })
                    ->count()
                    }}</td>
                @endif
                @endforeach
                <td>{{ $tasks->where('ibp_ral', $ral)->count() }}</td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2">TOTALE</th>
                @foreach ($stats['basaments'] as $item)
                @if (stripos($item, 'PIATTO') !== false)
                @php
                $dim_basamento = substr($item, 0, strrpos($item, '-')-0);
                @endphp
                @if (str_contains($item, 'PIATTO 8 MM'))
                <th>
                    {{
                    $tasks->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO 8 MM') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO 8 MM') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO 8 MM') ;
                    })
                    ->count()
                    }}
                </th>
                @endif
        
                @if (str_contains($item, 'PIATTO TP-BILANCIA'))
                <th>
                    {{
                    $tasks->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO TP-BILANCIA') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO TP-BILANCIA') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO TP-BILANCIA') ;
                    })
                    ->count()
                    }}
                </th>
                @endif
        
                @if (str_contains($item, 'PIATTO PINZA'))
                <th>
                    {{
                    $tasks->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO PINZA') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO PINZA') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO PINZA') ;
                    })
                    ->count()
                    }}
                </th>
                @endif
        
                @if (str_contains($item, 'PIATTO ANTISCIVOLO'))
                <th>
                    {{
                    $tasks->where('ibp_basamento', $dim_basamento)
                    ->filter(function ($task) {
                    return
                    str_contains($task->ibp_basamento_opt, 'PIATTO ANTISCIVOLO') ||
                    str_contains($task->ibp_opt2_basamento, 'PIATTO ANTISCIVOLO') ||
                    str_contains($task->ibp_opt3_basamento, 'PIATTO ANTISCIVOLO') ;
                    })
                    ->count()
                    }}
                </th>
                @endif
                @else
                <th>{{
                    $tasks->where('ibp_basamento', $item)
                    ->filter(function ($task) {
                    return
                    !str_contains($task->ibp_basamento_opt, 'PIATTO 8 MM') &&
                    !str_contains($task->ibp_basamento_opt, 'PIATTO TP-BILANCIA') &&
                    !str_contains($task->ibp_basamento_opt, 'PIATTO PINZA') &&
                    !str_contains($task->ibp_basamento_opt, 'PIATTO ANTISCIVOLO') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO 8 MM') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO TP-BILANCIA') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO PINZA') &&
                    !str_contains($task->ibp_opt2_basamento, 'PIATTO ANTISCIVOLO') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO 8 MM') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO TP-BILANCIA') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO PINZA') &&
                    !str_contains($task->ibp_opt3_basamento, 'PIATTO ANTISCIVOLO') ;
                    })
                    ->count()
                    }}</th>
                @endif
                @endforeach
                <th>{{ $tasks->count() }}</th>
            </tr>
        </table>
</div>

{{-- <div>
    <hr class="dividerPage">
</div>

<div class="row">
    <h2>RAL Piatto / Carrello</h2>
    <table>
        <tr height="30px">
            <td colspan="2"></td>
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
            <td>{{ $tasks->where('ibp_ral', $ral)->where('ibp_carrello', $item)->count() }}</td>
            @endforeach
            <th>{{ $tasks->where('ibp_ral', $ral)->where('ibp_carrello', '!=', '')->count() }}</th>
        </tr>
        @endforeach
        <tfoot>
            <tr>
                <th colspan="2">TOTALE</th>
                @foreach ($stats['carrelli'] as $item)
                <th>{{ $tasks->where('ibp_carrello', $item)->count() }}</th>
                @endforeach
                <th>{{ $tasks->where('ibp_carrello', '!=', '')->count() }}</th>
            </tr>
        </tfoot>
    </table>
</div> --}}

<div>
    <hr class="dividerPage">
</div>

</p>

@endsection