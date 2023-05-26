<?php

namespace App\Http\Livewire\PlannedTask;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Session;

class PlannedTaskTable extends DataTableComponent
{
    protected $model = PlannedTask::class;

    public $type_id;

    public function mount() {
        $this->setFilter('date_prod_from', date('Y-m-d', strtotime('-' . date('w') . ' days')));
        $this->setFilter('completed', 'no');
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
            // ->setDebugEnabled()
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setPerPage(25)
            ->setAdditionalSelects(['planned_tasks.id as id'])
            ->setDefaultSort('ibp_data_consegna', 'asc')
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle()== "Matricola") {
                    if(!$row->completed){
                        return [
                            'class' => 'text-bold btn',
                            'onclick' => "Livewire.emit('modal.open', 'planned-task.planned-task-modal-edit', {'id': " . $row->id . "});",
                        ];
                    } else {
                        return [
                            'class' => 'text-bold btn',
                            'onclick' => "Livewire.emit('modal.open', 'planned-task.planned-task-modal-edit', {'id': " . $row->id . ", 'readOnly': 1});",
                        ];
                    }
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
        $planAttrs = PlanTypeAttribute::where('type_id', $this->type_id)->with(['attribute'])->orderBy('order')->get();
        $columns = [];
        array_push(
            $columns,
            BooleanColumn::make('', 'completed')
            ->excludeFromColumnSelect(),
        );
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
            DateFilter::make('Inizio Data Prod.', 'date_prod_from')
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('ibp_dt_inizio_prod', '>=', $value);
                }),

            DateFilter::make('Fine Data Prod.', 'date_prod_to')
            ->config([
                'placeholder' => 'dd-mm-yyyy',
            ])
                ->filter(function (Builder $builder, string $value) {
                    $builder->where('ibp_dt_inizio_prod', '<=', $value);
                }),

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

            SelectFilter::make('Completato', 'completed')
                ->options([
                    '' => 'Tutti',
                    'yes' => 'Si',
                    'no' => 'No',
                ])
                ->filter(function (Builder $builder, string $value) {
                    $valueFilter = ($value=='yes') ? true : (($value=='no') ? false : null);
                    $builder->where('completed', $valueFilter);
                }),


        ];
    }

    public function bulkActions(): array
    {
        return [
            'doReport' => 'Stampa Report',
            'xlsExport' => 'Export Xls',
            'completed' => 'Completati',
            'notcompleted' => 'Non Completati',
        ];
    }

    public function doReport()  {
        $this->emit('modal.open', 'pdf-reports.list-of-reports', ['tasks_ids' => $this->getSelected(), 'type_id' => $this->type_id]);
        // dd($this->getSelected());
    }

    public function xlsExport()
    {
        $this->emit('modal.open', 'xls-export.xls-export-modal', ['tasks_ids' => $this->getSelected(), 'type_id' => $this->type_id]);
        // dd($this->getSelected());
    }

    public function completed()
    {
        foreach ($this->getSelected() as $id) {
            $tasks = PlannedTask::find($id)->update(['completed' => 1]);
        }
    }

    public function notcompleted()
    {
        foreach ($this->getSelected() as $id) {
            $tasks = PlannedTask::find($id)->update(['completed' => 0]);
        }
    }
}
