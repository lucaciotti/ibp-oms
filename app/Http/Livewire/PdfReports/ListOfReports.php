<?php

namespace App\Http\Livewire\PdfReports;

use WireElements\Pro\Components\Modal\Modal;

class ListOfReports extends Modal
{
    public $tasks_ids;
    public $type_id;
    public $configs;
    public $reports = [
        'plan' => 'Distinta di Produzione',
        'plan_ended' => 'Report SOLO COMPLETATE',
        'stat_imp' => 'Statistiche Impianti',
        'stat_ral' => 'Statistiche RAL',
        'stat_imb' => 'Statistiche Imballi',
    ];
    public $selectedReport = 'plan';

    public $title = 'Seleziona Report';

    // public function mount($tasks_ids, $type_id, $configs=null) {
    //     dd($configs);
    //     $this->tasks_ids = $tasks_ids;
    //     $this->type_id = $type_id;
    //     $this->configs = $configs;
    // }

    public function render()
    {
        return view('livewire.pdf-reports.list-of-reports');
    }

    public function doReport()
    {
        $this->emit('modal.open', 'pdf-reports.generate-reports', ['reportKey' => $this->selectedReport, 'tasks_ids' => $this->tasks_ids, 'type_id' => $this->type_id, 'configs' => $this->configs], ['force' => true]);
    }
}
