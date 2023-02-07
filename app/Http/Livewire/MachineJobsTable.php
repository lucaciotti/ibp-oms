<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\MachineJob;

use App\Exports\MachineJobExport;
use Maatwebsite\Excel\Facades\Excel;

use Mpdf\Mpdf;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class MachineJobsTable extends DataTableComponent
{
    protected $model = MachineJob::class;

    public array $bulkActions = [
        'exportSelected' => 'Report PDF 1',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setColumnSelectStatus(false);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")->sortable()->hideIf(!false),
            Column::make('Matricola')->sortable()->searchable(),
            Column::make('Cliente', 'customer.name')->sortable()->searchable()->eagerLoadRelations(),
            Column::make('Modello', 'product.name')->sortable()->searchable()->eagerLoadRelations(),
            Column::make('Carrello', 'cart.name')->sortable()->searchable()->eagerLoadRelations(),
            Column::make('Imballo', 'package.name')->sortable()->searchable()->eagerLoadRelations(),
            Column::make("Data Consegna", "data_consegna")->sortable(),
            BooleanColumn::make('Eseguito', 'created_at')->setSuccessValue(false)
        ];
    }

    // public function filters(): array
    // {
    //     return [
    //         DateFilter::make('Data Consegna'),
    //     ];
    // }

    public function exportSelected(){
        foreach($this->getSelected() as $item)
        {
            // return Excel::download(new MachineJobExport, 'tasks.xls');
        }
        $this->clearSelected();
    }
}
