<?php

namespace App\Http\Livewire\Attribute;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

class AttributeTable extends DataTableComponent
{
    // protected $model = Attribute::class;

    public function builder(): Builder
    {
        return Attribute::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPage(25)
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
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
        return [
            Column::make("#", "id")
                ->sortable(),
            Column::make("Nome Attributo", "label")
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => '<strong>'.$value.'</strong>'
                )->html()
                ->sortable(),
            Column::make("Tipo di dato", "col_type")
                ->format(
                    function ($value, $row, Column $column) {
                        switch ($value) {
                            case 'string':
                                return 'Testo';
                                break;
                            case 'integer':
                                return 'Valore Numerico';
                                break;
                            case 'float':
                                return 'Valore Decimale';
                                break;
                            case 'boolean':
                                return 'Vero/Falso';
                                break;
                            case 'text':
                                return 'Testo esteso';
                                break;
                            case 'date':
                                return 'Data';
                                break;
                            default:
                                return $value;
                                break;
                        }
                    }                        
                )
                ->searchable()
                ->sortable(),
            // Column::make("Creato da")
            //     ->label(
            //         fn ($row, Column $column) => $this->getAuditCreatedUser($row, $column)
            //     ),
            BooleanColumn::make('Obbligatorio', 'required'),
            BooleanColumn::make('Nascosto in Tabella', 'hidden_in_view'),
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
                                'class' => 'btn btn-default btn-xs',
                                'onclick' => "Livewire.emit('modal.open', 'attribute.attribute-modal-edit', {'id': " . $row->id . "});"
                            ];
                        }),
                ]),
        ];
    }

    public function getAuditCreatedUser($row, $column){
        return $row->audits()->first()->user->name;
    }
}
