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
    public $importTypes;
    public $completed;

    public $import_type_id;

    public $title = 'Seleziona Xls Format';

    public function mount($tasks_ids, $type_id, $completed=false) {
        $this->tasks_ids = $tasks_ids;
        $this->type_id = $type_id;
        $this->completed = $completed;
        if($completed) $this->title = 'Seleziona Xls Format (COMPLETATI)';
        $this->importTypes = PlanImportType::where('type_id', $type_id)->where('use_in_export', true)->get();
        $this->import_type_id = $this->importTypes->where('default_export', true)->first()->id;
    }

    public function render()
    {
        return view('livewire.xls-export.xls-export-modal');
    }

    public function doExport()
    {
        Session::put('plannedtask.xlsExport.task_ids', $this->tasks_ids);
        Session::put('plannedtask.xlsExport.import_type_id', $this->import_type_id);
        if($this->completed){
            return redirect()->route('exportxls_completed_tasks');
        } else {
            return redirect()->route('exportxls_tasks');
        }
        $this->close();
    }
}
