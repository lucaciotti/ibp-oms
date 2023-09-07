<?php

namespace App\Imports;

use App\Exceptions\ImportFileException;
use App\Models\PlanFilesTempTask;
use App\Models\PlanImportFile;
use App\Models\PlanImportType;
use App\Models\PlanImportTypeAttribute;
use App\Models\User;
use App\Notifications\DefaultMessageNotify;
use Auth;
use Carbon\Carbon;
use Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Notification;
use Str;

class PlanTempTasksImport implements ToModel, WithStartRow, SkipsEmptyRows, WithCalculatedFormulas, WithMultipleSheets, SkipsOnError
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

    public function sheets(): array
    {
        return [
            0 => $this
        ];
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
                        $data = Str::upper(Str::of(strval($row[$cell_num]))->trim());
                        break;
                }

                // Controllo MATRICOLA -> unico dato che deve essere sempre corretto
                if($confRow->attribute->col_name=='ibp_plan_matricola'){
                    if(!preg_match('/S\d{6}/',$data)) {
                        throw new ImportFileException('Attenzione la colonna ' . $confRow->cell_num . ' alla riga '.$this->rowNum.' deve contenere: "' . $confRow->attribute->label . '" con valore valido e non deve essere vuota!');    
                    }
                }
                if ($confRow->attribute->required && empty($row[$cell_num])) {
                    // Log::error('Attenzione la colonna ' . $confRow->cell_num . ' alla riga ' . $this->rowNum . ' deve contenere: ' . $confRow->attribute->label . ' e non deve essere vuota!');
                    throw new ImportFileException('Attenzione la colonna ' . $confRow->cell_num . ' alla riga '.$this->rowNum.' deve contenere: "' . $confRow->attribute->label . '" e non deve essere vuota!');
                }

                $dataRow[$confRow->attribute->col_name] = $data;
            }
            return new PlanFilesTempTask($dataRow);
        } else {
            ++$this->rowNum;
        }
    }

    public function onError(\Throwable $th)
    {
        report($th);
        #INVIO NOTIFICA
        $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', $this->importedfile->userCreated()->id)->get();
        foreach ($notifyUsers as $user) {
            Notification::send(
                $user,
                new DefaultMessageNotify(
                    $title = 'File di Import - [' . $this->importedfile->filename . ']!',
                    $body = 'Errore: [' . $th->getMessage() . ']',
                    $link = '#',
                    $level = 'error'
                )
            );
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
