<?php

namespace App\Http\Livewire\PlanImportFile;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanImportFile;
use App\Models\PlanType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class PlanImportFileTable extends DataTableComponent
{
    protected $model = PlanImportFile::class;

    public function builder(): Builder
    {
        return PlanImportFile::query()
            ->with(['plantype', 'planimporttype'])
            ->orderBy('id', 'desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setDebugEnabled()
            ->setAdditionalSelects(['plan_import_files.import_type_id as import_type_id'])
            ->setEagerLoadAllRelationsEnabled()
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setPerPage(25)
            ->setTableRowUrl(function ($row) {
                return route('plan_xls_rows', $row->id);
            })
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                
                if ($column->getTitle() == '') {
                    return [
                        'style' => 'width:22%;'
                    ];
                }
                if ($column->getTitle() == "Dt.Modifica") {
                    return [
                        'class' => 'text-bold btn',
                        'onclick' => "Livewire.emit('slide-over.open', 'audits.audits-slide-over', {'ormClass': '" . class_basename(get_class($row)) . "', 'ormId': " . $row->id . "});",
                    ];
                }
                return [];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("#", "id")
                ->sortable(),
            Column::make("Piano")
                ->label(
                    fn ($row, Column $column) => $this->getPlanTypeName($row, $column)
                )
                ->searchable()
                ->sortable(),
            Column::make("File Name", "filename")
                ->format(
                    fn ($value, $row, Column $column) => '<strong>' . $value . '</strong>'
                )->html()
                ->searchable()
                ->sortable(),
            Column::make("Tipo Import", "planimporttype.name")
                ->searchable()
                ->sortable(),
            Column::make("Stato", "status")
                ->searchable()
                ->sortable()
                ->unclickable(),
            BooleanColumn::make('Forza Import', 'force_import'),
            Column::make("Caricato da")
                ->label(
                    fn ($row, Column $column) => $this->getAuditCreatedUser($row, $column)
                ),
            Column::make("Dt.Modifica", "updated_at")
                ->format(
                    fn ($value, $row, Column $column) => '<span class="fa fa-history pr-1"></span>'.$value->format('d-m-Y')
                )->html()
                ->unclickable()
                ->sortable(),
            ButtonGroupColumn::make('')
                ->unclickable()
                ->buttons([
                    LinkColumn::make('Modifica')
                        ->title(fn ($row) => '<span class="fa fa-edit pr-1"></span>Modifica')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-default btn-xs mr-2 ',
                                'onclick' => "Livewire.emit('modal.open', 'plan-import-file.plan-import-file-modal-edit', {'plan_file_id': " . $row->id . "});"
                            ];
                        }),
                    LinkColumn::make('Download')
                        ->title(fn ($row) => '<span class="fa fa-download pr-1"></span>Download')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-primary btn-xs mr-2 ',
                                'style' => 'opacity: 85%',
                                'wire:click' => "exportFile(" . $row->id . ");"
                            ];
                        }),
                ]),
        ];
    }

    public function exportFile($id){
        $file = PlanImportFile::find($id);
        return response()->download(storage_path('app/'. $file->path), $file->filename);
    }

    private function getAuditCreatedUser($row, $column)
    {
        return $row->audits()->first()->user->name;
    }
    private function getPlanTypeName($row, $column)
    {
        // dd($row);
        // $plantype = PlanType::whereHas('planimporttype', fn ($query) => $query->where('id', $row->import_type_id))->first();
        return $row->plantype->name;
    }
}
