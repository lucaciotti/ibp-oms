<?php

namespace App\Http\Livewire\Attribute;

use App\Http\Livewire\Layouts\DynamicContent;
use App\Models\Attribute;
use App\Models\PlannedTask;
use App\Notifications\DefaultMessageNotify;
use Auth;
use Notification;

class Content extends DynamicContent
{
    public $syncJob = false;

    public function render()
    {
        return view('livewire.attribute.content');
    }

    public function fetchAttributeFromTasksTable() {
        $this->syncJob = true;
        $tasksColumns = (new PlannedTask())->getTableColumns();
        $attributes = array_column(Attribute::select('col_name')->get()->toArray(), 'col_name');
        foreach ($tasksColumns as $colName => $details) {
            if (!in_array($colName, $attributes)){
                $attr = new Attribute();
                $attr->col_name = $colName;
                $attr->col_type = $details['type'];
                $attr->label = !empty($details['comment']) ? $details['comment'] : Str::headline(Str::replace('ibp_', '', $colName));
                $attr->required = $details['required'];
                // $attr->default = $details['default'];
                $attr->save();
            }
        }
        Notification::send(Auth::user(), new DefaultMessageNotify(
            $title = 'Sincronizzazione Lista Attributi',
            $body = 'Sincronizzazione attributi terminata',
            $link = 'config/attributes',
            $level = 'info'
        ));
        $this->syncJob = false;
        $this->emit('refreshDatatable');
    }
}
