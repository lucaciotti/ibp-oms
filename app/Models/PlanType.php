<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlanType
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlanType extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
}
