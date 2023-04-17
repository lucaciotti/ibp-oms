<?php

namespace App\Http\Controllers;

use App\Models\PlannedTask;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stdplan = PlannedTask::where('type_id', 1)->where('ibp_data_consegna', '>', date('Y-m-d', strtotime('-' . date('w') . ' days')))->where('completed', false)->count();
        $robotplan = PlannedTask::where('type_id', 2)->where('ibp_data_consegna', '>', date('Y-m-d', strtotime('-' . date('w') . ' days')))->where('completed', false)->count();
        return view('home',['stdplan'=>$stdplan, 'robotplan' => $robotplan]);
    }
}
