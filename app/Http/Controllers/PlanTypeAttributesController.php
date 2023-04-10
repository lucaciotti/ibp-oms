<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\PlanType;
use App\Models\PlanTypeAttribute;
use Illuminate\Http\Request;

class PlanTypeAttributesController extends Controller
{
    public function index(Request $req, $id = null)
    {
        // if (!empty($id)) {
        //     $req->session()->put('config.plantypesattribute.id', $id);
        // } else {
        //     $req->session()->forget('config.plantypesattribute.id');
        // }

        $planType = PlanType::find($id);

        // SE PlanTypeAttribute è vuoto creo gli attributi required
        $this->initPlanTypeAttriute($id);

        return view('ibp.config.plantypesattribute',['planType'=>$planType]);
    }

    private function initPlanTypeAttriute($type_id){
        $planTypeAttribute = PlanTypeAttribute::where('type_id', $type_id)->get();
        if ($planTypeAttribute->count()==0){
            $requAttr = Attribute::where('required', 1)->get();
            // dd($requAttr);
            $order = 0;
            foreach ($requAttr as $attr) {
                PlanTypeAttribute::create(['type_id'=>$type_id, 'attribute_id'=>$attr->id, 'order'=>++$order]);
            }
        }

    }
}
