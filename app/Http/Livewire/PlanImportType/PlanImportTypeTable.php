<?php

namespace App\Http\Livewire\PlanImportType;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlanImportType;
use Illuminate\Database\Eloquent\Builder;
use Session;

class PlanImportTypeTable extends DataTableComponent
{
    protected $model = PlanImportType::class;

    public function builder(): Builder
    {
        if (Session::has('config.planimporttype.id')) {
            return PlanImportType::query()
                ->where('type_id', Session::get('config.planimporttype.id', '1'));
        }
        return PlanImportType::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setDebugEnabled();
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
