<?php

namespace App\Imports;

use App\Models\PlanFilesTempTask;
use App\Models\PlanImportFile;
use App\Models\PlanImportType;
use App\Models\PlanImportTypeAttribute;
use Carbon\Carbon;
use Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PlanTempTasksImport implements ToModel, WithStartRow, SkipsEmptyRows, WithCalculatedFormulas
{
    protected $importedfile;
    protected $importType;
    protected $typeAttribute;
    protected $rowNum=1;
    protected $rules = [];
    
    public function __construct($id){
        $this->importedfile = PlanImportFile::find($id);
        $this->importType = PlanImportType::where('id', $this->importedfile->import_type_id)->first();
        $this->typeAttribute = PlanImportTypeAttribute::where('import_type_id', $this->importedfile->import_type_id)->with(['attribute'])->orderBy('cell_num')->get();
        PlanFilesTempTask::where('import_file_id', $this->importedfile->id)->delete();
        foreach ($this->typeAttribute as $confRow) {
            if ($confRow->attribute->required) {
                $this->rules[''. $confRow->cell_num - 1 .''] = 'required';
            }
        }
    }

    public function rules(): array
    {
        return $this->rules;
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($this->rowNum!=0){
            $dataRow=[
                'import_file_id' => $this->importedfile->id,
                'type_id' => $this->importType->type_id,
                'num_row' => ++$this->rowNum,
            ];
            try {
                foreach ($this->typeAttribute as $confRow) {
                    $cell_num = $confRow->cell_num-1;
                    switch ($confRow->attribute->col_type) {
                        case 'date':
                            $data = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$cell_num]));
                            break;
                        case 'integer':
                            $data = intval($row[$cell_num]);
                            break;
                        case 'boolean':
                            $data = (bool)$row[$cell_num];
                            break;

                        default:
                            $data = strval($row[$cell_num]);
                            break;
                    }
                    $dataRow[$confRow->attribute->col_name] = $data;
                }
                Log::info($dataRow);
                return new PlanFilesTempTask($dataRow);
            } catch (\Throwable $th) {
                report($th);
                return false;
            }
        }else{
            ++$this->rowNum;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 2;
    }
}
