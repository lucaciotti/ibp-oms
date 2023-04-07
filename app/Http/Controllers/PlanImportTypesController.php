<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanImportTypesController extends Controller
{
    public function index(Request $req, $id=null)
    {
        if (!empty($id)) {
            $req->session()->put('config.planimporttype.id', $id);
        } else {
            $req->session()->forget('config.planimporttype.id');
        }


        return view('ibp.config.planimporttypes');
    }
}
