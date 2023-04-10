<?php

namespace App\Http\Livewire\PlanImportType;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanImportType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Session;

class PlanImportTypeTable extends DataTableComponent
{
    protected $model = PlanImportType::class;
    
    public $type_id;

    public function builder(): Builder
    {
        return PlanImportType::query()
            ->with(['plantype'])
            ->where('type_id', $this->type_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPage(25)
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle() == 'Actions') {
                    return [
                        'default' => false,
                        // 'class' => 'w-5',
                        'style' => 'width:15%;'
                    ];
                }
                return [];
            });
            // ->setDebugEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nome Import", "name")
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => '<strong>' . $value . '</strong>'
                )->html()
                ->sortable(),
            Column::make("Descrizione", "description")
                ->searchable()
                ->sortable(),
            Column::make("Tipo Pianificazione", "plantype.name")
                ->searchable()
                ->sortable(),
            Column::make("Data creazione", "created_at")
                ->format(
                    fn ($value, $row, Column $column) => $value->format('d-m-Y')
                )
                ->sortable(),
            Column::make("Creato da")
                ->label(
                    fn ($row, Column $column) => $this->getAuditCreatedUser($row, $column)
                ),
            BooleanColumn::make('Predefinito', 'default'),
            ButtonGroupColumn::make('Actions')
                ->buttons([
                    LinkColumn::make('Modifica')
                        ->title(fn ($row) => 'Modifica')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-warning btn-xs mr-2 text-bold',
                                'onclick' => "Livewire.emit('modal.open', 'plan-import-type.plan-import-type-modal-edit', {'type_id': ".$this->type_id. ",'import_type_id': " . $row->id . "});"
                            ];
                        }),
                    LinkColumn::make('Conf.Colonne')
                        ->title(fn ($row) => 'Conf.Colonne')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-primary btn-xs mr-2 text-bold',
                                'onclick' => "Livewire.emit('modal.open', 'plan-import-type.plan-import-type-attribute-modal', {'type_id': " . $this->type_id . ",'import_type_id': " . $row->id . "});"
                            ];
                        }),
                ]),
        ];
    }

    public function getAuditCreatedUser($row, $column)
    {
        return $row->audits()->first()->user->name;
    }
}
