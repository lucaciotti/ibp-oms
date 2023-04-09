<?php

namespace App\Http\Livewire\Attribute;

use App\Models\Attribute;
use App\Models\PlannedTask;
use App\Notifications\DefaultMessageNotify;
use Auth;
use DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Validator;
use Notification;
use Schema;
use Str;
use WireElements\Pro\Components\Modal\Modal;

class AttributeModalEdit extends Modal
{
    public $attribute;
    public $title;
    public $mode;

    public $label;
    public $col_type;

    protected $rules = [
        'label' => 'required|unique:attributes',
        'col_type' => 'required|in:string,integer,float,date,text,boolean',
    ];

    protected $messages = [
        'label.required' => 'Nome Attributo obbligatorio!',
        'label.unique' => 'Nome Attributo già utilizzato',
        'col_type.required' => 'Tipo Attributo obbligatorio!',
    ];

    public function mount($id = null)
    {
        if (empty($id)) {
            $this->mode = 'insert';
            $this->title = 'Nuovo Attributo';
        } else {
            $this->mode = 'edit';
            $this->title = 'Modifica Attributo [' . $id . ']';
            $this->attribute = Attribute::find($id);
            $this->label = $this->attribute->label;
            $this->col_type = $this->attribute->col_type;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->withValidator(function (Validator $validator) {
            $validator->after(function ($validator) {
                $tasksColumns = array_keys((new PlannedTask())->getTableColumns());
                $colName = 'ibp_' . Str::snake(preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->label));
                if (in_array($colName, $tasksColumns)) {
                    $validator->errors()->add('label', 'Attenzione! Contattare supporto! Inserire altro nome per continuare!');
                }
            });
        })->validate();
        if (empty($this->attribute)) {
            try {
                DB::transaction(function () use ($validatedData) {
                    $attr = new Attribute();
                    $attr->col_name = 'ibp_' . Str::snake(preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['label']));
                    $attr->col_type = $validatedData['col_type'];
                    $attr->label = $validatedData['label'];
                    $attr->save();

                    // Migration on TempTask
                    Schema::table('plan_files_temp_tasks', function (Blueprint $table) use ($attr) {
                        $col_type = $attr->col_type;
                        $col_name = $attr->col_name;

                        $table->$col_type($col_name)->nullable()->comment($attr->label);
                    });
                    // Migration on PlannedTask
                    Schema::table('planned_tasks', function (Blueprint $table) use ($attr) {
                        $col_type = $attr->col_type;
                        $col_name = $attr->col_name;

                        $table->$col_type($col_name)->nullable()->comment($attr->label);
                    });
                });
                Notification::send(Auth::user(), new DefaultMessageNotify(
                    $title = 'Creazione Attributo',
                    $body = 'Attributo '. $validatedData['label'].' creato',
                    $link = 'config/attributes',
                    $level = 'info'
                ));
            } catch (\Throwable $th) {
                if (!Str::contains($th->getMessage(), 'There is no active transaction')) {
                    Notification::send(Auth::user(), new DefaultMessageNotify(
                        $title = 'Creazione Attributo',
                        $body = 'Attributo ' . $validatedData['label'] . ' errore!'. $th->getMessage(),
                        $link = 'config/attributes',
                        $level = 'error'
                    ));
                } else {
                    Notification::send(Auth::user(), new DefaultMessageNotify(
                        $title = 'Creazione Attributo',
                        $body = 'Attributo ' . $validatedData['label'] . ' creato',
                        $link = 'config/attributes',
                        $level = 'info'
                    ));
                }
            }
            
        } else {
            $this->attribute->update($validatedData);
        }

        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    public function render()
    {
        return view('livewire.attribute.attribute-modal-edit');
    }



    public static function behavior(): array
    {
        return [
            // Close the modal if the escape key is pressed
            'close-on-escape' => true,
            // Close the modal if someone clicks outside the modal
            'close-on-backdrop-click' => false,
            // Trap the users focus inside the modal (e.g. input autofocus and going back and forth between input fields)
            'trap-focus' => true,
            // Remove all unsaved changes once someone closes the modal
            'remove-state-on-close' => false,
        ];
    }

    public static function attributes(): array
    {
        return [
            // Set the modal size to 2xl, you can choose between:
            // xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl
            'size' => '4xl',
        ];
    }
}
