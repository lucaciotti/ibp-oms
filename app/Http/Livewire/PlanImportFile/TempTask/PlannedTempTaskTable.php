<?php

namespace App\Http\Livewire\PlanImportFile\TempTask;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanFilesTempTask;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Session;

class PlannedTempTaskTable extends DataTableComponent
{
    protected $model = PlanFilesTempTask::class;

    public $file_id;
    public $type_id;

    public function builder(): Builder
    {
        return PlanFilesTempTask::query()
            ->where('import_file_id', $this->file_id);
    }

    public function mount()
    {
        $this->setFilter('warning', 'yes');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDefaultSort('num_row', 'asc')
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setPerPage(25)
            ->setAdditionalSelects(['id', 'error', 'task_id'])
            ->setTrAttributes(function ($row, $index) {
                if (!empty($row->error)) {
                    return [
                        'style' => 'background-color: rgba(204,51,0, 0.5) !important',
                    ];
                }
                if ($row->warning) {
                    return [
                        'style' => 'background-color: rgba(255,153,102, 0.5) !important;',
                    ];
                }

                return [];
            })
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle() == "Error") {
                    return [
                        'title' => $row->error,
                    ];
                }
                if ($column->getTitle()== "Matricola") {
                    if($row->task_id!=null){
                        return [
                            'class' => 'text-bold btn',
                            'onclick' => "Livewire.emit('modal.open', 'planned-task.planned-task-modal-edit', {'id': " . $row->task_id . ", 'readOnly': 1});",
                        ];
                    } else {
                        return [];
                    }
                }
                return [];
            });
    }

    public function columns(): array
    {
        $planAttrs = PlanTypeAttribute::where('type_id', $this->type_id)->with(['attribute'])->orderBy('order')->get();
        $columns = [];
        array_push(
            $columns,
            Column::make('#Riga', 'num_row')
                ->excludeFromColumnSelect()
                ->sortable(),
            BooleanColumn::make('Importati', 'imported')
                ->excludeFromColumnSelect(),
            BooleanColumn::make('Error', 'warning')
                ->excludeFromColumnSelect(),
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

    public function filters(): array
    {
        return [
            SelectFilter::make('Error', 'warning')
            ->options([
                '' => 'Tutti',
                'yes' => 'Si',
                'no' => 'No',
            ])
                ->filter(function (Builder $builder, string $value) {
                    $valueFilter = ($value == 'yes') ? true : (($value == 'no') ? false : null);
                    $builder->where('warning', $valueFilter);
                }),

            SelectFilter::make('Importati', 'imported')
            ->options([
                '' => 'Tutti',
                'yes' => 'Si',
                'no' => 'No',
            ])
                ->filter(function (Builder $builder, string $value) {
                    $valueFilter = ($value == 'yes') ? true : (($value == 'no') ? false : null);
                    $builder->where('imported', $valueFilter);
                }),

        ];
    }

    public function bulkActions(): array
    {
        return [
            'xlsError' => 'Export Xls (con Errori)',
        ];
    }

    public function xlsError()
    {   
        if(count($this->getSelected())>0){
            Session::put('plannedtemptask.xlsExport.task_ids',$this->getSelected());
            return redirect()->route('exportxls_temptasks');
        }
        // dd($this->getSelected());
    }
}
