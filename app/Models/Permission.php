<?php

namespace App\Models;

use Laratrust\Models\LaratrustPermission;
use OwenIt\Auditing\Contracts\Auditable;

class Permission extends LaratrustPermission implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    public $guarded = [];
}
