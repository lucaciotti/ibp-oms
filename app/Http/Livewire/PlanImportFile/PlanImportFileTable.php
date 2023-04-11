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
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle() == '') {
                    return [
                        'default' => false,
                        // 'class' => 'w-5',
                        'style' => 'width:20%;'
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
                ->sortable(),
            BooleanColumn::make('Forza Import', 'force_import'),
            Column::make("Caricato da")
                ->label(
                    fn ($row, Column $column) => $this->getAuditCreatedUser($row, $column)
                ),
            Column::make("Data Caricamento", "created_at")
                ->format(
                    fn ($value, $row, Column $column) => $value->format('d-m-Y')
                )
                ->sortable(),
            ButtonGroupColumn::make('')
                ->buttons([
                    LinkColumn::make('Modifica')
                        ->title(fn ($row) => 'Modifica')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-default btn-xs mr-2 text-bold',
                                'onclick' => "Livewire.emit('modal.open', 'plan-import-file.plan-import-file-modal-edit', {'plan_file_id': " . $row->id . "});"
                            ];
                        }),
                    LinkColumn::make('Cancella')
                        ->title(fn ($row) => 'Cancella')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            if(in_array($row->status, ['Modificato'])) {
                                return [
                                    'class' => 'btn btn-danger btn-xs mr-2 text-bold',
                                    // 'style' => 'display: none;'
                                    'style' => 'pointer-events: none; cursor: not-allowed; opacity: 0.65;'
                                ];
                            } else {
                                return [
                                    'class' => 'btn btn-danger btn-xs mr-2 text-bold',
                            'onclick' => "Livewire.emit('modal.open', 'plan-import-file.plan-import-file-modal-edit', {'plan_file_id': " . $row->id . "});"
                                ];
                            }
                        }),
                ]),
        ];
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
