<?php

namespace App\Http\Controllers;

use App\Models\PlanType;
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
                $req->session()->put('plannedtask.plantype.id', $planType->id);
            }
        }
        $planType = PlanType::find($req->session()->get('plannedtask.plantype.id'));
        return view('ibp.plannedtask', ['planType' => $planType]);
    }
}
