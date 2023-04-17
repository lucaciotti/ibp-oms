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
        'plan' => 'Distinta Pianificazioni',
        'stat' => 'Statistiche',
    ];
    
    public $title = 'Report PDF';
    public $pdfReport;

    protected function do_plan_report(){
        
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->max('ibp_data_consegna')))->format('d/m/Y');

        $columns = Arr::pluck(Attribute::select('col_name')->whereHas('planTypeAttribute', fn ($query) => $query->where('type_id', $this->type_id))->where('col_name', '!=', 'ibp_plan_matricola')->get()->toArray(), 'col_name');
        $n_of_columns = count($columns);
        $tasksWithSameValues = PlannedTask::select($columns)->whereIn('id', $this->tasks_ids)->groupBy($columns)->orderBy('ibp_data_consegna')->orderBy('ibp_cliente_ragsoc')->get();
        $tasks = [];
        foreach ($tasksWithSameValues as $task) {
            $aWhere = [];
            foreach ($columns as $column) {
                array_push($aWhere, [$column, $task->$column]);
            }
            $matricole = Arr::pluck(PlannedTask::select('ibp_plan_matricola')->where($aWhere)->get()->toArray(), 'ibp_plan_matricola');
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

        $title = "Pianificazioni_" . $planName;
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
        $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
    }

    protected function do_stat_report(){
        $planName = Str::upper((PlanType::select('name')->find($this->type_id))->name);
        $dtMin = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->min('ibp_data_consegna')))->format('d/m/Y');
        $dtMax = (new Carbon(PlannedTask::whereIn('id', $this->tasks_ids)->max('ibp_data_consegna')))->format('d/m/Y');

        $tasks = PlannedTask::select('ibp_ral', 'ibp_basamento', 'ibp_colonna', 'ibp_prodotto_tipo', 'ibp_carrello')->whereIn('id', $this->tasks_ids)->get();
        
        $rals = Arr::pluck(PlannedTask::select('ibp_ral')->whereIn('id', $this->tasks_ids)->where('ibp_ral', '!=', '')->orderBy('ibp_ral')->distinct()->get()->toArray(), 'ibp_ral');
        $basaments = Arr::pluck(PlannedTask::select('ibp_basamento')->whereIn('id', $this->tasks_ids)->where('ibp_basamento', '!=', '')->orderBy('ibp_basamento')->distinct()->get()->toArray(), 'ibp_basamento');
        $statRal = [
            'rals' => $rals,
            'basaments' => $basaments,
        ];

        $prods = Arr::pluck(PlannedTask::select('ibp_prodotto_tipo')->whereIn('id', $this->tasks_ids)->where('ibp_prodotto_tipo', '!=', '')->orderBy('ibp_prodotto_tipo')->distinct()->get()->toArray(), 'ibp_prodotto_tipo');
        $colonne = Arr::pluck(PlannedTask::select('ibp_colonna')->whereIn('id', $this->tasks_ids)->where('ibp_colonna', '!=', '')->orderBy('ibp_colonna')->distinct()->get()->toArray(), 'ibp_colonna');
        $prodsLabel = [];
        foreach ($prods as $prod) {
            array_push($prodsLabel, trim(str_replace('TOUCH', '', $prod)));
        }
        $prodsLabel = array_unique($prodsLabel);
        $statImpianti = [
            'prods' => $prodsLabel,
            'colonne' => $colonne,
        ];

        $statCarrelli = Arr::pluck(PlannedTask::select('ibp_carrello')->whereIn('id', $this->tasks_ids)->where('ibp_carrello', '!=', '')->orderBy('ibp_carrello')->distinct()->get()->toArray(), 'ibp_carrello');

        $title = "Statistiche_" . $planName;
        $subTitle = str_replace('/', '-', $dtMin . "_" . $dtMax);
        $view = 'ibp._exports.pdf.tasksStat-' . $planName;
        $data = [
            'planName' => $planName,
            'dtMin' => $dtMin,
            'dtMax' => $dtMax,
            'tasks' => $tasks,
            'statRal' => $statRal,
            'statImpianti' => $statImpianti,
            'statCarrelli' => $statCarrelli,
        ];
        // dd($data);
        $this->pdfReport = $title . '-' . $subTitle . '_' . Carbon::now()->format('YmdHis') . '.pdf';
        $pdf = PdfReport::A4Portrait($view, $data, $title, $subTitle);
        $pdf->save(base_path('public/tmp_pdf/' . $this->pdfReport));
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
        $delete = unlink(base_path('public/tmp_pdf/' . $this->pdfReport));
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
