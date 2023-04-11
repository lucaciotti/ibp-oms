<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanImportFileController extends Controller
{
    public function index(Request $req){
        return view('ibp.planimportfile');
    }
}
