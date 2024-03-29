<?php

namespace App\Http\Livewire\XlsExport;

use App\Exports\PlannedTaskExport;
use App\Models\PlanImportType;
use Maatwebsite\Excel\Excel;
use Session;
use WireElements\Pro\Components\Modal\Modal;

class XlsExportModal extends Modal
{
    public $tasks_ids;
    public $type_id;
    public $order_tasks;
    public $filter_on_tasks;
    public $importTypes;
    public $completed;

    public $import_type_id;

    public $title = 'Seleziona Xls Format';

    public function mount($tasks_ids, $type_id, $completed=false, $configs=null) {
        $this->tasks_ids = $tasks_ids;
        $this->type_id = $type_id;
        $this->completed = $completed;
        $this->order_tasks = (array_key_exists('order', $configs)) ? $configs['order'] : [];
        $this->filter_on_tasks = (array_key_exists('filters', $configs)) ? $configs['filters'] : [];
        if($completed) $this->title = 'Seleziona Xls Format (COMPLETATI)';
        $this->importTypes = PlanImportType::where('type_id', $type_id)->where('use_in_export', true)->get();
        $this->import_type_id = ($this->importTypes->where('default_export', true)->count()>0) ? $this->importTypes->where('default_export', true)->first()->id : (($this->importTypes->count()>0) ? $this->importTypes->first()->id : null);
    }

    public function render()
    {
        return view('livewire.xls-export.xls-export-modal');
    }

    public function doExport()
    {
        Session::put('plannedtask.xlsExport.task_ids', $this->tasks_ids);
        Session::put('plannedtask.xlsExport.import_type_id', $this->import_type_id);
        Session::put('plannedtask.xlsExport.order_tasks', $this->order_tasks);
        Session::put('plannedtask.xlsExport.filter_on_tasks', $this->filter_on_tasks);
        if($this->completed){
            return redirect()->route('exportxls_completed_tasks');
        } else {
            return redirect()->route('exportxls_tasks');
        }
        $this->close();
    }
}
