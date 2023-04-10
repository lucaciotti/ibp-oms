<?php

namespace App\Http\Livewire\PlanImportType;

use WireElements\Pro\Components\Modal\Modal;

class PlanImportTypeAttributeModal extends Modal
{
    public $title;

    public $type_id;
    public $import_type_id;

    public function mount($import_type_id, $type_id){
        $this->import_type_id = $import_type_id;
        $this->type_id = $type_id;

        $this->title = 'Configura colonne di Import';
    }

    public function render()
    {
        return view('livewire.plan-import-type.plan-import-type-attribute-modal');
    }

    public static function behavior(): array
    {
        return [
            // Close the modal if the escape key is pressed
            'close-on-escape' => true,
            // Close the modal if someone clicks outside the modal
            'close-on-backdrop-click' => true,
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
            'size' => '7xl',
        ];
    }
}
