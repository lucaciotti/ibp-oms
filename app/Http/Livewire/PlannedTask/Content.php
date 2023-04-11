<?php

namespace App\Http\Livewire\PlannedTask;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\PlanType;
use Session;

class Content extends DynamicContent
{
    public $plantypes;
    public $plantype_id;

    public function mount(){
        if (!Session::has('plannedtask.plantype.id')) {
            $planType = PlanType::first();
            Session::put('plannedtask.plantype.id', $$planType->id);
        }
        $this->plantype_id = Session::get('plannedtask.plantype.id');
    }

    public function render()
    {
        $this->plantypes = PlanType::all();
        return view('livewire.planned-task.content');
    }
}
