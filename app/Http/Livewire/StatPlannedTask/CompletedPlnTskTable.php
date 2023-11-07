<?php

namespace App\Http\Livewire\StatPlannedTask;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\PlannedTask;
use App\Models\PlanTypeAttribute;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Laratrust;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Session;

use function PHPUnit\Framework\isInstanceOf;

class CompletedPlnTskTable extends DataTableComponent
{
    protected $model = PlannedTask::class;

    public $type_id;
    public $month;
    public $completed;
    public $datetype;
    public $aDateOfWeeks = [];
    // public $query;

    protected function getListeners()
    {
        return [
            'clearSelected' => 'clearSelected',
        ];
    }

    public function mount()
    {
        // $this->setFilter('date_prod_from', date('Y-m-d', strtotime('-' . date('w') . ' days')));

    }
    
    public function builder(): Builder
    {
        $this->type_id = Session::get('statplannedtask.plantype.id');
        $this->month = !empty(Session::get('statplannedtask.filter.month')) ? Session::get('statplannedtask.filter.month') : (new DateTime())->format('F');
        $this->completed = !empty(Session::get('statplannedtask.filter.completed')) ? ((Session::get('statplannedtask.filter.completed')=='no') ? 0 : 1) : null;
        $this->datetype = Session::get('statplannedtask.filter.datetype');

        try {
            $start    = new DateTime('first day of ' . $this->month . ' this year');
            $end      = new DateTime('last day of ' . $this->month . ' this year');
        } catch (\Throwable $th) {
            $start    = new DateTime('first day of january this year');
            $end      = new DateTime('last day of january this year');
        }
        $interval = new DateInterval('P1W');
        $period   = new DatePeriod($start, $interval, $end);
        foreach ($period as $date) {
            array_push($this->aDateOfWeeks, $date);
        }
        // dd($this->aDateOfWeeks);
        $query =  PlannedTask::query()->select('ibp_prodotto_tipo as modello')->selectRaw('MAX(id) as id');
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            $firstDayOfWeek = clone $date;
            $lastDayOfWeek = (clone $date)->modify('next Sunday');
            // $query->selectRaw('SUM(IF(ibp_data_inizio_prod>="' . $firstDayOfWeek->format('Y-m-d') . '" and ibp_data_inizio_prod<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
            $query->selectRaw('SUM(IF('. $this->datetype.'>="' . $firstDayOfWeek->format('Y-m-d') .'" and ' . $this->datetype . '<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
        }
        if ($this->completed != null) $query->where('completed', $this->completed);
        $query->where('type_id', $this->type_id)->groupBy('modello')->orderBy('modello');
        
        $query_b =
        PlannedTask::query()->selectRaw('"TOTALE" as modello')->selectRaw('MAX(id)+1 as id');
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            $firstDayOfWeek = clone $date;
            $lastDayOfWeek = (clone $date)->modify('next Sunday');
            // $query_b->selectRaw('SUM(IF(ibp_data_inizio_prod>="' . $firstDayOfWeek->format('Y-m-d') . '" and ibp_data_inizio_prod<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
            $query_b->selectRaw('SUM(IF(' . $this->datetype . '>="' . $firstDayOfWeek->format('Y-m-d') .'" and ' . $this->datetype . '<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
        }
        if($this->completed != null) $query_b->where('completed', $this->completed);
        $query_b->where('type_id', $this->type_id)->groupBy('modello')->orderBy('modello');
        // dd($this->query->get());
        
        return $query->union($query_b);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            // ->setDebugEnabled()
            ->setPerPageAccepted([25, 50, 75, 100])
            ->setPerPage(25)
            ->setTrAttributes(function ($row, $index) {
                if ($row->modello == 'TOTALE') {
                    return [
                        'class' => 'text-bold',
                        'style' => 'background-color: lightgrey;',
                    ];
                }

                return [];
            })
            ->setThAttributes(function (Column $column) {
                if (!$column->isField('Modello')) {
                    return [
                        'class' => 'text-center',
                    ];
                }

                return [];
            })
            ->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
                if (!$column->isField('Modello')) {
                    return [
                        'class' => 'text-center',
                    ];
                }

                return [];
            })
            ->setSecondaryHeaderEnabled()
            ->setOfflineIndicatorEnabled()
            ->setFilterLayoutSlideDown()
            ->setHideBulkActionsWhenEmptyEnabled();
    }


    public function columns(): array
    {
        $columns = [];
        array_push(
            $columns,
            Column::make("Modello")
                ->label(
                    fn ($row, Column $column) =>  '<strong>'.$row['modello'].'</strong>'
                )->html()
                ->sortable(),
        );
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            array_push(
                $columns,
                Column::make($date->format('d/m/Y').' [w_'. $date->format('W').']')
                    ->label(
                    function ($row) use($date) {
                        return $row['w_' . $date->format('W')];
                    })
                    ->sortable()
            );
        }
        return $columns;
    }

    // public function filters(): array
    // {
    //     return [
    //         DateFilter::make('Data Prod. [>=]', 'date_prod_from')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_inizio_prod', '>=', $value);
    //             }),

    //         DateFilter::make('Data Prod. [<=]', 'date_prod_to')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_inizio_prod', '<=', $value);
    //             }),

    //         DateFilter::make('Data Consegna [>=]', 'date_from')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_consegna', '>=', $value);
    //             }),

    //         DateFilter::make('Data Consegna [<=]', 'date_to')
    //         ->config([
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('ibp_data_consegna', '<=', $value);
    //             }),

    //         SelectFilter::make('Seleziona Mese', $this->month)
    //         // ->config([
    //         //     'position' => 'bottom',
    //         // ])
    //             ->options([
    //                 'january' => 'Gennaio',
    //                 'february' => 'Febbraio',
    //                 'march' => 'Marzo',
    //                 'april' => 'Aprile',
    //                 'may' => 'Maggio',
    //                 'june' => 'Giugno',
    //                 'july' => 'Luglio',
    //                 'august' => 'Agosto',
    //                 'semptemper' => 'Settembre',
    //                 'october' => 'Ottobre',
    //                 'november' => 'Novembre',
    //                 'december' => 'Dicembre',
    //             ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $this->month = $value;
    //             }),

    //         DateFilter::make('Data Completato [>=]', 'date_complete_from')
    //         ->config([
    //             'position' => 'bottom',
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('completed_date', '>=', $value);
    //             }),

    //         DateFilter::make('Data Completato [<=]', 'date_complete_to')
    //         ->config([
    //             'position' => 'bottom',
    //             'half-space' => true,
    //         ])
    //             ->filter(function (Builder $builder, string $value) {
    //                 $builder->where('completed_date', '<=', $value);
    //             }),


    //     ];
    // }
}