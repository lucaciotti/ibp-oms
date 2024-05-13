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
            <h1>Statistiche IMPIANTI {{ $planName }}</h1>
            <h3>Periodo Produzione: {{ $dtMin }} - {{ $dtMax }}</h3>
            <br>
        </div>
        @php
            $firstPage=false;
        @endphp
    @endif

    <div class="row">
        <h2>Impianti</h2>
        <table>
            <thead>
                <tr height="20px">
                    <th width='150px' rowspan="2"></th>
                    @foreach ($stats['prods'] as $item)
                    <th colspan="3">{{ $item }}</th>
                    @endforeach
                </tr>
                <tr height="20px">
                    @foreach ($stats['prods'] as $item)
                    <th >TASTI</th>
                    <th >TOUCH</th>
                    <th >TOT.</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($stats['colonne'] as $col)
            <tr>
                <th>{{ $col }}</th>
                @foreach ($stats['prods'] as $item)
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
            <thead>
                <tr>
                    <th width='20%'>CARRELLO</th>
                    <th width='10%'>TOTALE</th>
                    <th width='10%'>Opt. INOX</th>
                    <th width='10%'>Opt. KIT SOFFIO</th>
                    <th width='10%'>Opt. STRINGIF. AUTO</th>
                    <th width='10%'>Opt. STRINGIF. MAN.</th>
                    <th width='10%'>Opt. TAGLIO</th>
                    <th width='10%'>Opt. TAGLIO A FASCE</th>
                    <th width='10%'>Opt. CARRELLO PLURIBALL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats['carrelli'] as $cart)
                <tr>
                    <th>{{ str_replace("CARRELLO", "", $cart) }}</th>
                    <td>{{ $tasks->where('ibp_carrello', $cart)->count() }}</td>
                    <td>
                        {{ 
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){ return stripos($task->ibp_carrello_opt, 'INOX')!==false || stripos($task->ibp_carrello_opt_2, 'INOX')!==false || stripos($task->ibp_carrello_opt_3, 'INOX')!==false; })
                            ->count() 
                        }}
                    </td>
                    <td>
                        {{
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){ return stripos($task->ibp_carrello_opt, 'KIT SOFFIO')!==false || stripos($task->ibp_carrello_opt_2, 'KIT SOFFIO')!==false || stripos($task->ibp_carrello_opt_3, 'KIT SOFFIO')!==false; })
                            ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){ return 
                                stripos($task->ibp_carrello_opt, 'STRINGIF')!==false && stripos($task->ibp_carrello_opt, 'AUTO')!==false || 
                                stripos($task->ibp_carrello_opt_2, 'STRINGIF')!==false && stripos($task->ibp_carrello_opt_2, 'AUTO')!==false || 
                                stripos($task->ibp_carrello_opt_3, 'STRINGIF')!==false && stripos($task->ibp_carrello_opt_3, 'AUTO')!==false || 
                                stripos($task->ibp_opt4_carrello, 'STRINGIF')!==false && stripos($task->ibp_opt4_carrello, 'AUTO')!==false || 
                                stripos($task->ibp_opt5_carrello, 'STRINGIF')!==false && stripos($task->ibp_opt5_carrello, 'AUTO')!==false ; 
                            })
                            ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){ return 
                                stripos($task->ibp_carrello_opt, 'STRINGIF')!==false && stripos($task->ibp_carrello_opt, 'MAN')!==false || 
                                stripos($task->ibp_carrello_opt_2, 'STRINGIF')!==false && stripos($task->ibp_carrello_opt_2, 'MAN')!==false || 
                                stripos($task->ibp_carrello_opt_3, 'STRINGIF')!==false && stripos($task->ibp_carrello_opt_3, 'MAN')!==false || 
                                stripos($task->ibp_opt4_carrello, 'STRINGIF')!==false && stripos($task->ibp_opt4_carrello, 'MAN')!==false || 
                                stripos($task->ibp_opt5_carrello, 'STRINGIF')!==false && stripos($task->ibp_opt5_carrello, 'MAN')!==false ; 
                            })
                            ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){return stripos($task->ibp_carrello_opt, 'TAGLIO')!==false || stripos($task->ibp_carrello_opt_2, 'TAGLIO')!==false || stripos($task->ibp_carrello_opt_3, 'TAGLIO')!==false;})
                            ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){ return stripos($task->ibp_carrello_opt, 'TAGLIO A FASCE')!==false || stripos($task->ibp_carrello_opt_2, 'TAGLIO A FASCE')!==false || stripos($task->ibp_carrello_opt_3, 'TAGLIO A FASCE')!==false; })
                            ->count()
                        }}
                    </td>
                    <td>
                        {{
                            $tasks->where('ibp_carrello', $cart)
                            ->filter(function ($task){ return stripos($task->ibp_carrello_opt, 'CARRELLO PLURIBALL')!==false || stripos($task->ibp_carrello_opt_2, 'CARRELLO PLURIBALL')!==false || stripos($task->ibp_carrello_opt_3, 'CARRELLO PLURIBALL')!==false; })
                            ->count()
                        }}
                    </td>
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