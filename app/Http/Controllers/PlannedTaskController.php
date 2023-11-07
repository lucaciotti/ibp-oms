<?php

namespace App\Http\Controllers;

use App\Exports\PlannedTaskAllExport;
use App\Exports\PlannedTaskCompletedExport;
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
        $order_tasks = $req->session()->get('plannedtask.xlsExport.order_tasks');
        $filter_on_tasks = $req->session()->get('plannedtask.xlsExport.filter_on_tasks');
        $req->session()->forget('plannedtask.xlsExport.task_ids');
        $req->session()->forget('plannedtask.xlsExport.import_type_id');
        $req->session()->forget('plannedtask.xlsExport.order_tasks');
        $req->session()->forget('plannedtask.xlsExport.filter_on_tasks');
        $planType = PlanType::find($req->session()->get('plannedtask.plantype.id'));
        $filename = $planType->name . '_Export_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new PlannedTaskExport($tasks_ids, $import_type_id, $order_tasks, $filter_on_tasks), $filename);
    }

    public function exportAllXls(Request $req){
        // dd();
        $filters = $req->session()->get('plannedtask.xlsAllExport.filters');
        $planConf = $req->session()->get('plannedtask.xlsAllExport.planConf');
        $req->session()->forget('plannedtask.xlsAllExport.filters');
        $req->session()->forget('plannedtask.xlsAllExport.planConf');
        $filename = 'Export_ALL_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new PlannedTaskAllExport($planConf, $filters), $filename);
    }

    public function exportXls_completed(Request $req){
        // dd();
        $tasks_ids = $req->session()->get('plannedtask.xlsExport.task_ids');
        $import_type_id = $req->session()->get('plannedtask.xlsExport.import_type_id');
        $order_tasks = $req->session()->get('plannedtask.xlsExport.order_tasks');
        $filter_on_tasks = $req->session()->get('plannedtask.xlsExport.filter_on_tasks');
        $req->session()->forget('plannedtask.xlsExport.task_ids');
        $req->session()->forget('plannedtask.xlsExport.import_type_id');
        $req->session()->forget('plannedtask.xlsExport.order_tasks');
        $req->session()->forget('plannedtask.xlsExport.filter_on_tasks');
        $planType = PlanType::find($req->session()->get('plannedtask.plantype.id'));
        $filename = $planType->name . '_Export_Completati_' . Carbon::now()->format('YmdHis') . '.xlsx';
        return Excel::download(new PlannedTaskCompletedExport($tasks_ids, $import_type_id, $order_tasks, $filter_on_tasks), $filename);
    }
}

