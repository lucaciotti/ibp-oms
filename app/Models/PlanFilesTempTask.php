<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PlanFilesTempTask
 *
 * @property int $id
 * @property int $import_file_id
 * @property int $type_id
 * @property int|null $task_id
 * @property string $plan_matricola
 * @property string $data_consegna
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
 * @property int $imported
 * @property string|null $date_last_import
 * @property int $selected
 * @property int $warning
 * @property string|null $error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereBasamento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereBasamentoOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereBatteria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereBraccio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereCarrello($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereCarrelloOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereCarrelloOpt2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereCarrelloOpt3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereClienteRagsoc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereColonna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereColonnaOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereDataConsegna($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereDateLastImport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImballoDim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImballoInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImballoNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImballoTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImballoTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImpianto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImportFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereImported($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereMontaggioTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask wherePlanMatricola($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask wherePlanNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask wherePressoreOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereProdottoTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereRal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereRampaDimeOpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereRuotaTastatrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanFilesTempTask whereWarning($value)
 * @mixin \Eloquent
 */
class PlanFilesTempTask extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];
}
