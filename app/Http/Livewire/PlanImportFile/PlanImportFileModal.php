<?php

namespace App\Http\Livewire\PlanImportFile;

use App\Models\PlanImportFile;
use App\Models\PlanImportType;
use App\Models\PlanType;
use App\Notifications\DefaultMessageNotify;
use Auth;
use Livewire\WithFileUploads;
use Notification;
use WireElements\Pro\Components\Modal\Modal;

class PlanImportFileModal extends Modal
{
    use WithFileUploads;
 
    public $title;
    
    public $file;
    public $type_id;
    public $import_type_id;
    public bool $force_import = false;

    public $file_extension;
    public $path = '';
    public $filename = '';
    public $file_placeolder = 'Carica file excel...';

    public $planTypes;
    public $planImportTypes;

    protected $rules = [
        'file' => 'required',
        'file_extension' => 'required|in:xlsx,xls',
        'filename' => 'required',
        'type_id' => 'required',
        'import_type_id' => 'required',
        'force_import' => 'required',
    ];

    public function mount($type_id=null){
        $this->title = "Importazione File XLS Pianificazioni";
        $this->planTypes = PlanType::all();
        if($type_id){
            $this->type_id = $type_id;
        } else {
            $this->type_id = $this->planTypes->first()->id;
        }
        $this->planImportTypes = PlanImportType::where('type_id', $this->type_id)->get();
        $this->import_type_id = $this->planImportTypes->first()->id;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedFile(){
        // dd($this->file->getClientOriginalName());
        $this->file_extension = strtolower($this->file->getClientOriginalExtension());
        $this->filename = $this->file->getClientOriginalName();
        $this->validate();
        if($this->file){
            $this->file_placeolder = $this->file->getClientOriginalName();
        } else {
            $this->file_placeolder = 'Carica file excel...';
        }
        
    }

    public function updatedTypeId(){
        $this->validate();
        $this->planImportTypes = PlanImportType::where('type_id', $this->type_id)->get();
    }

    public function render()
    {
        return view('livewire.plan-import-file.plan-import-file-modal');
    }

    public function save(){
        $validatedData = $this->validate();
        $this->path = $this->file->store('plan_import_file');
        $extradata = [
            'status' => 'uploaded',
            'path' => $this->path,
        ];
        // dd(array_merge($validatedData, $extradata));
        PlanImportFile::create(array_merge($validatedData, $extradata));

        //TODO Avvia JOb

        Notification::send(Auth::user(), new DefaultMessageNotify(
            $title = 'Import Pianificazione - '. $this->planTypes->where('id', $this->type_id)->first()->name,
            $body = 'Avviato processo di Importazione Pianificazioni da file ' . $validatedData['filename'],
            $link = '#',
            $level = 'info'
        ));

        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }
}

