<?php

namespace App\Http\Livewire\PlannedTask;

use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use Carbon\Carbon;
use Laratrust;
use Livewire\Component;
use WireElements\Pro\Components\Modal\Modal;

class PlannedTaskModalEdit extends Modal
{
    public $title;
    public $disabled;

    public $task_id;
    public PlannedTask $task;
    public $aTask;
    public $typeAttrs;
    
    public $index = 0;
    public $exclAttribute = ['ibp_plan_matricola', 'ibp_prodotto_tipo', 'ibp_cliente_ragsoc', 'ibp_tipologia', 'ibp_data_consegna', 'ibp_data_inizio_prod'];

    public function mount($id, $readOnly=false){
        $this->task_id = $id;
        $this->disabled = ($readOnly) ? 'disabled' : '';
        $this->task = PlannedTask::find($id)->makeHidden(['type_id', 'completed']);
        $this->aTask = $this->task->toArray();
        // dd($this->aTask['ibp_data_consegna']);
        $this->typeAttrs = PlanTypeAttribute::where('type_id', $this->task->type_id)->with('attribute')->get();
        $this->title = "Pianificazione [Matricola: ".$this->task->ibp_plan_matricola."]";
        if (!Laratrust::isAbleTo('tasks-update')) {
            $this->disabled = 'disabled';
        }

    }

    public function updated($propertyName)
    {
        // dd($propertyName);
    }

    public function save(){
        // Check date value
        foreach($this->typeAttrs as $typeA){
            if ($typeA->attribute->col_type=="date"){
                // dd(new Carbon(date('d-m-Y',strtotime($this->aTask[$typeA->attribute->col_name]))));
                $this->aTask[$typeA->attribute->col_name] = new Carbon(date('d-m-Y',strtotime($this->aTask[$typeA->attribute->col_name])));
            }
        }
        $this->task->update($this->aTask);
        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    public function render()
    {
        return view('livewire.planned-task.planned-task-modal-edit');
    }

    public static function attributes(): array
    {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => '4xl',
        ];
    }
}
