<?php

namespace App\Exports;

use App\Models\PlanImportType;
use App\Models\PlanImportTypeAttribute;
use App\Models\PlannedTask;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class PlannedTaskAllExport implements WithMultipleSheets
{
    use Exportable;

    protected $planConf;
    protected $filters;

    public function __construct($planConf, $filters)
    {
        $this->planConf = $planConf;
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->planConf as $key => $plan) {
            if(!$plan['selected']) continue;
            $planTypeId = $plan['planType']['id'];
            $exportXlsTypeId = $plan['xlsTypeId'];
            $planName = $plan['planType']['name'];
            $sheets[] = new PlannedTaskSheet($planName, $planTypeId, $exportXlsTypeId, null, $this->filters);
        }

        return $sheets;
    }

}

class PlannedTaskSheet implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles, WithTitle
{
    protected $planName;
    protected $planTypeId;
    protected $order_tasks;
    protected $filters;
    protected $exportType;
    protected $typeAttribute;

    public function __construct($planName, $planTypeId, $exportXlsTypeId, $order_tasks, $filters)
    {
        $this->planName = $planName;
        $this->planTypeId = $planTypeId;
        $this->order_tasks = $order_tasks;
        $this->filters = $filters;
        $this->exportType = PlanImportType::where('id', $exportXlsTypeId)->first();
        $this->typeAttribute = PlanImportTypeAttribute::where('import_type_id', $exportXlsTypeId)->with(['attribute'])->orderBy('cell_num')->get();
        // dd($this);
    }

    public function query()
    {
        $tasksWithSameValues = PlannedTask::select();
        $tasksWithSameValues->where('type_id', $this->planTypeId);
        if($this->filters){
            foreach ($this->filters as $key => $filter) {
                $value= $filter['value'];
                if($value=='' or $value==null) continue;                
                $colname= $filter['column_name'];
                $type= $filter['type'];
                $operator= $filter['operator'];
                if($type=='date'){
                    $date = (new Carbon($value));
                    $tasksWithSameValues = $tasksWithSameValues->where($colname, $operator, $date);
                }
                if ($type == 'string') {
                    $tasksWithSameValues = $tasksWithSameValues->where($colname, $operator, '%'.$value.'%');
                }
                if ($type == 'choice') {
                    if($value=='true') $value=1;
                    if($value=='false') $value=0;
                    $tasksWithSameValues = $tasksWithSameValues->where($colname, $operator, $value);
                }
            }
        }

        if ($this->order_tasks) {
            $order_applied = false;
            foreach ($this->order_tasks as $key => $value) {
                $order_applied = true;
                $tasksWithSameValues->orderBy($key, $value);
            }
            if (!$order_applied) {
                $tasksWithSameValues->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc');
            }
        } else {
            $tasksWithSameValues = $tasksWithSameValues->orderBy('ibp_data_inizio_prod')->orderBy('ibp_cliente_ragsoc');
        }
        // return PlannedTask::query()->whereIn('id', $this->taskIds);
        return $tasksWithSameValues;
    }

    public function headings(): array
    {
        $head = [];
        array_push($head, 'Completato');
        array_push($head, 'Data Completato');
        foreach ($this->typeAttribute as $column) {
            array_push($head, $column->attribute->label);
        }
        return $head;
    }

    public function columnFormats(): array
    {
        $format = [];
        $alphabet = range('C', 'Z');
        $index = 0;
        $format['B'] = NumberFormat::FORMAT_DATE_DDMMYYYY;
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
        array_push($body, ($row->completed ? 'Completato' : '-'));
        array_push($body, ($row->completed ? Date::dateTimeToExcel($row->completed_date) : '-'));
        foreach ($this->typeAttribute as $column) {
            $colname = $column->attribute->col_name;
            if ($column->attribute->col_type == 'date') {
                array_push($body, Date::dateTimeToExcel($row->$colname));
            } else {
                array_push($body, $row->$colname);
            }
        }
        // Date::dateTimeToExcel($invoice->created_at),
        return $body;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->planName;
    }
}
