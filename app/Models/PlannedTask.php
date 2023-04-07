<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlannedTask
 *
 * @property int $id
 * @property string $plan_matricola
 * @property string $data_consegna
 * @property int $type_id
 * @property string $cliente_ragsoc
 * @property string $prodotto_tipo
 * @property string $basamento
 * @property string $basamento_opt
 * @property string $impianto
 * @property string $braccio
 * @property string $colonna
 * @property string $colonna_opt
 * @property string $batteria
 * @property string $ruota_tastatrice
 * @property string $carrello
 * @property string $carrello_opt
 * @property string $carrello_opt_2
 * @property string $carrello_opt_3
 * @property string $pressore_opt
 * @property string $imballo_tipo
 * @property string $imballo_dim
 * @property string $imballo_info
 * @property string $imballo_note
 * @property string $rampa_dime_opt
 * @property string|null $plan_note
 * @property string $ral
 * @property int|null $montaggio_time
 * @property int|null $imballo_time
 * @property int $completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereBasamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereBasamentoOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereBatteria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereBraccio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereCarrello($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereCarrelloOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereCarrelloOpt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereCarrelloOpt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereClienteRagsoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereColonna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereColonnaOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereDataConsegna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereImballoDim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereImballoInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereImballoNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereImballoTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereImballoTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereImpianto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereMontaggioTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask wherePlanMatricola($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask wherePlanNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask wherePressoreOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereProdottoTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereRal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereRampaDimeOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereRuotaTastatrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlannedTask whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PlannedTask extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
}
