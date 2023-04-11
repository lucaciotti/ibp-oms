<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use OwenIt\Auditing\Contracts\Auditable;

class PlanImportFile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;
    
    protected $guarded = ['id'];

    /**
     * Get the planimporttype associated with the PlanImportFile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function planimporttype(): HasOne
    {
        return $this->hasOne(PlanImportType::class, 'id', 'import_type_id');
    }

    public function plantype(): HasOneThrough
    {
        return $this->hasOneThrough(PlanType::class, PlanImportType::class, 'id', 'id', 'import_type_id', 'type_id');
    }

    public function planfiletemptasks(): HasMany
    {
        return $this->hasMany(PlanFilesTempTask::class, 'import_file_id', 'id');
    }

}
