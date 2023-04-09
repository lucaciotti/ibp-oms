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
            ->setPerPageAccepted([25, 50, 75, 100]);
            // ->setDebugEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("#", "id")
                ->sortable(),
            Column::make("Label", "label")
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
            Column::make("Data creazione", "created_at")
                ->format(
                    fn ($value, $row, Column $column) => $value->format('d-m-Y')
                )
                ->sortable(),
            Column::make("Creato da")
                ->label(
                    fn ($row, Column $column) => $this->getAuditCreatedUser($row, $column)
                ),
            BooleanColumn::make('Obbligatorio', 'required'),
            ButtonGroupColumn::make('Actions')
                ->buttons([
                    LinkColumn::make('Modifica')
                        ->title(fn ($row) => 'Modifica')
                        ->location(fn ($row) => '#')
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-warning btn-xs',
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
