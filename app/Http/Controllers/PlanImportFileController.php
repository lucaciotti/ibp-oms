<?php

namespace App\Http\Controllers;

use App\Exports\PlannedTempTaskExport;
use App\Models\PlanFilesTempTask;
use App\Models\PlanImportFile;
use App\Models\PlanType;
use Arr;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class PlanImportFileController extends Controller
{
    public function index(Request $req){
        return view('ibp.planimportfile');
    }

    public function rows(Request $req, $id)
    {
        $planimportfile = PlanImportFile::find($id);
        $req->session()->put('plannedtemptask.xlsExport.import_type_id', $planimportfile->import_type_id);
        return view('ibp.planimporttemptask', ['id' => $id]);
    }

    public function exportXls(Request $req){
        // dd();
        $tasks_ids = $req->session()->get('plannedtemptask.xlsExport.task_ids');
        $import_type_id = $req->session()->get('plannedtemptask.xlsExport.import_type_id');
        $type_id = (PlanFilesTempTask::find(Arr::first($tasks_ids)))->type_id;
        $planType = PlanType::find($type_id);
        $filename = $planType->name . '_Export-Temp-conErrori_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new PlannedTempTaskExport($tasks_ids, $import_type_id), $filename);
    }
}
