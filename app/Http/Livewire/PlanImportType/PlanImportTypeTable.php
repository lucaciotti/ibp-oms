<?php

namespace App\Http\Livewire\PlanImportType;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanImportType;
use Illuminate\Database\Eloquent\Builder;
use Laratrust;
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
                if ($column->getTitle() == '') {
                    return [
                        'default' => false,
                        'style' => 'width:20%;'
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
            // ->setDebugEnabled();
    }

    public function columns(): array
    {
        $actionColumns = [];
        if (Laratrust::isAbleTo('config-update')) {
            array_push(
                $actionColumns,
                LinkColumn::make('Modifica')
                    ->title(fn ($row) => '<span class="fa fa-edit pr-1"></span>Modifica')
                    ->location(fn ($row) => '#')
                    ->attributes(function ($row) {
                        return [
                            'class' => 'btn btn-default btn-xs mr-2',
                            'onclick' => "Livewire.emit('modal.open', 'plan-import-type.plan-import-type-modal-edit', {'type_id': " . $this->type_id . ",'import_type_id': " . $row->id . "});"
                        ];
                    }),
            );
        }
        array_push(
            $actionColumns,
            LinkColumn::make('Conf.Colonne')
            ->title(fn ($row) => '<span class="fa fa-table pr-1"></span>Colonne')
            ->location(fn ($row) => '#')
                ->attributes(function ($row) {
                    return [
                        'class' => 'btn btn-warning btn-xs mr-2',
                        'style' => 'opacity: 85%',
                        'onclick' => "Livewire.emit('modal.open', 'plan-import-type.plan-import-type-attribute-modal', {'type_id': " . $this->type_id . ",'import_type_id': " . $row->id . "});",
                    ];
                }),
        );
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
            Column::make("Dt.Modifica", "updated_at")
                ->format(
                    fn ($value, $row, Column $column) => '<span class="fa fa-history pr-1"></span>' . $value->format('d-m-Y')
                )->html()
                ->sortable(),
            // Column::make("Creato da")
            //     ->label(
            //         fn ($row, Column $column) => $this->getAuditCreatedUser($row, $column)
            //     ),
            BooleanColumn::make('Import', 'use_in_import'),
            BooleanColumn::make('Export', 'use_in_export'),
            BooleanColumn::make('Default Imp.', 'default_import'),
            BooleanColumn::make('Default Exp.', 'default_export'),
            ButtonGroupColumn::make('')
                ->buttons($actionColumns),
        ];
    }

    public function getAuditCreatedUser($row, $column)
    {
        return $row->audits()->first()->user->name;
    }
}
