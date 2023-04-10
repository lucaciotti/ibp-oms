<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Attribute extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    use HasFactory;

    protected $guarded = ['id'];

    // protected function default(): Attribute
    // {
    //     return Attribute::make(
    //         get: function ($value) {
    //             switch ($this->attributes['col_type']) {
    //                 case 'string':
    //                     $this->attributes['default_string'];
    //                     break;
    //                 case 'integer':
    //                     $this->attributes['default_integer'];
    //                     break;
    //                 case 'float':
    //                     $this->attributes['default_float'];
    //                     break;
    //                 case 'boolean':
    //                     return $this->attributes['default_boolean'];
    //                     break;
    //                 case 'text':
    //                     return '';
    //                     break;
    //                 case 'date':
    //                     return '';
    //                     break;
    //                 default:
    //                     return '';
    //                     break;
    //             }
    //         },
    //         set: function ($value) {
    //             switch ($this->attributes['col_type']) {
    //                 case 'string':
    //                     $this->attributes['default_string']=$value;
    //                     break;
    //                 case 'integer':
    //                     $this->attributes['default_integer']=$value;
    //                     break;
    //                 case 'float':
    //                     $this->attributes['default_float']=$value;
    //                     break;
    //                 case 'boolean':
    //                     $this->attributes['default_boolean']=$value;
    //                     break;
    //                 default:
    //                     break;
    //             }
    //         },
    //     );
    // }

    public function planTypeAttribute(){
        return $this->hasMany('App\Models\PlanTypeAttribute', 'attribute_id', 'id');
    }

    public function planImportTypeAttribute(){
        return $this->hasMany('App\Models\PlanImportTypeAttribute', 'attribute_id', 'id');
    }
}
