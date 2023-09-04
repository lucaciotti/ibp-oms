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
    
    public $reports = [
        'plan' => 'Distinta Pianificazioni (da completare)',
        'plan_ended' => 'Pianificazioni Completare',
        'stat_imp' => 'Statistiche Impianti',
        'stat_ral' => 'Statistiche RAL',
        'stat_imb' => 'Statistiche Imballi',
    ];
    
    public $title = 'Report PDF';
    public $pdfReport;

    protected function do_plan_report(){
        
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_consegna')))->format('d/m/Y');

        $columns = Arr::pluck(Attribute::select('col_name')->whereHas('planTypeAttribute', fn ($query) => $query->where('type_id', $this->type_id))->where('col_name', '!=', 'ibp_plan_matricola')->get()->toArray(), 'col_name');
        $n_of_columns = count($columns);
        $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->where('completed', false)->groupBy($columns)->orderBy('ibp_data_consegna')->orderBy('ibp_cliente_ragsoc')->get();
        $tasks = [];
        foreach ($tasksWithSameValues as $task) {
            $aWhere = [];
            foreach ($columns as $column) {
                array_push($aWhere, [$column, $task->$column]);
            }
            $matricole = Arr::pluck(PlannedTask::select('ibp_plan_matricola')->where($aWhere)->whereIn('id', $this->tasks_ids)->where('completed', false)->get()->toArray(), 'ibp_plan_matricola');
            $chunck_matricole = array_chunk($matricole, 8);
            foreach ($chunck_matricole as $aMat) {
                $aTask=[
                    'matricole' => $aMat,
                    'values' => $task->toArray(),
                    'qta' => count($aMat),
                ];
                array_push($tasks, $aTask);
            }
        }
        // dd($tasks);
        $title = "Pianificazioni_da_Elaborare_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin."_".$dtMax);
        $view = 'ibp._exports.pdf.tasksPlan-'. $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
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
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', true)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', true)->max('ibp_data_consegna')))->format('d/m/Y');

        $columns = Arr::pluck(Attribute::select('col_name')->whereHas('planTypeAttribute', fn ($query) => $query->where('type_id', $this->type_id))->where('col_name', '!=', 'ibp_plan_matricola')->get()->toArray(), 'col_name');
        $n_of_columns = count($columns);
        $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->where('completed', true)->groupBy($columns)->orderBy('ibp_data_consegna')->orderBy('ibp_cliente_ragsoc')->get();
        $tasks = [];
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
                array_push($tasks, $aTask);
            }
        }

        $title = "Pianificazioni_Completate_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin . "_" . $dtMax);
        $view = 'ibp._exports.pdf.tasksPlan-' . $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
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
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_consegna')))->format('d/m/Y');

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
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_consegna')))->format('d/m/Y');

        $tasks = PlannedTask::select('ibp_ral', 'ibp_basamento', 'ibp_colonna', 'ibp_prodotto_tipo', 'ibp_carrello', 'ibp_braccio')->whereIn('id', $this->tasks_ids)->where('completed', false)->get();

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
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->where('completed', false)->max('ibp_data_consegna')))->format('d/m/Y');

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
        $rals = $this->getUniqueFromCollection($tasks,'ibp_ral');
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
            'rals' => $rals,
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
        $arrayOfValues = Arr::pluck($collect->where($key, '!=', '')->unique($key)->sortBy($key)->toArray(), $key);
        // $aMapped = Arr::map($arrayOfValues, function (string $value, string $key) {
        //     return Str::upper($value);
        // });
        // $arrayUnique = array_unique($aMapped);
        return $arrayOfValues;
    }

    public function mount($tasks_ids, $reportKey){

        $this->tasks_ids = $tasks_ids;
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
