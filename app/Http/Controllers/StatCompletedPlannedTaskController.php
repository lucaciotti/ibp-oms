<?php

namespace App\Http\Controllers;

use App\Exports\PlannedTaskAllExport;
use App\Exports\PlannedTaskCompletedExport;
use App\Exports\PlannedTaskExport;
use App\Exports\StatPlannedTaskExport;
use App\Models\PlanType;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;

class StatCompletedPlannedTaskController extends Controller
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
        return view('ibp.stat_completed_plannedtask', ['planType' => $planType]); 
    }

    public function exportXls(Request $req)
    {
        // dd();
        $plantype_id = $req->session()->get('statplannedtask.plantype.id');
        $month = $req->session()->get('statplannedtask.filter.month');
        $completed = $req->session()->get('statplannedtask.filter.completed');
        $datetype = $req->session()->get('statplannedtask.filter.datetype');
        $planType = PlanType::find($plantype_id);
        $filename = $planType->name . '_STAT_Export_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new StatPlannedTaskExport($plantype_id, $month, $completed, $datetype), $filename);
    }

}

