<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlanImportType
 *
 * @property int $id
 * @property int $type_id
 * @property string $name
 * @property string $description
 * @property int $default
 * @property mixed $columns_import
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereColumnsImport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlanImportType extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
}
