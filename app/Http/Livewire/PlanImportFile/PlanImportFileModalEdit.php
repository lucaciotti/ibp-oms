<?php

namespace App\Http\Livewire\PlanImportFile;

use App\Jobs\ImportFileExcelRows;
use App\Models\PlanFilesTempTask;
use App\Models\PlanImportFile;
use App\Models\PlanImportType;
use App\Models\PlanType;
use App\Models\User;
use App\Notifications\DefaultMessageNotify;
use Auth;
use DB;
use Notification;
use WireElements\Pro\Components\Modal\Modal;
use WireElements\Pro\Concerns\InteractsWithConfirmationModal;

class PlanImportFileModalEdit extends Modal
{
    use InteractsWithConfirmationModal;
    
    public $title;

    public $plan_file_id;
    
    public $type_id;
    public $import_type_id;
    public bool $force_import = false;
    
    public $filename = '';
    
    public $planImportFile;
    public $planTypes;
    public $planImportTypes;

    protected $rules = [
        'type_id' => 'required',
        'import_type_id' => 'required',
        'force_import' => 'required',
    ];

    public function mount($plan_file_id, $askDelete=0)
    {
        $this->title = "Modifica File XLS Pianificazioni";
        $this->plan_file_id = $plan_file_id;
        $this->planImportFile = PlanImportFile::with('planimporttype')->find($plan_file_id);

        $this->filename = $this->planImportFile->filename;
        $this->type_id = $this->planImportFile->planimporttype->type_id;
        $this->import_type_id = $this->planImportFile->import_type_id;
        $this->force_import = $this->planImportFile->force_import;

        $this->planTypes = PlanType::all();        
        $this->planImportTypes = PlanImportType::where('type_id', $this->type_id)->get();
        if(empty($this->import_type_id)) $this->import_type_id = $this->planImportTypes->where('default', true)->first()->id;
    }

    public function render()
    {
        return view('livewire.plan-import-file.plan-import-file-modal-edit');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function updatedTypeId(){
        $this->validate();
        $this->planImportTypes = PlanImportType::where('type_id', $this->type_id)->get();
        $this->import_type_id = $this->planImportTypes->where('default', true)->first()->id;
    }

    public function save(){
        $validatedData = $this->validate();
        $deleteTempRows = false;
        if($this->planImportFile->import_type_id != $this->import_type_id || $this->planImportFile->force_import != $this->force_import ){
            $deleteTempRows = true;
            $extradata = [
                'status' => 'Modificato',
            ];
        }
        
        try {
            DB::transaction(function () use ($validatedData, $extradata, $deleteTempRows) {
                if ($deleteTempRows) PlanFilesTempTask::where('import_file_id', $this->planImportFile->id)->delete();
                $this->planImportFile->update(array_merge($validatedData, $extradata));
            });
            //TODO Avvia JOb
            ImportFileExcelRows::dispatch($this->planImportFile->id)->onQueue('importFiles');
    
            Notification::send(Auth::user(), new DefaultMessageNotify(
                $title = 'File di Import - Modificato!',
                $body = 'Avviato processo di Importazione Pianificazioni da file ' . $this->planImportFile->filename,
                $link = '#',
                $level = 'warning'
            ));
        } catch (\Throwable $th) {
            report($th);
            #INVIO NOTIFICA
            $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', Auth::user()->id)->get();
            foreach ($notifyUsers as $user) {
                Notification::send(
                    $user,
                    new DefaultMessageNotify(
                        $title = 'File di Import - [' . $this->planImportFile->filename . ']!',
                        $body = 'Errore: [' . $th->getMessage() . ']',
                        $link = '#',
                        $level = 'error'
                    )
                );
            }
        }

        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    public function delete(){
        try {
            $filename = $this->planImportFile->filename;

            DB::transaction(function ()  {
                $this->planImportFile->delete();
            });

            Notification::send(Auth::user(), new DefaultMessageNotify(
                $title = 'File di Import - Cancellato!',
                $body = 'Cancellato file di Importazione Pianificazione ' . $filename,
                $link = '#',
                $level = 'warning'
            ));
        } catch (\Throwable $th) {
            report($th);
            #INVIO NOTIFICA
            $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', Auth::user()->id)->get();
            foreach ($notifyUsers as $user) {
                Notification::send(
                    $user,
                    new DefaultMessageNotify(
                        $title = 'File di Import - [' . $filename . ']!',
                        $body = 'Errore: [' . $th->getMessage() . ']',
                        $link = '#',
                        $level = 'error'
                    )
                );
            }
        }

        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    public function deleteConfirmation()
    {
        $this->askForConfirmation(
            callback: function () {
                return $this->delete();
            },
            prompt: [
                'title' => __('Attenzione!'),
                'message' => __('Cancellando la importazione si potrebbe perdere lo storico delle righe importate, procedere?'),
                'confirm' => __('Si, Cancella'),
                'cancel' => __('No'),
            ],
            confirmPhrase: 'CANCELLA',
            theme: 'warning',
            modalBehavior: [
                'close-on-escape' => false,
                'close-on-backdrop-click' => false,
                'trap-focus' => true,
            ],
            modalAttributes: [
                'size' => '2xl'
            ]
        );
    }

}
