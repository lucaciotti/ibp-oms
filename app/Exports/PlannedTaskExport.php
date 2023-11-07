<?php

namespace App\Exports;

use App\Models\PlanImportType;
use App\Models\PlanImportTypeAttribute;
use App\Models\PlannedTask;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PlannedTaskExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles
{
    protected $taskIds;
    protected $order_tasks;
    protected $filter_on_tasks;
    protected $importType;
    protected $typeAttribute;

    public function __construct($taskIds, $import_type_id, $order_tasks, $filter_on_tasks)
    {
        $this->taskIds = $taskIds;
        $this->order_tasks = $order_tasks;
        $this->filter_on_tasks = $filter_on_tasks;
        $this->importType = PlanImportType::where('id', $import_type_id)->first();
        $this->typeAttribute = PlanImportTypeAttribute::where('import_type_id', $import_type_id)->with(['attribute'])->orderBy('cell_num')->get();
        // dd($this);
    }

    public function query()
    {
        if ($this->order_tasks) {
            $order_applied = false;
            $tasksWithSameValues = PlannedTask::whereIn('id', $this->taskIds);
            foreach ($this->order_tasks as $key => $value) {
                    $order_applied = true;
                    $tasksWithSameValues->orderBy($key, $value);
            }
            if (!$order_applied) {
                $tasksWithSameValues->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc');
            }
        } else {
            $tasksWithSameValues = PlannedTask::query()->whereIn('id', $this->taskIds)->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc');
        }
        // return PlannedTask::query()->whereIn('id', $this->taskIds);
        return $tasksWithSameValues;
    }

    public function headings(): array
    {   
        $head = [];
        foreach ($this->typeAttribute as $column) {
            array_push($head, $column->attribute->label);
        }
        return $head;
    }

    public function columnFormats(): array
    {
        $format = [];
        $alphabet = range('A', 'Z');
        $index=0;
        foreach ($this->typeAttribute as $column) {
            if ($column->attribute->col_type == 'date') {
                $format[$alphabet[$index]] = NumberFormat::FORMAT_DATE_DDMMYYYY;
            }
            $index++;
        }
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
        foreach ($this->typeAttribute as $column) {
            $colname = $column->attribute->col_name;
            if($column->attribute->col_type=='date'){
                array_push($body, Date::dateTimeToExcel($row->$colname));
            } else {
                array_push($body, $row->$colname);
            }
        }
        // Date::dateTimeToExcel($invoice->created_at),
        return $body;
    }


}
