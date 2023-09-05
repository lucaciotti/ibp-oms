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
            <h2>Pianificazione {{ $planName }}</h2>
            <h4>Periodo: {{ $dtMin }} - {{ $dtMax }}</h4>
            <hr>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif
    
    @foreach ($tasks as $task)
    @php
    $ralRGB = $ralHelper->getRGB($task['values']['ibp_ral']);
    $ralRGB2 = $ralHelper->getRGB($task['values']['ibp_ral_basamcol']);
    @endphp
    <div class="row">
        <table>
            <col width='5%'>
            <col width='5%'>
            <col width='10%'>
            <col width='3%'>
            <col width='7%'>
            <col width='15%'>
            <col width='15%'>
            <col width='15%'>
            <col width='20%'>
            <col width='5%'>
            <tr>
                <th colspan=2 rowspan="2">{{ $task['values']['ibp_cliente_ragsoc'] }}</th>
                <th>{{ $task['values']['ibp_prodotto_tipo'] }}</th>
                <th colspan="2">RAL PIATTO</th>
                <th>COLONNA</th>
                <th>CARRELLO</th>
                {{-- <th>{{ $task['values']['ibp_carrello'] }}</th> --}}
                <th>IMBALLO</th>
                <th>NOTE</th>
                <th>Qta</th>
            </tr>
            <tr>
                <td>{{ $task['values']['ibp_n_programmi'] }}</td>
                <td style="background-color: rgb({{ $ralRGB }}); opacity:75%;"></td><th>{{ $task['values']['ibp_ral'] }}</th>
                <td>{{ $task['values']['ibp_colonna'] }}</td>
                <td>{{ $task['values']['ibp_carrello'] }}</td>
                <td>{{ $task['values']['ibp_imballo_tipo'] }}</td>
                <td rowspan=6>{{ $task['values']['ibp_plan_note'] }}</td>
                <th rowspan=6>{{ $task['qta'] }}</th>
            </tr>
            <tr>
                <td>{{ $task['matricole'][0] ?? '' }}</td>
                <td>{{ $task['matricole'][4] ?? '' }}</td>
                <th>BASAMENTO</th>
                <th colspan="2">RAL BAS - COL</th>
                <td>{{ $task['values']['ibp_colonna_opt'] }}</td>
                <td>{{ $task['values']['ibp_carrello_opt'] }}</td>
                <td>{{ $task['values']['ibp_imballo_dim'] }}</td>
            </tr>
            <tr>
                <td>{{ $task['matricole'][1] ?? '' }}</td>
                <td>{{ $task['matricole'][5] ?? '' }}</td>
                <td>{{ $task['values']['ibp_basamento'] }}</td>
                <td style="background-color: rgb({{ $ralRGB2 }}); opacity:75%;"></td><th>{{ $task['values']['ibp_ral_basamcol'] }}</th>
                <td>{{ $task['values']['ibp_opt2_colonna'] }}</td>
                <td>{{ $task['values']['ibp_carrello_opt_2'] }}</td>
                <td>{{ $task['values']['ibp_imballo_note'] }}</td>
            </tr>
            <tr>
                <td>{{ $task['matricole'][2] ?? '' }}</td>
                <td>{{ $task['matricole'][6] ?? '' }}</td>
                <td>{{ $task['values']['ibp_basamento_opt'] }}</td>
                <th colspan="2">ADESIVI</th>
                <td>{{ $task['values']['ibp_opt3_colonna'] }}</td>
                <td>{{ $task['values']['ibp_carrello_opt_3'] }}</td>
                <td>{{ $task['values']['ibp_note_imballo2'] }}</td>
            </tr>
            <tr>
                <td>{{ $task['matricole'][3] ?? '' }}</td>
                <td>{{ $task['matricole'][7] ?? '' }}</td>
                <td>{{ $task['values']['ibp_opt2_basamento'] }}</td>
                <td colspan="2">{{ $task['values']['ibp_adesivi'] }}</td>
                <th>PRESSORE</th>
                <td>{{ $task['values']['ibp_opt4_carrello'] }}</td>
                <td>{{ $task['values']['ibp_rampadime'] }}</td>
            </tr>
            <tr>
                <td colspan="2">{{ $task['values']['ibp_note_imp'] ?? ''}}</td>
                <td>{{ $task['values']['ibp_opt3_basamento'] }}</td>
                <td colspan="2">{{ $task['values']['ibp_documenti'] }}</td>
                <td>{{ $task['values']['ibp_pressore_opt'] }}</td>
                <td>{{ $task['values']['ibp_opt5_carrello'] }}</td>
                <td>{{ $task['values']['ibp_rampa_dime_opt'] }}</td>
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