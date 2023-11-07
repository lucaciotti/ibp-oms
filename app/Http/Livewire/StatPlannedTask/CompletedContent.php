<?php

namespace App\Http\Livewire\StatPlannedTask;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\PlanType;
use DateTime;
use Session;

class CompletedContent extends DynamicContent
{
    public $plantypes;
    public $plantype_id;
    public $month;
    public $completed;
    public $datetype;

    public $months = [
                    'january' => 'Gennaio',
                    'february' => 'Febbraio',
                    'march' => 'Marzo',
                    'april' => 'Aprile',
                    'may' => 'Maggio',
                    'june' => 'Giugno',
                    'july' => 'Luglio',
                    'august' => 'Agosto',
                    'september' => 'Settembre',
                    'october' => 'Ottobre',
                    'november' => 'Novembre',
                    'december' => 'Dicembre',
    ];

    public $completed_opt = [
        '' => 'Tutti',
        'no' => 'No',
        'si' => 'Si',
    ];

    public $datetypes = [
        'completed_date' => 'Data Completamento',
        'ibp_data_inizio_prod' => 'Data Inizio Produzione',
        'ibp_data_consegna' => 'Data Consegna',
    ];

    public $refresh_table;

    public $listeners = [
        'dynamic-content.collapse' => 'collapse',
        'dynamic-content.expande' => 'expande',
        'refreshNewPlannedTask' => 'tableHasToBeRefreshed',
        'refreshDatatable' => 'tableRefreshed',
    ];

    public function mount(){
        if (!Session::has('statplannedtask.plantype.id')) {
            $planType = PlanType::first();
            Session::put('statplannedtask.plantype.id', $planType->id);
        }
        $this->plantype_id = Session::get('statplannedtask.plantype.id');

        if (!Session::has('statplannedtask.filter.month')) {
            $this->month = (new DateTime())->format('F');
            Session::put('statplannedtask.filter.month', $this->month);
        }
        $this->month = Session::get('statplannedtask.filter.month');

        if (!Session::has('statplannedtask.filter.completed')) {
            $this->completed = 'si';
            Session::put('statplannedtask.filter.completed', $this->month);
        }
        $this->completed = Session::get('statplannedtask.filter.completed');

        if (!Session::has('statplannedtask.filter.datetype')) {
            $this->datetype = 'completed_date';
            Session::put('statplannedtask.filter.datetype', $this->datetype);
        }
        $this->datetype = Session::get('statplannedtask.filter.datetype');
    }

    public function render()
    {
        $this->plantypes = PlanType::all();
        return view('livewire.stat-planned-task.completed-content');
    }

    public function updatedPlantypeId(){
        Session::put('statplannedtask.plantype.id', $this->plantype_id);
        return redirect()->route('stat_plntask');
        // $this->emit('refreshDatatable');
        // $this->emit('clearSelected');
    }

    public function updatedMonth()
    {
        Session::put('statplannedtask.filter.month', $this->month);
        return redirect()->route('stat_plntask');
        // $this->emit('refreshDatatable');
        // $this->emit('clearSelected');
    }

    public function updatedCompleted()
    {
        Session::put('statplannedtask.filter.completed', $this->completed);
        return redirect()->route('stat_plntask');
        // $this->emit('refreshDatatable');
        // $this->emit('clearSelected');
    }

    public function updatedDateType()
    {
        Session::put('statplannedtask.filter.datetype', $this->datetype);
        return redirect()->route('stat_plntask');
        // $this->emit('refreshDatatable');
        // $this->emit('clearSelected');
    }

    public function tableHasToBeRefreshed(){
        $this->refresh_table = true;
    }
    
    public function tableRefreshed(){
        $this->refresh_table = false;
    }
}
