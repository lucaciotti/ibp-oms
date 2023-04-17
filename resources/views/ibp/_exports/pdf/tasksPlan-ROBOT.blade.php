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
    $firstPage=true;
    $chunkTasks = array_chunk($tasks, 5);
    $ralHelper = new RALHelper();
@endphp

@foreach ($chunkTasks as $tasks)
    <p class="page">
    @if ($firstPage)
        <div class="row" style="text-align: center">
            <h1>Pianificazione {{ $planName }}</h1>
            <h3>Periodo: {{ $dtMin }} - {{ $dtMax }}</h3>
            <br>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif
    
    @foreach ($tasks as $task)
    @php
    $ralRGB = $ralHelper->getRGB($task['values']['ibp_ral']);
    @endphp
    <div class="row">
        <table>
            <col width='8%'>
            <col width='8%'>
            <col width='10%'>
            <col width='5%'>
            <col width='5%'>
            <col width='10%'>
            <col width='12%'>
            <col width='17%'>
            <col width='20%'>
            <col width='5%'>
            <tr height="30px">
                <th colspan=2>{{ $task['values']['ibp_cliente_ragsoc'] }}</th>
                <th>{{ $task['values']['ibp_prodotto_tipo'] }}</th>
                <th colspan="2"> RAL</th>
                @if (!empty($task['values']['ibp_colonna']))
                <th>COLONNA</th>
                @else
                <th>BRACCIO</th>
                @endif
                <th>CARRELLO {{ $task['values']['ibp_carrello'] }}</th>
                <th>IMBALLO</th>
                <th>NOTE</th>
                <th>Qta</th>
            </tr>
            <tr>
                <td>{{ $task['matricole'][0] ?? '' }}</td>
                <td>{{ $task['matricole'][4] ?? '' }}</td>
                <th>IMPIANTO</th>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;"></td><th>{{ $task['values']['ibp_ral'] }}</th>
                @if (!empty($task['values']['ibp_colonna']))
                    <td>{{ $task['values']['ibp_colonna'] }}</td>                    
                @else
                    <td>{{ $task['values']['ibp_braccio'] }}</td>                    
                @endif
                <td>{{ $task['values']['ibp_carrello_opt'] }}</td>
                <td>{{ $task['values']['ibp_imballo_tipo'] }}</td>
                <td rowspan=5>{{ $task['values']['ibp_plan_note'] }}</td>
                <th rowspan=5>{{ $task['qta'] }}</th>
            </tr>
            <tr>
                <td>{{ $task['matricole'][1] ?? '' }}</td>
                <td>{{ $task['matricole'][5] ?? '' }}</td>
                <td>{{ $task['values']['ibp_impianto'] }}</td>
                <th colspan="2">DOCUMENTI</th>
                <th>BATTERIA</th>
                <td>{{ $task['values']['ibp_carrello_opt_2'] }}</td>
                <td>{{ $task['values']['ibp_imballo_dim'] }}</td>
            </tr>
            <tr>
                <td>{{ $task['matricole'][2] ?? '' }}</td>
                <td>{{ $task['matricole'][6] ?? '' }}</td>
                <td rowspan="3"></td>
                <th colspan="2"></th>
                <td>{{ $task['values']['ibp_batteria'] }}</td>
                <td>{{ $task['values']['ibp_carrello_opt_3'] }}</td>
                <td>{{ $task['values']['ibp_imballo_info'] }}</td>
            </tr>
            <tr>
                <td>{{ $task['matricole'][3] ?? '' }}</td>
                <td>{{ $task['matricole'][7] ?? '' }}</td>
                <th colspan="2">ADESIVI</th>
                <th>RUOTA TASTATRICE</th>
                <td rowspan="2"></td>
                <td rowspan=2>{{ $task['values']['ibp_imballo_note'] }}</td>
            </tr>
            <tr>
                <th>Data Cons.</th>
                <td>{{ (new Carbon\Carbon($task['values']['ibp_data_consegna']))->format('d/m/Y') }}</td>
                <td colspan="2"></td>
                <td>{{ $task['values']['ibp_ruota_tastatrice'] }}</td>
            </tr>
        </table>
    </div>
    <div>
        <hr class="dividerPage">
    </div>
    @endforeach
    
    </p>
@endforeach

@endsection