<?php

namespace App\Jobs;

use App\Imports\PlanTempTasksImport;
use App\Models\PlanFilesTempTask;
use App\Models\PlanImportFile;
use App\Models\PlannedTask;
use App\Models\User;
use App\Notifications\DefaultMessageNotify;
use DB;
use Excel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Notification;

class ImportFileExcelRows implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PlanImportFile $importedfile;
    private $hasWarnings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import_file_id)
    {
        Log::info('ImportFileExcelRow Job Created');
        $this->importedfile = PlanImportFile::find($import_file_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('ImportFileExcelRow Job Started');
        $this->importedfile->status = 'Processing';
        $this->importedfile->save();
        PlanFilesTempTask::where('import_file_id', $this->importedfile->id)->delete();
        Excel::import(new PlanTempTasksImport($this->importedfile->id), storage_path('app/' . $this->importedfile->path));
        $this->analizeTempTasks();
        ProcessTempTasks::dispatch($this->importedfile->id, $this->hasWarnings)->onQueue('processTasks');
    }

    private function analizeTempTasks(){
        DB::transaction(function () {
            $force_import = $this->importedfile->force_import;
            $tempRows = $this->importedfile->planfiletemptasks;
            $this->hasWarnings = false;
            $aPlanMatricola=[];
            if (count($tempRows)>0){
                foreach ($tempRows as $row) {
                    if(!in_array($row->ibp_plan_matricola, $aPlanMatricola)){
                        array_push($aPlanMatricola, $row->ibp_plan_matricola);
                        $plannedTask = PlannedTask::where('ibp_plan_matricola', $row->ibp_plan_matricola)->first();
                        if($plannedTask != null){
                            $row->task_id = $plannedTask->id;
                            if($plannedTask->audits->last()->user_id != null){
                                $row->warning = true;
                                $row->error = 'Matricola già presente e modificata da utente!';
                                $this->hasWarnings = true;
                            } else {
                                $row->selected = true;
                            }
                            if($force_import) {
                                $row->selected = true;
                            }
                        } else {
                            $row->selected = true;
                        }
                    } else {
                        $row->warning = true;
                        $row->error = 'Riga Duplicata in file!';
                        $this->hasWarnings = true;
                    }
                    $row->save();
                }
            }
            if ($this->hasWarnings){
                Notification::send($this->importedfile->userCreated(),
                    new DefaultMessageNotify(
                        $title = 'File di Import - Da Verificare!',
                        $body = 'Alcune righe sono già presenti e sono gia state modificate da utenti! [' . $this->importedfile->filename . ']',
                        $link = '#',
                        $level = 'warning'
                    ));
            }
        });
    }

    public function failed(\Throwable $e)
    {

        $this->importedfile->status = 'Errore';
        $this->importedfile->save();
        report($e);

        $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', $this->importedfile->userCreated()->id)->get();
        if(get_class($e)== 'App\Exceptions\ImportFileException'){
            foreach ($notifyUsers as $user) {
                Notification::send(
                    $user,
                    new DefaultMessageNotify(
                        $title = 'Import File - [' . $this->importedfile->filename . ']!',
                        $body = 'Errore: '. $e->getMessage(),
                        $link = '#',
                        $level = 'error'
                    )
                );
            }
        } else {
            #INVIO NOTIFICA
            foreach ($notifyUsers as $user) {
                Notification::send(
                    $user,
                    new DefaultMessageNotify(
                        $title = 'Import File - [' . $this->importedfile->filename . ']!',
                        $body = 'Errore: File di Importazione Non corretto!',
                        $link = '#',
                        $level = 'error'
                    )
                );
            }
        }
        return false;
    }
}
