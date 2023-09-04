<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PlanFilesTempTask extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;
    
    protected $guarded = ['id'];

    protected $casts = [
        'ibp_data_consegna' => 'datetime:d-m-Y',
        'ibp_data_inizio_prod' => 'datetime:d-m-Y',
    ];
}
