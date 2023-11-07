<?php

namespace App\Http\Livewire\XlsExport;

use App\Models\PlanImportType;
use App\Models\PlanType;
use Livewire\Component;
use WireElements\Pro\Components\Modal\Modal;
use Session;

class XlsAllExportModal extends Modal
{
    public $title = 'Export di tutte le pianificazioni';

    // public $planTypes;
    public $eachPlanConfs = [];

    // Filtri dinamici
    public $filters = [];
    public $ordersBy = [];

    public $index = 0;
    public $error_filter = '';
    
    public function mount()
    {
        $planTypes = PlanType::all();
        foreach ($planTypes as $value) {
            $exportXlsTypes = PlanImportType::where('type_id', $value->id)->where('use_in_export', true)->get();
            $strId = strval($value->id);
            $this->eachPlanConfs[$strId] = [];
            $this->eachPlanConfs[$strId]['planType'] = $value->toArray();
            $this->eachPlanConfs[$strId]['selected'] = true;
            $this->eachPlanConfs[$strId]['xlsTypes'] = $exportXlsTypes->toArray();
            $this->eachPlanConfs[$strId]['xlsTypeId'] = ($exportXlsTypes->where('default_export', true)->count() > 0) ? $exportXlsTypes->where('default_export', true)->first()->id : (($exportXlsTypes->count() > 0) ? $exportXlsTypes->first()->id : null);
        }

        $filters['dateProdFrom'] = ['label'=> 'Data Prod. [>=]', 'type'=>'date', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'ibp_data_inizio_prod', 'operator' => '>='];
        $filters['dateProdTo'] = ['label' => 'Data Prod. [<=]', 'type'=>'date', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'ibp_data_inizio_prod', 'operator' => '<='];
        $filters['dateConsFrom'] = ['label' => 'Data Consegna [>=]', 'type'=>'date', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'ibp_data_consegna', 'operator' => '>='];
        $filters['dateConsTo'] = ['label' => 'Data Consegna [<=]', 'type'=>'date', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'ibp_data_consegna', 'operator' => '<='];
        $filters['matricola'] = ['label' => 'Matricola', 'type'=>'string', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'ibp_plan_matricola', 'operator' => 'like'];
        $filters['customer'] = ['label' => 'Cliente', 'type'=> 'string', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'ibp_cliente_ragsoc', 'operator' => 'like'];
        $filters['divider'] = ['label' => '', 'type'=>'divider', 'value'=>'', 'valuelist'=>'', 'column_name'=> '', 'operator' => ''];
        $filters['dateCompletesFrom'] = ['label' => 'Data Completato [>=]', 'type'=>'date', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'completed_date', 'operator' => '>='];
        $filters['dateCompletesTo'] = ['label' => 'Data Completato [<=]', 'type'=>'date', 'value'=>'', 'valuelist'=>'', 'column_name'=> 'completed_date', 'operator' => '<='];
        $filters['completed'] = ['label' => 'Completato', 'type'=>'choice', 'value'=>'all', 'valuelist'=>[['value'=>'', 'label' => 'Tutti'], ['value' => 'true', 'label' => 'Si'], ['value' => 'false', 'label' => 'No']], 'column_name' => 'completed', 'operator' => '='];
        $this->filters = $filters;
    }

    public function render()
    {
        return view('livewire.xls-export.xls-all-export-modal');
    }

    public function doExport()
    {   
        $emptyFilters = true;
        foreach ($this->filters as $key => $value) {
            if($value['type']=='choice') continue;
            if(!$emptyFilters) break;
            $emptyFilters = $value['value']=='';
        }
        if($emptyFilters) {
            $this->error_filter = 'Attenzione compilare almeno un filtro!';
            return;
        }
        
        Session::put('plannedtask.xlsAllExport.filters', $this->filters);
        Session::put('plannedtask.xlsAllExport.planConf', $this->eachPlanConfs);
        return redirect()->route('exportxls_alltasks');
        $this->close();
    }



    public static function behavior(): array
    {
        return [
            // Close the modal if the escape key is pressed
            'close-on-escape' => true,
            // Close the modal if someone clicks outside the modal
            'close-on-backdrop-click' => true,
            // Trap the users focus inside the modal (e.g. input autofocus and going back and forth between input fields)
            'trap-focus' => true,
            // Remove all unsaved changes once someone closes the modal
            'remove-state-on-close' => false,
        ];
    }

    public static function attributes(): array
    {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => '7xl',
        ];
    }
}
