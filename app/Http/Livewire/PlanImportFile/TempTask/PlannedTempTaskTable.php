<?php

namespace App\Http\Livewire\PlanImportFile\TempTask;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanFilesTempTask;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;

class PlannedTempTaskTable extends DataTableComponent
{
    protected $model = PlanFilesTempTask::class;

    public $file_id;
    public $type_id;

    public function builder(): Builder
    {
        return PlanFilesTempTask::query()
            ->where('import_file_id', $this->file_id)
            ->orderBy('num_row', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        $planAttrs = PlanTypeAttribute::where('type_id', $this->type_id)->with(['attribute'])->get();
        $columns = [];
        array_push(
            $columns,
            Column::make('#Riga', 'num_row')
                ->sortable()
        );
        foreach ($planAttrs as $planAttr) {
            if ($planAttr->attribute->hidden_in_view) {
                array_push(
                    $columns,
                    Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                        ->searchable()
                        ->deselected()
                        ->sortable()
                );
            } else {
                array_push(
                    $columns,
                    Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                        ->searchable()
                        ->sortable()
                );
            }
        }
        // dd($columns);
        return $columns;
    }
}
