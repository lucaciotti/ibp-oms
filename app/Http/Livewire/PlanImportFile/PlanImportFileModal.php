<?php

namespace App\Http\Livewire\PlanImportFile;

use App\Jobs\ImportFileExcelRows;
use App\Models\PlanImportFile;
use App\Models\PlanImportType;
use App\Models\PlanType;
use App\Notifications\DefaultMessageNotify;
use Auth;
use Carbon\Carbon;
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
    public $name = '';
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
        'name' => 'required',
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
        $this->getPlanImportTypes();
        if (empty($this->import_type_id)) $this->getDefaultPlanImportTypes();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedFile(){
        // dd($this->file->getClientOriginalName());
        $this->file_extension = strtolower($this->file->getClientOriginalExtension());
        $this->validate();
        if($this->file){
            $this->file_placeolder = $this->file->getClientOriginalName();
        } else {
            $this->file_placeolder = 'Carica file excel...';
        }
        
    }

    public function updatedTypeId()
    {
        $this->getPlanImportTypes();
        $this->getDefaultPlanImportTypes();
    }

    private function buildFileName()
    {   
        $date = Carbon::now();
        // return 'Plan_' . $this->planTypes->where('id', $this->type_id)->first()->name . '_' . $date->format('Ymd') . '_' . $date->format('Hmi').'.'. $this->file_extension;
        
        return $this->file->getClientOriginalName();
    }

    private function buildImportName()
    {
        $date = Carbon::now();
        return $this->planTypes->where('id', $this->type_id)->first()->name . '_' . $date->format('Ymd') . '_' . $date->format('Hmi');
    }

    private function getPlanImportTypes()
    {
        $this->planImportTypes = PlanImportType::where('type_id', $this->type_id)->where('use_in_import', true)->get();
    }

    private function getDefaultPlanImportTypes()
    {
        $this->import_type_id = $this->planImportTypes->where('default_import', true)->first()->id;
    }

    public function render()
    {
        return view('livewire.plan-import-file.plan-import-file-modal');
    }

    public function save(){
        $this->filename = $this->buildFileName();
        $this->name = $this->buildImportName();
        $validatedData = $this->validate();
        $this->path = $this->file->store('plan_import_file');
        $extradata = [
            'status' => 'File Caricato',
            'path' => $this->path,
        ];
        // dd(array_merge($validatedData, $extradata));
        $planImportFile = PlanImportFile::create(array_merge($validatedData, $extradata));

        //TODO Avvia JOb
        ImportFileExcelRows::dispatch($planImportFile->id)->onQueue('importFiles');

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

