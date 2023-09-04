<?php

namespace App\Models;

use Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Schema;
use OwenIt\Auditing\Contracts\Auditable;

class PlannedTask extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;
    
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $exceptColumnList = ['id', 'type_id', 'created_at', 'updated_at'];

    // protected $dates = ['ibp_data_consegna'];
    protected $casts = [
        'ibp_data_consegna' => 'datetime:d-m-Y',
        'ibp_data_inizio_prod' => 'datetime:d-m-Y',
        'completed_date' => 'datetime:d-m-Y',
    ];


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
        // usort($columnsDetails, fn ($a, $b) => $a['required'] <=> $b['required']);
        return $columnsDetails;
    }

    /**
     * Get the plantype associated with the PlannedTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function plantype(): HasOne
    {
        return $this->hasOne(PlanType::class, 'id', 'type_id');
    }
}
