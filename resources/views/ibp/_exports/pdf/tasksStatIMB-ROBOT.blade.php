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
                <td></td>
                @foreach ($stats['imb_tipo'] as $item)
                    <th width='50px'>{{ $item }}</th>
                @endforeach
                <th width='15px'>Tot.</th>
            </tr>
            @foreach ($stats['imb_dim'] as $imb_dim)
            @php
                if(str_contains($imb_dim,'780')){
                    continue;
                }
            @endphp
            <tr>
                <th width='100px'>{{ $imb_dim }}</th>
                @if ($imb_dim=='700 X 1500')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '750 X 1500')!==false; })->count() + 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '785 X 1535 X H 2200')!==false and !Str::startsWith($task->ibp_imballo_tipo, '*'); })->count() +
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '785 X 1530 X H 1160')!==false and !Str::startsWith($task->ibp_imballo_tipo, '*'); })->count()
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '750 X 1500')!==false; })->count() + 
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '785 X 1535 X H 2200')!==false and !Str::startsWith($task->ibp_imballo_tipo, '*'); })->count() +
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '785 X 1530 X H 1160')!==false and !Str::startsWith($task->ibp_imballo_tipo, '*'); })->count()
                        }}
                    </td>
                @endif
                
                @if ($imb_dim=='790 X 1540 X H 1135')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '790 X 1540 X H 1135')!==false; })->count() +
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return Str::startsWith($task->ibp_imballo_tipo, '*'); })->count()
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '790 X 1540 X H 1135')!==false; })->count() +
                            $tasks->filter(function ($task) { return Str::startsWith($task->ibp_imballo_tipo, '*'); })->count()
                        }}
                    </td>
                @endif

                @if ($imb_dim=='790 X 1540 X H 1235')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '790 X 1540 X H 1235')!==false; })->count() 
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '790 X 1540 X H 1235')!==false; })->count() 
                        }}
                    </td>
                @endif
                
                @if ($imb_dim=='860 X 1760')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '860 X 1760')!==false; })->count() 
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '860 X 1760')!==false; })->count() 
                        }}
                    </td>
                @endif                
                
                @if ($imb_dim=='900 X 1800 X H 1235')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return strpos($task->ibp_imballo_dim, '900 X 1800 X H 1235')!==false; })->count() 
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return strpos($task->ibp_imballo_dim, '900 X 1800 X H 1235')!==false; })->count() 
                        }}
                    </td>
                @endif
                
                {{-- @if ($imb_dim=='780 X 2600 X H 800')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return in_array($task->ibp_colonna, ['H 2500', 'H 3000', 'H 3500']); })->count()
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return in_array($task->ibp_colonna, ['H 2500', 'H 3000', 'H 3500']); })->count()
                        }}
                    </td>
                @endif

                @if ($imb_dim=='*Prolunga 780 X 2600')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>
                        {{ 
                            $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return in_array($task->ibp_colonna, ['H 3000', 'H 3500']); })->count()
                        }}
                    </td>
                    @endforeach
                    <td>
                        {{ 
                            $tasks->filter(function ($task) { return in_array($task->ibp_colonna, ['H 3000', 'H 3500']); })->count()
                        }}
                    </td>
                @endif --}}


                {{-- @if ($imb_dim!='790 X 1540 X H 1135')
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->where('ibp_imballo_tipo', $item)->count() }}</td>
                    @endforeach
                    <td>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->count() }}</td>
                @else
                    @foreach ($stats['imb_tipo'] as $item)
                    <td>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->where('ibp_imballo_tipo', $item)->count() + $tasks->filter(function ($task) { return Str::startsWith($task->ibp_imballo_tipo, '*'); })->where('ibp_imballo_tipo', $item)->count() }}</td>
                    @endforeach
                    <td>{{ $tasks->where('ibp_imballo_dim', $imb_dim)->count() + $tasks->filter(function ($task) { return Str::startsWith($task->ibp_imballo_tipo, '*'); })->count() }}</td>
                @endif --}}
            </tr>
            @endforeach
            <tfoot>
                <tr>
                    <th>TOTALE</th>
                    @foreach ($stats['imb_tipo'] as $item)
                    <th>{{ $tasks->where('ibp_imballo_tipo', $item)->count() }}</th>
                    @endforeach
                    <th>{{ $tasks->count() }}</th>
                </tr>
            </tfoot>

            <tr>
                <td colspan="{{ count($stats['imb_tipo'])+2 }}"></td>
            </tr>
            <tr>
                <th colspan="{{ count($stats['imb_tipo'])+2 }}">
                    IMBALLO 2
                </th>
            </tr>

            @foreach ($stats['imb_dim'] as $imb_dim)
            @php
            if(!str_contains($imb_dim,'780')){
            continue;
            }
            @endphp
            <tr>
                <th width='100px'>{{ $imb_dim }}</th>
            
                @if ($imb_dim=='780 X 2600 X H 800')
                @foreach ($stats['imb_tipo'] as $item)
                <td>
                    {{
                    $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return in_array($task->ibp_colonna, ['H 2500', 'H 3000', 'H 3500', 'H2500', 'H3000', 'H3500']); })->count()
                    }}
                </td>
                @endforeach
                <td>
                    {{
                    $tasks->filter(function ($task) { return in_array($task->ibp_colonna, ['H 2500', 'H 3000', 'H 3500', 'H2500', 'H3000', 'H3500']);
                    })->count()
                    }}
                </td>
                @endif
            
                @if ($imb_dim=='*Prolunga 780 X 2600')
                @foreach ($stats['imb_tipo'] as $item)
                <td>
                    {{
                    $tasks->where('ibp_imballo_tipo', $item)->filter(function ($task) { return in_array($task->ibp_colonna, ['H 3000', 'H 3500', 'H3000', 'H3500']); })->count()
                    }}
                </td>
                @endforeach
                <td>
                    {{
                    $tasks->filter(function ($task) { return in_array($task->ibp_colonna, ['H 3000', 'H 3500', 'H3000', 'H3500']); })->count()
                    }}
                </td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>

    <div>
        <hr class="dividerPage">
    </div>

</p>

@endsection