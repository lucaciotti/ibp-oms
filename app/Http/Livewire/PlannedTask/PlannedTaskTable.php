<?php

namespace App\Http\Livewire\PlannedTask;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Session;

class PlannedTaskTable extends DataTableComponent
{
    protected $model = PlannedTask::class;

    public $type_id;

    public function builder(): Builder
    {
        $this->type_id = Session::get('plannedtask.plantype.id');
        return PlannedTask::query()
            ->where('type_id', $this->type_id)
            ->orderBy('ibp_data_consegna', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        $planAttrs = PlanTypeAttribute::where('type_id', $this->type_id)->with(['attribute'])->get();
        $columns = [];
        foreach ($planAttrs as $planAttr) {
            // dd($planAttr);
            array_push($columns, 
                Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                    ->searchable()
                    ->sortable()
            );
        }
        // dd($columns);
        return $columns;
    }
}
