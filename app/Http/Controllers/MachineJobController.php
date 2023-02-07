<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MachineJobController extends Controller
{
    public function index()
    {
        return view('tasks');
    }
}
