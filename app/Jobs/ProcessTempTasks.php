<?php

namespace App\Jobs;

use App\Models\PlanImportFile;
use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use App\Notifications\DefaultMessageNotify;
use Carbon\Carbon;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Notification;

class ProcessTempTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private PlanImportFile $importedfile;
    private $hasWarnings;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($import_file_id, $hasWarnings=false)
    {
        Log::info('ProcessTempTasks Job Created');
        $this->importedfile = PlanImportFile::with('plantype')->find($import_file_id);
        $this->hasWarnings = $hasWarnings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('ProcessTempTasks Job Started');

        DB::transaction(function () {
            $tempRows = $this->importedfile->planfiletemptasks;
            $listTypeAttr = PlanTypeAttribute::where('type_id', $this->importedfile->plantype->id)->with('attribute')->get();
            foreach ($tempRows as $row) {
                $dataRow = [];
                if ($row->selected) {
                    $aRow = $row->toArray();
                    $dataRow['type_id'] = $row->type_id;
                    foreach ($listTypeAttr as $typeAttr) {
                        switch ($typeAttr->attribute->col_type) {
                            case 'date':
                                $data = Carbon::createFromFormat('d-m-Y', $aRow[$typeAttr->attribute->col_name]);
                                break;

                            default:
                                $data = $aRow[$typeAttr->attribute->col_name];
                                break;
                        }
                        $dataRow[$typeAttr->attribute->col_name] = $data;
                    }
                    if (empty($row->task_id)) {
                        PlannedTask::create($dataRow);
                    } else {
                        PlannedTask::find($row->task_id)->update($dataRow);
                    }
                    $row->selected = false;
                    $row->imported = true;
                    $row->date_last_import = Carbon::now();
                    $row->save();
                }
            }
            $this->importedfile->date_last_import = Carbon::now();
            $this->importedfile->status = ($this->hasWarnings) ? 'Verificare' : 'Processato';
            $this->importedfile->save();
            Notification::send(
                $this->importedfile->userCreated(),
                new DefaultMessageNotify(
                    $title = 'File di Import - Processato!',
                    $body = 'Righe del file [' . $this->importedfile->filename . '] processate correttamente!',
                    $link = '#',
                    $level = 'info'
                    )
                );
        });  
        Log::info('ProcessTempTasks Job Ended');
    }


    public function failed(\Throwable $e)
    {
        $this->importedfile->status = 'Errore';
        $this->importedfile->save();
        report($e);
        return false;
    }
}
