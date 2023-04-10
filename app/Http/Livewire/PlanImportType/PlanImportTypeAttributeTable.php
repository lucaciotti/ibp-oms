<?php

namespace App\Http\Livewire\PlanImportType;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanImportTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class PlanImportTypeAttributeTable extends DataTableComponent
{
    protected $model = PlanImportTypeAttribute::class;

    public $import_type_id;
    public $orderRows = [];

    public function builder(): Builder
    {
        return PlanImportTypeAttribute::query()
            ->where('import_type_id', $this->import_type_id)
            ->with(['attribute'])
            ->orderBy('cell_num', 'asc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setReorderEnabled()
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setAdditionalSelects(['plan_import_types_attribute.id as id'])
            // ->setHideReorderColumnUnlessReorderingEnabled()
            ->setDefaultReorderSort('cell_num', 'asc')
            ->setPerPage(25);
    }

    public function columns(): array
    {
        return [
            Column::make('# Cella', "cell_num")
                ->sortable(),
            Column::make("Nome Attributo", "attribute.label")
                ->searchable()
                ->format(
                    fn ($value, $row, Column $column) => '<strong>' . $value . '</strong>'
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
            BooleanColumn::make('Obbligatorio', 'attribute.required'),
            Column::make('')
                ->label(
                    function ($row) {
                        $data = '<button class="btn btn-danger btn-xs text-bold" wire:click="unLinkAttrToPlanImportTypeAttribute(' . $row->id . ')"><span class="fa fa-plus mr-1"></span>Elimina</button>&nbsp;';
                        return $data;
                    }
                )
                ->html(),
        ];
    }

    public function reorder($items): void
    {
        foreach ($items as $item) {
            PlanImportTypeAttribute::find((int)$item['value'])->update(['cell_num' => (int)$item['order']]);
        }
    }

    public function unLinkAttrToPlanImportTypeAttribute($id)
    {
        PlanImportTypeAttribute::find($id)->delete();
        $this->emit('refreshDatatable');
    }
}
