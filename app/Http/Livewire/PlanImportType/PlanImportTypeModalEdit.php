<?php

namespace App\Http\Livewire\PlanImportType;

use App\Models\PlanImportType;
use WireElements\Pro\Components\Modal\Modal;

class PlanImportTypeModalEdit extends Modal
{
    public PlanImportType $planImportType;
    public $title;
    public $mode;

    public string $type_id;
    public string $name;
    public string $description;
    public bool $default = false;

    protected function rules()
    {
        if($this->mode == 'edit') {
            return [
                'name' => 'required|unique:plan_import_types,name,'.$this->planImportType->id,
                'description' => 'required',
                'default' => 'required',
            ];
        } else {
            return [
                'name' => 'required|unique:plan_import_types,name',
                'description' => 'required',
                'default' => 'required',
            ];
        }
    }

    protected $messages = [
        'name.required' => 'Nome Tipo Import obbligatorio!',
        'name.unique' => 'Nome Tipo Import già utilizzato',
        'description.required' => 'Descrizione Tipo Import obbligatoria!',
    ];

    public function mount($type_id, $import_type_id = null)
    {
        $this->type_id = $type_id;
        if (empty($import_type_id)) {
            $this->mode = 'insert';
            $this->title = 'Nuovo Tipologia di Import';
        } else {
            $this->mode = 'edit';
            $this->title = 'Modifica Tipologia di Import [' . $import_type_id . ']';
            $this->planImportType = PlanImportType::find($import_type_id);
            $this->name = $this->planImportType->name;
            $this->description = $this->planImportType->description;
            $this->default = $this->planImportType->default;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();
        if ($this->default) {
            // Solo uno può essere default
            $prevDefault = PlanImportType::where('type_id', $this->type_id)->where('default', true)->first();
            if(!empty($prevDefault)){
                $prevDefault->default = false;
                $prevDefault->save();
            }
        }
        if (empty($this->planImportType)) {
            $data = [
                'type_id' => $this->type_id,
            ];
            PlanImportType::create(array_merge($validatedData, $data));
        } else {
            $this->planImportType->update($validatedData);
        }

        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }

    public function render()
    {
        return view('livewire.plan-import-type.plan-import-type-modal-edit');
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
