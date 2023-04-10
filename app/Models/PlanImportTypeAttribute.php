<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class PlanImportTypeAttribute extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    protected $table = 'plan_import_types_attribute';
    protected $guarded = ['id'];
    /**
     * Get the plantype associated with the PlanImportType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attribute(): HasOne
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function planimporttype(): HasOne
    {
        return $this->hasOne(PlanImportType::class, 'id', 'import_type_id');
    }
}
