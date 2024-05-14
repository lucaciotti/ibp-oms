<?php

namespace App\Http\Livewire\PdfReports;

use App\Helpers\PdfReport;
use App\Models\Attribute;
use App\Models\PlannedTask;
use App\Models\PlanType;
use Arr;
use Carbon\Carbon;
use Illuminate\Support\Str;
use WireElements\Pro\Components\Modal\Modal;

class GenerateReports extends Modal
{
    public $tasks_ids;
    public $type_id;
    public $reportKey;
    public $order_tasks;
    public $filter_on_tasks;
    public $planName;
    
    public $reports = [
        'plan' => 'Distinta Pianificazioni (da completare)',
        'plan_ended' => 'Pianificazioni Completare',
        'stat_imp' => 'Statistiche Impianti',
        'stat_ral' => 'Statistiche RAL',
        'stat_imb' => 'Statistiche Imballi',
    ];
    
    public $title = 'Report PDF';
    public $pdfReport;

    protected function do_plan_report() 
    {
        
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);

        // Costruisco le date di riferimento report
        if (array_key_exists('date_prod_from', $this->filter_on_tasks)){
            $dtMin = (new Carbon($this->filter_on_tasks['date_prod_from']))->format('d/m/Y');
        } else {
            // $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_inizio_prod')))->format('d/m/Y');
            $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->min('ibp_data_inizio_prod')))->format('d/m/Y');
        }
        if (array_key_exists('date_prod_to', $this->filter_on_tasks)) {
            $dtMax = (new Carbon($this->filter_on_tasks['date_prod_to']))->format('d/m/Y');
        } else {
            // $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_inizio_prod')))->format('d/m/Y');
            $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->max('ibp_data_inizio_prod')))->format('d/m/Y');
        }

        $columns = Arr::pluck(Attribute::select('col_name')->whereHas('planTypeAttribute', fn ($query) => $query->where('type_id', $this->type_id))->where('col_name', '!=', 'ibp_plan_matricola')->get()->toArray(), 'col_name');
        $n_of_columns = count($columns);
        // $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->where('completed', false)->groupBy($columns)->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc')->get();
        $tasksWithSameValues = null;
        if ($this->order_tasks) {
            $order_applied = false;
            // $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->where('completed', false)->groupBy($columns);
            $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->groupBy($columns);
            foreach ($this->order_tasks as $key => $value) {
                // if(!in_array($key, $columns)){
                //     $tasksWithSameValues->addSelect($key);
                //     // $tasksWithSameValues->addSelect(DB::raw('MAX(' . $key . ') as max_' . $key));
                // }
                if(in_array($key, $columns)){
                    $order_applied = true;
                    $tasksWithSameValues->orderBy($key, $value);
                }
            }
            if(!$order_applied){
                $tasksWithSameValues->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc');
            }
            $tasksWithSameValues = $tasksWithSameValues->get();
            // dd($tasksWithSameValues);
        } else {
            // $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->where('completed', false)->groupBy($columns)->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc')->get();
            $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->groupBy($columns)->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc')->get();
        }
        $tasks = [];
        $sum_total_tasks = 0;
        foreach ($tasksWithSameValues as $task) {
            $aWhere = [];
            foreach ($columns as $column) {
                array_push($aWhere, [$column, $task->$column]);
            }
            // $matricole = Arr::pluck(PlannedTask::select('ibp_plan_matricola')->where($aWhere)->whereIn('id', $this->tasks_ids)->where('completed', false)->get()->toArray(), 'ibp_plan_matricola');
            $matricole = Arr::pluck(PlannedTask::select('ibp_plan_matricola')->where($aWhere)->whereIn('id', $this->tasks_ids)->get()->toArray(), 'ibp_plan_matricola');
            $chunck_matricole = array_chunk($matricole, 8);
            foreach ($chunck_matricole as $aMat) {
                $aTask=[
                    'matricole' => $aMat,
                    'values' => $task->toArray(),
                    'qta' => count($aMat),
                ];
                $sum_total_tasks += count($aMat);
                array_push($tasks, $aTask);
            }
        }
        $matricole_completed = Arr::pluck(PlannedTask::select('ibp_plan_matricola')->where('completed', true)->whereIn('id', $this->tasks_ids)->get()->toArray(), 'ibp_plan_matricola');
        // dd($tasks);
        $title = "Pianificazioni_da_Elaborare_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin."_".$dtMax);
        $view = 'ibp._exports.pdf.tasksPlan-'. $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
            'total_tasks' => $sum_total_tasks,
            'matricole_completed' => $matricole_completed,
            'total_tasks_completed' => count($matricole_completed),
        ];
        // dd($data);
        $this->pdfReport = $title . '-' . $subTitle . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);
        // $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
        $pdf->save(storage_path('app/public/tmp_pdf/' . $this->pdfReport));
    }

    protected function do_plan_ended_report()
    {

        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        // Costruisco le date di riferimento report
        if (array_key_exists('date_prod_from', $this->filter_on_tasks)) {
            $dtMin = (new Carbon($this->filter_on_tasks['date_complete_from']))->format('d/m/Y');
        } else {
            $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_inizio_prod')))->format('d/m/Y');
        }
        if (array_key_exists('date_prod_to', $this->filter_on_tasks)) {
            $dtMax = (new Carbon($this->filter_on_tasks['date_complete_to']))->format('d/m/Y');
        } else {
            $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_inizio_prod')))->format('d/m/Y');
        }

        $columns = Arr::pluck(Attribute::select('col_name')->whereHas('planTypeAttribute', fn ($query) => $query->where('type_id', $this->type_id))->where('col_name', '!=', 'ibp_plan_matricola')->get()->toArray(), 'col_name');
        $n_of_columns = count($columns);
        array_push($columns, 'completed_date');
        $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->where('completed', true)->groupBy($columns)->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc')->get();
        $tasks = [];
        $sum_total_tasks = 0;
        foreach ($tasksWithSameValues as $task) {
            $aWhere = [];
            foreach ($columns as $column) {
                array_push($aWhere, [$column, $task->$column]);
            }
            $matricole = Arr::pluck(PlannedTask::select('ibp_plan_matricola')->where($aWhere)->where($aWhere)->whereIn('id', $this->tasks_ids)->where('completed', true)->get()->toArray(), 'ibp_plan_matricola');
            $chunck_matricole = array_chunk($matricole, 8);
            foreach ($chunck_matricole as $aMat) {
                $aTask = [
                    'matricole' => $aMat,
                    'values' => $task->toArray(),
                    'qta' => count($aMat),
                ];
                $sum_total_tasks += count($aMat);
                array_push($tasks, $aTask);
            }
        }

        $title = "Pianificazioni_Completate_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin . "_" . $dtMax);
        $view = 'ibp._exports.pdf.tasksPlanEnded-' . $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
            'total_tasks' => $sum_total_tasks,
        ];
        // dd($data);
        $this->pdfReport = $title . '-' . $subTitle . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $pdf = PdfReport::A4Landscape($view, $data, $title, $subTitle);
        // $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
        $pdf->save(storage_path('app/public/tmp_pdf/' . $this->pdfReport));
    }

    protected function do_stat_imp_report()
    {
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        // Costruisco le date di riferimento report
        if (array_key_exists('date_prod_from', $this->filter_on_tasks)) {
            $dtMin = (new Carbon($this->filter_on_tasks['date_prod_from']))->format('d/m/Y');
        } else {
            $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_inizio_prod')))->format('d/m/Y');
        }
        if (array_key_exists('date_prod_to', $this->filter_on_tasks)) {
            $dtMax = (new Carbon($this->filter_on_tasks['date_prod_to']))->format('d/m/Y');
        } else {
            $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_inizio_prod')))->format('d/m/Y');
        }

        $tasks = PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->get();
        
        $title = "Statistiche IMPIANTI_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin . "_" . $dtMax);
        $view = 'ibp._exports.pdf.tasksStatIMP-' . $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
            'stats' => $this->loadStats($tasks),
        ];
        // dd($data);
        $this->pdfReport = $title . '-' . $subTitle . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);
        // $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
        $pdf->save(storage_path('app/public/tmp_pdf/' . $this->pdfReport));
    }

    protected function do_stat_ral_report(){
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        // Costruisco le date di riferimento report
        if (array_key_exists('date_prod_from', $this->filter_on_tasks)) {
            $dtMin = (new Carbon($this->filter_on_tasks['date_prod_from']))->format('d/m/Y');
        } else {
            $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_inizio_prod')))->format('d/m/Y');
        }
        if (array_key_exists('date_prod_to', $this->filter_on_tasks)) {
            $dtMax = (new Carbon($this->filter_on_tasks['date_prod_to']))->format('d/m/Y');
        } else {
            $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_inizio_prod')))->format('d/m/Y');
        }

        $tasks = PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->get();

        $title = "Statistiche RAL_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin . "_" . $dtMax);
        $view = 'ibp._exports.pdf.tasksStatRAL-' . $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
            'stats' => $this->loadStats($tasks),
        ];
        // dd($data);
        $this->pdfReport = $title . '-' . $subTitle . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);
        // $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
        $pdf->save(storage_path('app/public/tmp_pdf/' . $this->pdfReport));
    }

    protected function do_stat_imb_report()
    {
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        $this->planName = $planName;
        // Costruisco le date di riferimento report
        if (array_key_exists('date_prod_from', $this->filter_on_tasks)) {
            $dtMin = (new Carbon($this->filter_on_tasks['date_prod_from']))->format('d/m/Y');
        } else {
            $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_inizio_prod')))->format('d/m/Y');
        }
        if (array_key_exists('date_prod_to', $this->filter_on_tasks)) {
            $dtMax = (new Carbon($this->filter_on_tasks['date_prod_to']))->format('d/m/Y');
        } else {
            $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_inizio_prod')))->format('d/m/Y');
        }

        $tasks = PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->get();

        $title = "Statistiche IMBALLI_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin . "_" . $dtMax);
        $view = 'ibp._exports.pdf.tasksStatIMB-' . $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
            'stats' => $this->loadStats($tasks),
        ];
        // dd($data);
        $this->pdfReport = $title . '-' . $subTitle . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);
        // $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
        $pdf->save(storage_path('app/public/tmp_pdf/' . $this->pdfReport));
    }

    protected function loadStats($tasks) {
        // $rals = Arr::pluck(PlannedTask::select('ibp_ral')->whereIn('id', $this->tasks_ids)->where('completed', false)->where('ibp_ral', '!=', '')->orderBy('ibp_ral')->distinct()->get()->toArray(), 'ibp_ral');
        // $basaments = Arr::pluck(PlannedTask::select('ibp_basamento')->whereIn('id', $this->tasks_ids)->where('completed', false)->where('ibp_basamento', '!=', '')->orderBy('ibp_basamento')->distinct()->get()->toArray(), 'ibp_basamento');
        $rals_piatto = $this->getUniqueFromCollection($tasks,'ibp_ral');
        $rals_basamcol = $this->getUniqueFromCollection($tasks,'ibp_ral_basamcol');
        $rals_guscio = $this->getUniqueFromCollection($tasks,'ibp_ral_guscio');
        $rals_colbraccio = $this->getUniqueFromCollection($tasks,'ibp_ral_colbraccio');

        $basaments = $this->getUniqueFromCollection($tasks, 'ibp_basamento');
        $colonne = $this->getUniqueFromCollection($tasks, 'ibp_colonna');
        $braccio = $this->getUniqueFromCollection($tasks, 'ibp_braccio');

        $prods = $this->getUniqueFromCollection($tasks, 'ibp_prodotto_tipo');
        $prodsLabel = [];
        foreach ($prods as $prod) {
            array_push($prodsLabel, trim(str_replace('TOUCH', '', $prod)));
        }
        $prodsLabel = array_unique($prodsLabel);

        $carrelli = $this->getUniqueFromCollection($tasks, 'ibp_carrello');
        $impianto = $this->getUniqueFromCollection($tasks, 'ibp_impianto');
        $batteria = $this->getUniqueFromCollection($tasks, 'ibp_batteria');

        $imb_tipo = $this->getUniqueFromCollection($tasks, 'ibp_imballo_tipo');
        $imb_dim = $this->getUniqueFromCollection($tasks, 'ibp_imballo_dim');

        $stats = [
            'rals' => $rals_piatto,
            'rals_basamcol' => $rals_basamcol,
            'rals_guscio' => $rals_guscio,
            'rals_colbraccio' => $rals_colbraccio,
            'basaments' => $basaments,
            'colonne' => $colonne,
            'braccio' => $braccio,
            'prods' => $prodsLabel,
            'carrelli' => $carrelli,
            'impianto' => $impianto,
            'batteria' => $batteria,
            'imb_tipo' => $imb_tipo,
            'imb_dim' => $imb_dim,
        ];
        return $stats;
    }

    protected function getUniqueFromCollection($collect, $key){
        if ($key == 'ibp_imballo_dim' && $this->planName == 'ROBOT') {
            $arrayOfValues = $this->buildImbRobot();
        } else {
            $arrayOfValues = Arr::pluck($collect->where($key, '!=', '')->unique($key)->sortBy($key)->toArray(), $key);
            if($key == 'ibp_basamento') {
                # Controllo i valori aggiuntivi: PIATTO 8 MM, PIATTO TP-BILANCIA, PIATTO PINZA 
                $piatto8mms = $collect->filter(function ($task) {
                    return
                    stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, '8') !== false && stripos($task->ibp_basamento_opt, 'MM') !== false ||
                    stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, '8') !== false && stripos($task->ibp_opt2_basamento, 'MM') !== false ||
                    stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, '8') !== false && stripos($task->ibp_opt3_basamento, 'MM') !== false;
                });
                foreach ($piatto8mms as $task) {
                    if (stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, '8') !== false && stripos($task->ibp_basamento_opt, 'MM') !== false) $task->ibp_basamento_opt = 'PIATTO 8 MM';
                    if (stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, '8') !== false && stripos($task->ibp_opt2_basamento, 'MM') !== false) $task->ibp_opt2_basamento = 'PIATTO 8 MM';
                    if (stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, '8') !== false && stripos($task->ibp_opt3_basamento, 'MM') !== false) $task->ibp_opt3_basamento = 'PIATTO 8 MM';
                    array_push($arrayOfValues, $task['ibp_basamento'] . ' - PIATTO 8 MM');
                }

                $piattoTpBilancias = $collect->filter(function ($task) {
                    return
                    stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, 'TP') !== false && stripos($task->ibp_basamento_opt, 'BIL') !== false ||
                    stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, 'TP') !== false && stripos($task->ibp_opt2_basamento, 'BIL') !== false ||
                    stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, 'TP') !== false && stripos($task->ibp_opt3_basamento, 'BIL') !== false;
                });
                foreach ($piattoTpBilancias as $task) {
                    if (stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, 'TP') !== false && stripos($task->ibp_basamento_opt, 'BIL') !== false) $task->ibp_basamento_opt = 'PIATTO TP-BILANCIA';
                    if (stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, 'TP') !== false && stripos($task->ibp_opt2_basamento, 'BIL') !== false) $task->ibp_opt2_basamento = 'PIATTO TP-BILANCIA';
                    if (stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, 'TP') !== false && stripos($task->ibp_opt3_basamento, 'BIL') !== false) $task->ibp_opt3_basamento = 'PIATTO TP-BILANCIA';
                    array_push($arrayOfValues, $task['ibp_basamento'] . ' - PIATTO TP-BILANCIA');
                }

                $piattoPinzas = $collect->filter(function ($task) {
                    return
                    stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, 'PINZ') !== false ||
                    stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, 'PINZ') !== false ||
                    stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, 'PINZ') !== false;
                });
                foreach ($piattoPinzas as $task) {
                    if (stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, 'PINZ') !== false) $task->ibp_basamento_opt = 'PIATTO PINZA';
                    if (stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, 'PINZ') !== false) $task->ibp_opt2_basamento = 'PIATTO PINZA';
                    if (stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, 'PINZ') !== false) $task->ibp_opt3_basamento = 'PIATTO PINZA';
                    array_push($arrayOfValues, $task['ibp_basamento'] . ' - PIATTO PINZA');
                }

                $piattoAntiscivolos = $collect->filter(function ($task) {
                    return
                        stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, 'ANTISC') !== false ||
                        stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento,
                        'ANTISC'
                        ) !== false ||
                        stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento,
                        'ANTISC'
                        ) !== false;
                });
                foreach ($piattoPinzas as $task) {
                    if (stripos($task->ibp_basamento_opt, 'PIATTO') !== false && stripos($task->ibp_basamento_opt, 'ANTISC') !== false) $task->ibp_basamento_opt = 'PIATTO ANTISCIVOLO';
                    if (stripos($task->ibp_opt2_basamento, 'PIATTO') !== false && stripos($task->ibp_opt2_basamento, 'ANTISC') !== false) $task->ibp_opt2_basamento = 'PIATTO ANTISCIVOLO';
                    if (stripos($task->ibp_opt3_basamento, 'PIATTO') !== false && stripos($task->ibp_opt3_basamento, 'ANTISC') !== false) $task->ibp_opt3_basamento = 'PIATTO ANTISCIVOLO';
                    array_push($arrayOfValues, $task['ibp_basamento'] . ' - PIATTO ANTISCIVOLO');
                }
            }
        }
        
        // $aMapped = Arr::map($arrayOfValues, function (string $value, string $key) {
        //     return Str::upper($value);
        // });
        // $arrayUnique = array_unique($aMapped);
        return $arrayOfValues;
    }

    private function buildImbRobot() {
        // 700 X 1500 = alla somma di tutti i (750 X 1500) + (785 X 1535 X h 2200 ) + ( 785 X 1530 X h 1160 )
        // 790 X 1540 X h 1135 = alla somma di tutti i ( 790 X 1540 X h 1135 )
        // 790 X 1540 X h 1235 = alla somma di tutti i ( 790 X 1540 X h 1235 )
        // 860 X 1760 = alla somma di tutti i ( 860 X 1760 )
        // 900 X 1800 X h 1235 = alla somma di tutti i (900 X 1800 X h 1235 )
        // 780 X 2600 X h 800 = alla somma di tutti i ( *780 X 3000 X h 800 ) + ( *780 X 3500 X h 800 ) + ( 780 X 2600 X h 800 ) + ( 780 X 3000 X h 800 ) + ( 780 X 3500 X h 800 )
        // Prolunga 780 X 2600 = alla somma di tutti i ( *780 X 3000 X h 800 ) + ( *780 X 3500 X h 800 ) + ( 780 X 3000 X h 800 ) + ( 780 X 3500 X h 800 )
        
        $arrayOfValues = [
            '700 X 1500',
            '790 X 1540 X H 1135',
            '790 X 1540 X H 1235',
            '860 X 1760',
            '900 X 1800 X H 1235',
            '780 X 2600 X H 800',
            '*Prolunga 780 X 2600'
        ];
        // $arrayOfValues = array_filter($arrayOfValues, static function ($element) {
        //     return !Str::startsWith($element, '*');
        // });
        // if (!in_array('790 X 1540 X H 1135', $arrayOfValues)) {
        //     array_push($arrayOfValues, '790 X 1540 X H 1135');
        // }
        // sort($arrayOfValues);

        return $arrayOfValues;
    }

    public function mount($tasks_ids, $reportKey, $type_id, $configs)
    {
        $this->tasks_ids = $tasks_ids;
        $this->reportKey = $reportKey;
        $this->type_id = $type_id;
        $this->order_tasks = (array_key_exists('order', $configs)) ? $configs['order'] : [];
        $this->filter_on_tasks = (array_key_exists('filters', $configs)) ? $configs['filters'] : [];
        $reportCall = 'do_'.$reportKey.'_report';
        if (is_callable([$this, $reportCall])) {
            $this->$reportCall();
        }
    }

    public function render()
    {
        return view('livewire.pdf-reports.generate-reports');
    }

    public function exitReport(){
        // $delete = unlink(base_path('public/tmp_pdf/' . $this->pdfReport));
        $delete = unlink(storage_path('app/public/tmp_pdf/' . $this->pdfReport));
        if($delete){
            $this->close(
                andForget: [
                    ListOfReports::class,
                ]
            );
        }
    }

    public static function behavior(): array
    {
        return [
            // Close the modal if the escape key is pressed
            'close-on-escape' => false,
            // Close the modal if someone clicks outside the modal
            'close-on-backdrop-click' => false,
            // Trap the users focus inside the modal (e.g. input autofocus and going back and forth between input fields)
            'trap-focus' => true,
            // Remove all unsaved changes once someone closes the modal
            'remove-state-on-close' => true,
        ];
    }

    public static function attributes(): array
    {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => 'fullscreen',
        ];
    }
}
