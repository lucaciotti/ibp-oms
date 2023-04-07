<?php

namespace App\Http\Livewire\PlanType;

use WireElements\Pro\Components\Modal\Modal;
use App\Models\PlanType;

class PlanTypeModalEdit extends Modal
{
    public $planType;
    public $title;
    public $mode;

    public $name;
    public $description;

    protected $rules = [
        'name' => 'required',
        'description' => 'required',
    ];

    public function mount($id = null)
    {
        if (empty($id)) {
            $this->mode = 'insert';
            $this->title = 'Nuova Tipologia di pianificaizone';
        } else {
            $this->mode = 'edit';
            $this->title = 'Modifica Tipologia di pianificazione [' . $id . ']';
            $this->planType = PlanType::find($id);
            $this->name = $this->planType->name;
            $this->description = $this->planType->description;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $validatedData = $this->validate();
        if (empty($this->planType)) {
            PlanType::create($validatedData);
        } else {
            $this->planType->update($validatedData);
        }

        // $this->emit('refreshDatatable');
        $this->close(
            andEmit: [
                'refreshDatatable'
            ]
        );
    }




    public function render()
    {
        return view('livewire.modal.plan-type-edit');
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
