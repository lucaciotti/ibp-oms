<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class PlanTypeAttribute extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the attribute associated with the PlanTypeAttribute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attribute(): HasOne
    {
        return $this->hasOne(Attribute::class, 'id', 'attribute_id');
    }

    public function plantype(): HasOne
    {
        return $this->hasOne(PlanType::class, 'id', 'type_id');
    }
}
