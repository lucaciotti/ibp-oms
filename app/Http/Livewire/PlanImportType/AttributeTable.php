<?php

namespace App\Http\Livewire\PlanImportType;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Attribute;
use App\Models\PlanImportTypeAttribute;
use App\Models\PlanTypeAttribute;
use Illuminate\Database\Eloquent\Builder;
use Laratrust;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class AttributeTable extends DataTableComponent
{
    protected $model = Attribute::class;

    public $type_id;
    public $import_type_id;

    // public function mount($type_id){
    //     dd($type_id);
    //     $this->type_id = $type_id;
    // }

    public function builder(): Builder
    {
        return PlanTypeAttribute::query()->where('type_id', $this->type_id)
                ->whereHas( 'attribute', fn ($query) => $query->whereDoesntHave('planImportTypeAttribute', fn ($query) => $query->where('import_type_id', $this->import_type_id)))
                ->with(['attribute'])->orderBy('order');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['attributes.id as attribute_id'])
            ->setPerPage(25)
            ->setPerPageAccepted([25, 50, 75, 100]);
    }

    public function columns(): array
    {
        $columns = [
            Column::make("#", "order")
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
                ->searchable()
                ->sortable(),
            BooleanColumn::make('Obbligatorio', 'attribute.required'),
        ];
        if (Laratrust::isAbleTo('config-update')) {
            array_push(
                $columns,
                Column::make('')
                ->label(
                    function ($row) {
                        $data = '<button class="btn btn-success btn-xs text-bold" wire:click="linkAttrToPlanImportTypeAttribute(' . $row->attribute_id . ')"><span class="fa fa-plus mr-1"></span>Associa</button>&nbsp;';
                        return $data;
                    }
                )
                ->html(),
            );
        }
        return $columns;
    }

    public function linkAttrToPlanImportTypeAttribute($attr_id){
        $cell_num = PlanImportTypeAttribute::where('import_type_id', $this->import_type_id)->max('cell_num');
        PlanImportTypeAttribute::create(['import_type_id' => $this->import_type_id, 'attribute_id' => $attr_id, 'cell_num' => ++$cell_num]);
        $this->emit('refreshDatatable');
    }
}
