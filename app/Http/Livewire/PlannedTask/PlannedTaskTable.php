<?php

namespace App\Http\Livewire\PlannedTask;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Session;

class PlannedTaskTable extends DataTableComponent
{
    protected $model = PlannedTask::class;

    public $type_id;

    public function mount() {
        $this->setFilter('date_from', date('Y-m-d', strtotime('-' . date('w') . ' days')));
    }

    public function builder(): Builder
    {
        $this->type_id = Session::get('plannedtask.plantype.id');
        return PlannedTask::query()
            ->where('type_id', $this->type_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['planned_tasks.id as id'])
            ->setDefaultSort('ibp_data_consegna', 'asc')
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle()== "Matricola") {
                    return [
                        'class' => 'text-bold btn',
                        'onclick' => "Livewire.emit('modal.open', 'planned-task.planned-task-modal-edit', {'id': " . $row->id . "});",
                    ];
                }
                if ($column->getTitle()== "Dt.Modifica") {
                    return [
                        'class' => 'text-bold btn',
                        'onclick' => "Livewire.emit('slide-over.open', 'audits.audits-slide-over', {'ormClass': '". class_basename(get_class($row)) ."', 'ormId': " . $row->id . "});",
                    ];
                }
                return [];
            })
            ->setSecondaryHeaderEnabled()
            ->setOfflineIndicatorEnabled()
            ->setFilterLayoutSlideDown()
            ->setHideBulkActionsWhenEmptyEnabled()
            ;
    }

    public function columns(): array
    {
        $planAttrs = PlanTypeAttribute::where('type_id', $this->type_id)->with(['attribute'])->get();
        $columns = [];
        foreach ($planAttrs as $planAttr) {
            if ($planAttr->attribute->hidden_in_view){
                if ($planAttr->attribute->col_type == 'date'){
                    array_push($columns, 
                        Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                            ->searchable()
                            ->format(
                                fn ($value, $row, Column $column) => $value->format('d-m-Y')
                            )
                            ->deselected()
                            ->sortable()
                    );
                } else {
                    array_push($columns, 
                        Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                            ->searchable()
                            ->deselected()
                            ->sortable()
                    );
                }
            } else {
                if ($planAttr->attribute->col_type == 'date') {
                    array_push(
                        $columns,
                        Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                            ->searchable()
                            ->format(
                                fn ($value, $row, Column $column) => $value->format('d-m-Y')
                            )
                            ->deselected()
                            ->sortable()
                    );
                } else {
                    array_push($columns, 
                        Column::make($planAttr->attribute->label, $planAttr->attribute->col_name)
                            ->searchable()
                            ->sortable()
                    );
                }
            }
        }
        array_push(
            $columns,
            BooleanColumn::make('Completato', 'completed')
            ->excludeFromColumnSelect(),
            Column::make("Dt.Modifica", "updated_at")
                ->format(
                    fn ($value, $row, Column $column) => '<span class="fa fa-history pr-1"></span>'.$value->format('d-m-Y')
                )->html()
                ->sortable()
                ->excludeFromColumnSelect(),
        );
        // dd($columns);
        return $columns;
    }

    public function filters(): array
    {
        return [
            DateFilter::make('Inizio Data Consegna', 'date_from')
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('ibp_data_consegna', '>=', $value);
                }),

            DateFilter::make('Fine Data Consegna', 'date_to')
                ->config([
                    'placeholder' => 'dd-mm-yyyy',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('ibp_data_consegna', '<=', $value);
                }),

            TextFilter::make('Matricola', 'ibp_plan_matricola')
                ->config([
                    'placeholder' => 'Cerca Matricola',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('ibp_plan_matricola', 'like', '%' . $value . '%');
                }),

            TextFilter::make('Cliente', 'ibp_cliente_ragsoc')
            ->config([
                'placeholder' => 'Cerca Cliente',
            ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('ibp_cliente_ragsoc', 'like', '%' . $value . '%');
                }),


        ];
    }

    public function bulkActions(): array
    {
        return [
            'activate' => 'Activate',
            'deactivate' => 'Deactivate',
            'export' => 'Export',
        ];
    }
}
