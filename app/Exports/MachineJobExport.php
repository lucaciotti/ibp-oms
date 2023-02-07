<?php

namespace App\Exports;

use App\Models\MachineJob;
use Maatwebsite\Excel\Concerns\FromCollection;

class MachineJobExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MachineJob::all();
    }
}
