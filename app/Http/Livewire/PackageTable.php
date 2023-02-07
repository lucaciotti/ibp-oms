<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Package;

class PackageTable extends DataTableComponent
{
    protected $model = Package::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")->sortable()->hideIf(!false),
            Column::make('Name')->sortable()->searchable(),
            Column::make("Data creazione", "created_at")->sortable(),
        ];
    }
}
