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
            ->setPerPageAccepted([25, 50, 75, 100]);
        // ->setAdditionalSelects(['plan_types.id as id'])
        // ->setReorderEnabled()
        // ->setHideReorderColumnUnlessReorderingEnabled()
        // ->setSecondaryHeaderTrAttributes(function ($rows) {
        //     return ['class' => 'bg-gray'];
        // })
        // ->setTrAttributes(function ($row, $index) {
        //     return [
        //         'class' => 'hover:bg-gray',
        //     ];
        // });
        // ->setThAttributes(function (Column $column) {
        //     if ($column->isLabel() && $column->getTitle() === 'Nome') {
        //         return [
        //             'class' => 'bg-yellow-100 font-bold',
        //             'style' => 'background-color: #00CC99',
        //         ];
        //     } elseif ($column->isLabel() && $column->getTitle() === 'In Progress') {
        //         return [
        //             'class' => 'bg-purple-100 font-bold',
        //         ];
        //     } elseif ($column->isLabel() && $column->getTitle() === 'Live') {
        //         return [
        //             'class' => 'bg-green-100 font-bold',
        //         ];
        //     } elseif ($column->isLabel() && $column->getTitle() === 'Canceled') {
        //         return [
        //             'class' => 'bg-red-100 font-bold',
        //         ];
        //     }
        //     return [];
        // })
        // ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
        //     switch ($columnIndex) {
        //         case 2:
        //             return [
        //                 'style' => 'background-color: #00CC99',
        //             ];
        //             break;
        //         case 3:
        //             return [
        //                 'class' => 'bg-purple-50',
        //             ];
        //             break;
        //         case 4:
        //             return [
        //                 'class' => 'bg-yellow-50',
        //             ];
        //             break;
        //         case 5:
        //             return [
        //                 'class' => 'bg-red-50',
        //             ];
        //             break;

        //         default:
        //             # code...
        //             break;
        //     }
        //     return [];
        // });
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nome", "name")
                ->sortable()
                ->searchable(),
            Column::make("Descrizione", "description")
                ->sortable()
                ->searchable(),
            Column::make("Data creazione", "created_at")
                ->format(
                    fn ($value, $row, Column $column) => $value->format('d-m-Y')
                )
                ->sortable(),
            // Column::make('')
            //     ->label(
            //         function ($row) {
            //             $data = '<button class="btn btn-default btn-sm"><span class="fa fa-cogs"></span></button>&nbsp;'; 
            //             $data .= '<button class="btn btn-default btn-sm"><span class="fa fa-cogs"></span></button>&nbsp;'; 
            //             $data .= '<button class="btn btn-default btn-sm"><span class="fa fa-cogs"></span></button>&nbsp;'; 
            //             return $data;
            //         }
            //     )
            //     ->html(),
            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                    LinkColumn::make('Modifica')
                        ->title(fn ($row) => 'Modifica')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-warning btn-xs',
                                'onclick' => "Livewire.emit('modal.open', 'plan-type.plan-type-modal-edit', {'id': " . $row->id . "});"
                            ];
                        }),
                ]),

            // Column::make("Updated at", "updated_at")
            //     ->sortable(),
        ];
    }
}
