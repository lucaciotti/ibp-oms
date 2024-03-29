<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class PlanImportType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;
    
    protected $guarded = ['id'];

    /**
     * Get the plantype associated with the PlanImportType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function plantype(): HasOne
    {
        return $this->hasOne(PlanType::class, 'id', 'type_id');
    }
}
