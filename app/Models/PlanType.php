<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class PlanType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;
    
    protected $guarded = ['id'];


    public function planimporttype(): HasMany
    {
        return $this->hasMany(PlanImportType::class, 'type_id', 'id');
    }
}
