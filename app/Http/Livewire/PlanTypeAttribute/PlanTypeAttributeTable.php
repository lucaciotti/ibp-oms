<?php

namespace App\Http\Livewire\PlanTypeAttribute;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class PlanTypeAttributeTable extends DataTableComponent
{
    protected $model = PlanTypeAttribute::class;

    public $type_id;
    public $orderRows = [];

    public function builder(): Builder
    {
        return PlanTypeAttribute::query()
            ->where('type_id', $this->type_id)
            ->with(['attribute'])
            ->orderBy('order', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setReorderEnabled()
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setAdditionalSelects(['plan_type_attributes.id as id'])
            // ->setHideReorderColumnUnlessReorderingEnabled()
            ->setDefaultReorderSort('order', 'asc')
            ->setPerPage(25);
    }

    public function columns(): array
    {
        return [
            // Column::make("ID", "id")
            //     ->sortable(),
            Column::make('Posizione', "order")
                ->sortable(),
            Column::make("Nome Attributo", "attribute.label")
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => '<strong>'.$value.'</strong>'
                )->html()
                ->sortable(),
            Column::make("Tipo di dato", "attribute.col_type")
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
                ->sortable(),
            // Column::make("Posizione", "order")
            //     ->format(
            //     function ($value, $row, Column $column) {
            //             $this->orderRows[$row->id] = $value;
            //             $data = '<div class="input-group input-group-sm" style="width: 100px;">
            //                         <input id="iNum" name="iNum" class="form-control text-right" placeholder="number" type="number" min="1" max="10" wire:model="orderRows[]">
            //                         <div class="input-group-append"><button class="input-group-text btn btn-outline-success">
            //                             <i class="fas fa-check"></i>
            //                         </button></div>
            //                     </div>';
            //             return $data;
            //         }
            //     )->html()
            //     ->sortable(),            
            BooleanColumn::make('Obbligatorio', 'attribute.required'),
            Column::make('')
                ->label(
                    function ($row) {
                        if (!$row['attribute.required']) {
                            $data = '<button class="btn btn-danger btn-xs text-bold" wire:click="unLinkAttrToPlanType(' . $row->id . ')"><span class="fa fa-plus mr-1"></span>Elimina</button>&nbsp;';
                        } else {
                            $data = '';
                        }
                        return $data;
                    }
                )
                ->html(),
        ];
    }

    public function reorder($items): void
    {
        foreach ($items as $item) {
            PlanTypeAttribute::find((int)$item['value'])->update(['order' => (int)$item['order']]);
        }
    }

    public function unLinkAttrToPlanType($id)
    {
        PlanTypeAttribute::find($id)->delete();
        $this->emit('refreshDatatable');
    }
}
