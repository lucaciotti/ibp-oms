<?php

namespace App\Http\Livewire\PlanImportType;

use App\Http\Livewire\Layouts\DynamicContent;

class Content extends DynamicContent
{
    public $type_id;

    public function mount($type_id){
        $this->type_id = $type_id;
    }

    public function render()
    {
        return view('livewire.plan-import-type.content');
    }
}
