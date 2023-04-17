<?php

namespace App\Http\Controllers;

use App\Exports\PlannedTaskExport;
use App\Models\PlanType;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class PlannedTaskController extends Controller
{
    public function index(Request $req, $id = null)
    {
        if (!empty($id)) {
            $req->session()->put('plannedtask.plantype.id', $id);
        } else {
            if(!$req->session()->has('plannedtask.plantype.id')){
                $planType = PlanType::first();
                if($planType) $req->session()->put('plannedtask.plantype.id', $planType->id);
            }
        }
        $planType = ($req->session()->has('plannedtask')) ? PlanType::find($req->session()->get('plannedtask.plantype.id')) : null;
        return view('ibp.plannedtask', ['planType' => $planType]);
    }

    public function exportXls(Request $req){
        // dd();
        $tasks_ids = $req->session()->get('plannedtask.xlsExport.task_ids');
        $import_type_id = $req->session()->get('plannedtask.xlsExport.import_type_id');
        $req->session()->forget('plannedtask.xlsExport.task_ids');
        $req->session()->forget('plannedtask.xlsExport.import_type_id');
        $planType = PlanType::find($req->session()->get('plannedtask.plantype.id'));
        $filename = $planType->name . '_Export_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new PlannedTaskExport($tasks_ids, $import_type_id), $filename);
    }
}

