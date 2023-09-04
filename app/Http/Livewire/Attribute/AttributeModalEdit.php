<?php

namespace App\Http\Livewire\Attribute;

use App\Models\Attribute;
use App\Models\PlannedTask;
use App\Models\User;
use App\Notifications\DefaultMessageNotify;
use Auth;
use DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Validator;
use Log;
use Notification;
use Schema;
use Str;
use WireElements\Pro\Components\Modal\Modal;
use WireElements\Pro\Concerns\InteractsWithConfirmationModal;

class AttributeModalEdit extends Modal
{
    use InteractsWithConfirmationModal;

    public $attribute;
    public $title;
    public $mode;

    public $label;
    public $col_type;
    public $hidden_in_view=false;

    protected function rules()
    {
        if($this->mode == 'edit') {
            return [
                'label' => 'required|unique:attributes,label,' . $this->attribute->id,
                'col_type' => 'required|in:string,integer,float,date,text,boolean',
                'hidden_in_view' => 'required',
            ];
        } else {
            return [
                'label' => 'required|unique:attributes',
                'col_type' => 'required|in:string,integer,float,date,text,boolean',
                'hidden_in_view' => 'required',
            ];
        }
    }

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
            $this->hidden_in_view = $this->attribute->hidden_in_view;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $tasksColumns = array_keys((new PlannedTask())->getTableColumns());
        $colName = $this->existColumnName('ibp_' . Str::snake(preg_replace('/[^\p{L}\p{N}\s]/u', '', Str::lower($this->label))), $tasksColumns);

        $validatedData = $this->withValidator(function (Validator $validator) use ($colName) {
            $validator->after(function ($validator) use ($colName) {
                if(strlen($colName)>20 and empty($this->attribute)){
                    $validator->errors()->add('label', 'Attenzione! Nome Attributo troppo lungo per Database!');
                }
            });
        })->validate();

        if (empty($this->attribute)) {
            //  CREO ATTRIBUTO
            try {
                DB::transaction(function () use ($validatedData, $colName) {
                    $attr = new Attribute();
                    $attr->col_name = $colName;
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
                if (Str::contains($th->getMessage(), 'There is no active transaction')) {
                    Notification::send(Auth::user(), new DefaultMessageNotify(
                        $title = 'Creazione Attributo',
                        $body = 'Attributo ' . $validatedData['label'] . ' creato',
                        $link = 'config/attributes',
                        $level = 'info'
                    ));
                } else {
                    report($th);
                    #INVIO NOTIFICA
                    $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', Auth::user()->id)->get();
                    foreach ($notifyUsers as $user) {
                        Notification::send(
                            $user,
                            new DefaultMessageNotify(
                                    $title = 'Creazione Attributo - [' . $validatedData['label'] . ']!',
                                    $body = 'Errore: [' . $th->getMessage() . ']',
                                    $link = '#',
                                    $level = 'error'
                                )
                        );
                    }
                }
            }     
        } else {
            //  MODIFICO ATTRIBUTO
            try {
                DB::transaction(function () use ($validatedData) {
                    // DISABILITO MOMENTANEAMENTE LA MODIFICA DEL NOME
                    // $old_name = $this->attribute->col_name;
                    // $new_name = 'ibp_' . Str::snake(preg_replace('/[^\p{L}\p{N}\s]/u', '', $validatedData['label']));
                    // $validatedData['col_name'] = $old_name;
                    $old_required = $this->attribute->required;
                    $new_required = $validatedData['required'];
                    $this->attribute->update($validatedData);
                    // if ($old_required != $new_required){
                    //     if ($new_required==false){
                    //         // Migration on TempTask
                    //         Schema::table('plan_files_temp_tasks', function (Blueprint $table) {
                    //             $col_type = $this->attribute->col_type;
                    //             $col_name = $this->attribute->col_name;

                    //             $table->$col_type($col_name)->nullable()->comment($this->attribute->label)->change();
                    //         });
                    //         // Migration on PlannedTask
                    //         Schema::table('planned_tasks', function (Blueprint $table) {
                    //             $col_type = $this->attribute->col_type;
                    //             $col_name = $this->attribute->col_name;

                    //             $table->$col_type($col_name)->nullable()->comment($this->attribute->label)->change();
                    //         });
                    //     } else {

                    //     }
                    // }
                });
                Notification::send(Auth::user(), new DefaultMessageNotify(
                    $title = 'Modifica Attributo',
                    $body = 'Attributo ' . $validatedData['label'] . ' modificato',
                    $link = 'config/attributes',
                    $level = 'info'
                ));
            } catch (\Throwable $th) {
                if (Str::contains($th->getMessage(), 'There is no active transaction')) {
                    Notification::send(Auth::user(), new DefaultMessageNotify(
                        $title = 'Modifica Attributo',
                        $body = 'Attributo ' . $validatedData['label'] . ' errore!' . $th->getMessage(),
                        $link = 'config/attributes',
                        $level = 'error'
                    ));
                } else {
                    report($th);
                    #INVIO NOTIFICA
                    $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', Auth::user()->id)->get();
                    foreach ($notifyUsers as $user) {
                        Notification::send(
                            $user,
                            new DefaultMessageNotify(
                                $title = 'Modifica Attributo - [' . $validatedData['label'] . ']!',
                                $body = 'Errore: [' . $th->getMessage() . ']',
                                $link = '#',
                                $level = 'error'
                            )
                        );
                    }
                }
            }
        }

        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    private function existColumnName($colName, $tasksColumns){
        $n = 1;
        while (in_array($colName, $tasksColumns)){
            if (preg_match('/(_\d+)(?!.+\1)/', $colName, $matches, PREG_OFFSET_CAPTURE)){
                $colName = Str::substr($colName, 0, $matches[0][1]) . '_' . $n;
            } else {
                $colName = $colName . '_' . $n;   
            }
            $n++;
        }
        return $n;
    }

    public function delete()
    {
        try {
            $attribute = $this->attribute->label;
            $col_name = $this->attribute->col_name;

            DB::transaction(function () use($col_name) {
                foreach ($this->attribute->planTypeAttribute as $planTypeAttribute) {
                    $planTypeAttribute->delete();
                }
                foreach ($this->attribute->planImportTypeAttribute as $planImportTypeAttribute) {
                    $planImportTypeAttribute->delete();
                }
                $this->attribute->delete();

                Schema::table('plan_files_temp_tasks', function (Blueprint $table) use ($col_name) {
                    $table->dropColumn($col_name);
                });
                // Migration on PlannedTask
                Schema::table('planned_tasks', function (Blueprint $table) use ($col_name) {
                    $table->dropColumn($col_name);
                });

            });

            Notification::send(Auth::user(), new DefaultMessageNotify(
                $title = 'Attributo - Cancellato!',
                $body = 'Cancellato attributo: ' . $attribute,
                $link = '#',
                $level = 'warning'
            ));
        } catch (\Throwable $th) {
            if (Str::contains($th->getMessage(), 'There is no active transaction')) {
                Notification::send(Auth::user(), new DefaultMessageNotify(
                    $title = 'Attributo - Cancellato!',
                    $body = 'Cancellato attributo: ' . $attribute,
                    $link = '#',
                    $level = 'warning'
                ));
            } else {
                report($th);
                #INVIO NOTIFICA
                $notifyUsers = User::whereHas('roles', fn ($query) => $query->where('name', 'admin'))->orWhere('id', Auth::user()->id)->get();
                foreach ($notifyUsers as $user) {
                    Notification::send(
                        $user,
                        new DefaultMessageNotify(
                            $title = 'Cancellazione Attributo - [' . $attribute . ']!',
                            $body = 'Errore: [' . $th->getMessage() . ']',
                            $link = '#',
                            $level = 'error'
                        )
                    );
                }
            }
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

    public function deleteConfirmation()
    {
        // Prima di tutto controllo se l'attributo ha dei dati in PlannedTask
        if(PlannedTask::whereNotNull($this->attribute->col_name)->orWhere($this->attribute->col_name, '<>', '')->count()+1>0){
            // $msg = 'L\attributo presenta dai DATI nelle pianificazioni che ANDRANNO PERSI, procedere?';
            $this->askForConfirmation(
                callback: function () {
                    return false;
                },
                prompt: [
                    'title' => __('Attenzione!'),
                    'message' => __('L\'attributo presenta dei DATI nelle pianificazioni. NON è possibile cancellarlo!'),
                    'confirm' => __('Ok'),
                    // 'cancel' => __('No'),
                ],
                // confirmPhrase: 'CANCELLA',
                theme: 'warning',
                modalBehavior: [
                    'close-on-escape' => false,
                    'close-on-backdrop-click' => false,
                    'trap-focus' => true,
                ],
                modalAttributes: [
                    'size' => '2xl'
                ]
            );
        } else {
            $msg = 'L\attributo e tutti i collegamenti verrano cancellati, procedere?';
            $this->askForConfirmation(
                callback: function () {
                    return $this->delete();
                },
                prompt: [
                    'title' => __('Attenzione!'),
                    'message' => __($msg),
                    'confirm' => __('Si, Cancella'),
                    'cancel' => __('No'),
                ],
                confirmPhrase: 'CANCELLA',
                theme: 'warning',
                modalBehavior: [
                    'close-on-escape' => false,
                    'close-on-backdrop-click' => false,
                    'trap-focus' => true,
                ],
                modalAttributes: [
                    'size' => '2xl'
                ]
            );
        }        
    }

}
