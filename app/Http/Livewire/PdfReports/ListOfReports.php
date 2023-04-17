<?php

namespace App\Http\Livewire\PdfReports;

use WireElements\Pro\Components\Modal\Modal;

class ListOfReports extends Modal
{
    public $tasks_ids;
    public $type_id;
    public $reports = [
        'plan' => 'Distinta Pianificazioni',
        'stat' => 'Statistiche',
    ];
    public $selectedReport = 'plan';

    public $title = 'Seleziona Report';

    public function render()
    {
        return view('livewire.pdf-reports.list-of-reports');
    }

    public function doReport(){
        $this->emit('modal.open', 'pdf-reports.generate-reports', ['reportKey' => $this->selectedReport, 'tasks_ids' => $this->tasks_ids, 'type_id' => $this->type_id], ['force' => true]);
    }
}
