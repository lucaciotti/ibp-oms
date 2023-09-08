<?php

namespace App\Http\Livewire\PlannedTask;

use App\Models\PlannedTask;
use Livewire\Component;
use WireElements\Pro\Components\Modal\Modal;

class PlannedTaskModalDelete extends Modal
{
    public $tasks_ids;
    public $title = "ELIMINA Pianificazioni";

    public function render()
    {
        return view('livewire.planned-task.planned-task-modal-delete');
    }

    public function delete(){
        foreach ($this->tasks_ids as $id) {
            PlannedTask::find($id)->delete();
        }
        $this->close(
            andEmit: [
                'refreshDatatable',
                'clearSelected'
            ]
        );
    }
}
