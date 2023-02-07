<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Imports\MachineTasksImport;
use Maatwebsite\Excel\Facades\Excel;

class UploadTasks extends Component
{
    use WithFileUploads;
 
    public $task;
 
    public function save()
    {
        $this->validate([
            'task' => 'file|mimes:xls,xlsx|max:102400', // 1MB Max
        ]); 
        
        Excel::import(new MachineTasksImport, $this->task->store('Machine-Tasks'));

        return redirect('/machine_jobs')->with('success', 'All good!');


    }

    public function render()
    {
        return view('livewire.upload-tasks');
    }
}
