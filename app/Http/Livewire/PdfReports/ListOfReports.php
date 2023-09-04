<?php

namespace App\Http\Livewire\PdfReports;

use WireElements\Pro\Components\Modal\Modal;

class ListOfReports extends Modal
{
    public $tasks_ids;
    public $type_id;
    public $reports = [
        'plan' => 'Distinta di Produzione (da completare)',
        'plan_ended' => 'Pianificazioni Completare',
        'stat_imp' => 'Statistiche Impianti',
        'stat_ral' => 'Statistiche RAL',
        'stat_imb' => 'Statistiche Imballi',
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
