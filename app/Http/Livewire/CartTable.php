<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Cart;

class CartTable extends DataTableComponent
{
    protected $model = Cart::class;

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
            // Column::make("Updated at", "updated_at")
            //     ->sortable(),
        ];
    }
}
