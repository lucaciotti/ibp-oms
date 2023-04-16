<?php

namespace App\Http\Livewire\PlanType;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanType;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Str;

class PlanTypeTable extends DataTableComponent
{

    protected $model = PlanType::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setDebugEnabled()
            ->setPerPage(25)
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if ($column->getTitle() == '') {
                    return [
                        'default' => false,
                        // 'class' => 'w-5',
                        'style' => 'width:30%;'
                    ];
                }
                if ($column->getTitle() == 'Nome') {
                    return [
                        'class' => 'text-bold',
                    ];
                }
                if ($column->getTitle()== "Dt.Modifica") {
                    return [
                        'class' => 'text-bold btn',
                        'onclick' => "Livewire.emit('slide-over.open', 'audits.audits-slide-over', {'ormClass': '". class_basename(get_class($row)) ."', 'ormId': " . $row->id . "});",
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
            Column::make("Nome", "name")
                ->sortable()
                ->searchable(),
            Column::make("Descrizione", "description")
                ->sortable()
                ->searchable(),
            Column::make("Dt.Modifica", "updated_at")
                ->format(
                    fn ($value, $row, Column $column) => '<span class="fa fa-history pr-1"></span>' . $value->format('d-m-Y')
                )->html()
                ->sortable(),
            ButtonGroupColumn::make('')
                ->buttons([
                    LinkColumn::make('Modifica')
                        ->title(fn ($row) => '<span class="fa fa-edit pr-1"></span>Modifica')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-default btn-xs mr-2 ',
                                'onclick' => "Livewire.emit('modal.open', 'plan-type.plan-type-modal-edit', {'id': " . $row->id . "});"
                            ];
                        }),
                    LinkColumn::make('Attributi')
                        ->title(fn ($row) => '<span class="fa fa-tools pr-1"></span>Attributi')
                        ->location(fn ($row) => 'plantypes/'.$row->id.'/attributes')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-primary btn-xs mr-2 text-bold',
                                'style' => 'opacity: 75%'
                            ];
                        }),
                    LinkColumn::make('Conf. Xls')
                        ->title(fn ($row) => '<span class="fa fa-file-excel pr-1"></span>Conf. Xls')
                        ->location(fn ($row) => 'plantypes/'.$row->id. '/planimporttypes')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-success btn-xs mr-1 text-bold',
                                'style' => 'opacity: 85%'
                            ];
                        }),
                ]),

            // Column::make("Updated at", "updated_at")
            //     ->sortable(),
        ];
    }
}
