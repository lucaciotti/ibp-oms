<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlanImportFile
 *
 * @property int $id
 * @property int $import_type_id
 * @property string $filename
 * @property string $path
 * @property string $date_upload
 * @property string|null $date_last_import
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereDateLastImport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereDateUpload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereImportTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanImportFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlanImportFile extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
}
