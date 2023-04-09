<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(Request $req)
    {
        return view('ibp.config.attribute');
    }
}
