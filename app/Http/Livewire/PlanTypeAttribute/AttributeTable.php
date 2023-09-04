<?php

namespace App\Http\Livewire\PlanTypeAttribute;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Attribute;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Laratrust;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class AttributeTable extends DataTableComponent
{
    protected $model = Attribute::class;

    public $type_id;

    // public function mount($type_id){
    //     dd($type_id);
    //     $this->type_id = $type_id;
    // }

    public function builder(): Builder
    {
        return Attribute::query()
                ->whereDoesntHave('planTypeAttribute', fn ($query) => $query->where('type_id', $this->type_id));
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPerPage(25)
            ->setPerPageAccepted([25, 50, 75, 100]);
    }

    public function columns(): array
    {
        $columns = [
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
            BooleanColumn::make('Obbligatorio', 'required'),
        ];
        if (Laratrust::isAbleTo('config-update')) {
            array_push(
                $columns,
                Column::make('')
                    ->label(
                        function ($row) {
                            $data = '<button class="btn btn-success btn-xs text-bold" wire:click="linkAttrToPlanType(' . $row->id . ')"><span class="fa fa-plus mr-1"></span>Associa</button>&nbsp;';
                            return $data;
                        }
                    )
                    ->html(),
            );
        }
        return $columns;
    }

    public function linkAttrToPlanType($attr_id){
        $order = PlanTypeAttribute::where('type_id', $this->type_id)->max('order');
        PlanTypeAttribute::create(['type_id' => $this->type_id, 'attribute_id' => $attr_id, 'order' => ++$order]);
        $this->emit('refreshDatatable');
    }
}
