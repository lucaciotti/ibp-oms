<?php

namespace App\Exports;

use App\Models\PlanImportType;
use App\Models\PlanImportTypeAttribute;
use App\Models\PlannedTask;
use DateInterval;
use DatePeriod;
use DateTime;
use DatetimeHelper;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StatPlannedTaskExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $plantype_id;
    protected $month;
    protected $year;
    protected $completed;
    protected $datetype;
    protected $aDateOfWeeks = [];

    public function __construct($plantype_id, $month, $year, $completed, $datetype)
    {
        $this->plantype_id = $plantype_id;
        $this->month = $month;
        $this->year = $year;
        $this->completed = $completed;
        $this->datetype = $datetype;

        $period   = DatetimeHelper::getDateWeekPeriodByMonth($this->month, $this->year);
        foreach ($period as $date) {
            array_push($this->aDateOfWeeks, $date);
        }
    }

    public function query()
    {
        $query =  PlannedTask::query()->select('ibp_prodotto_tipo as modello')->selectRaw('MAX(id) as id');
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            $firstDayOfWeek = clone $date;
            $lastDayOfWeek = (clone $date)->modify('next Sunday');
            // $query->selectRaw('SUM(IF(ibp_data_inizio_prod>="' . $firstDayOfWeek->format('Y-m-d') . '" and ibp_data_inizio_prod<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
            $query = $query->selectRaw('SUM(IF(' . $this->datetype . '>="' . $firstDayOfWeek->format('Y-m-d') . '" and ' . $this->datetype . '<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
        }
        if ($this->completed != null) {
            if ($this->completed == 'no') $query = $query->where('completed', false);
            if ($this->completed == 'si') $query = $query->where('completed', true);
        }
        $query = $query->where('type_id', $this->plantype_id)->groupBy('modello')->orderBy('modello');

        $query_b =
        PlannedTask::query()->selectRaw('"TOTALE" as modello')->selectRaw('MAX(id)+1 as id');
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            $firstDayOfWeek = clone $date;
            $lastDayOfWeek = (clone $date)->modify('next Sunday');
            // $query_b->selectRaw('SUM(IF(ibp_data_inizio_prod>="' . $firstDayOfWeek->format('Y-m-d') . '" and ibp_data_inizio_prod<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
            $query_b = $query_b->selectRaw('SUM(IF(' . $this->datetype . '>="' . $firstDayOfWeek->format('Y-m-d') . '" and ' . $this->datetype . '<="' . $lastDayOfWeek->format('Y-m-d') . '", 1, 0)) as w_' . $date->format('W'));
        }
        if ($this->completed != null) {
            // dd($this->completed);
            if ($this->completed == 'no') $query_b = $query_b->where('completed', false);
            if ($this->completed == 'si') $query_b = $query_b->where('completed', true);
        }
        $query_b = $query_b->where('type_id', $this->plantype_id)->groupBy('modello')->orderBy('modello');
        // dd($this->query->get());

        return $query->union($query_b);
    }

    public function headings(): array
    {
        $head = [];
        array_push($head, 'Modello');
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            array_push(
                $head,
                Carbon::instance($date->modify('next Friday'))->currentOrNextBusinessDay()->format('d/m/Y') . ' [w_' . ($date->format('W') - 1) . ']'
            );
        }
        return $head;
    }

    public function columnFormats(): array
    {
        $format = [];
        // $alphabet = range('A', 'Z');
        // $index = 0;
        // foreach ($this->typeAttribute as $column) {
        //     if ($column->attribute->col_type == 'date') {
        //         $format[$alphabet[$index]] = NumberFormat::FORMAT_DATE_DDMMYYYY;
        //     }
        //     $index++;
        // }
        return $format;
        // return [
        //     'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        //     'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        // ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }


    public function map($row): array
    {
        $body = [];
        array_push($body, $row->modello);
        foreach ($this->aDateOfWeeks as $date) {
            if (!is_object($date)) {
                $date = new DateTime($date['date']);
            }
            $colname = 'w_' . $date->format('W');
            array_push($body, $row->$colname);
        }
        // Date::dateTimeToExcel($invoice->created_at),
        return $body;
    }
}
