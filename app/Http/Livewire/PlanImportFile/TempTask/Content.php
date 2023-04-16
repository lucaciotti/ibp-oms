<?php

namespace App\Http\Livewire\PlanImportFile\TempTask;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\PlanImportFile;
use App\Models\PlanImportType;

class Content extends DynamicContent
{
    public $file_id;
    public $file_imported;
    public $type_id;

    public function mount($file_id){
        $this->file_id = $file_id;
        $this->file_imported = PlanImportFile::with('planimporttype')->find($file_id);
        $this->type_id = $this->file_imported->planimporttype->type_id;
    }
    
    public function render()
    {
        return view('livewire.plan-import-file.temp-task.content');
    }
}
