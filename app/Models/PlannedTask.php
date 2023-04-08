<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Schema;

class PlannedTask extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $exceptColumnList = ['id', 'type_id', 'completed', 'created_at', 'updated_at'];

    public function getTableColumns()
    {
        $columnsDetails = [];
        $tableColumns = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        // $columns = array_merge(array_diff($tableColumns, $this->exceptColumnList));
        $columns = array_filter($tableColumns, function ($item) {
            return Str::startsWith($item, 'ibp_');
        });
        foreach ($columns as $column) {
            $detail = Schema::getConnection()->getDoctrineColumn($this->getTable(), $column);
            $columnsDetails[$column] = [
                'type' => $detail->getType()->getName(),
                'comment' => $detail->getComment(),
                'required' => $detail->getNotnull(),
                'default' => $detail->getDefault()
            ];
        }
        return $columnsDetails;
    }

}
