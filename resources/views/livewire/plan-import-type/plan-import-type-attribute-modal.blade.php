<x-wire-elements-pro::bootstrap.modal :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <div class="row">
        <div class="col-lg-6">
            <livewire:plan-import-type.attribute-table type_id='{{ $type_id }}' import_type_id='{{ $import_type_id }}' />
        </div>

        <div class="col-lg-6">
            <livewire:plan-import-type.plan-import-type-attribute-table import_type_id='{{ $import_type_id }}' />
        </div>
        </div>

        {{-- <x-adminlte-input name="name" label="Nome Tipo Import" placeholder="Nome Tipo Import" error-key="name"
            wire:model="name" />

        <x-adminlte-input name="description" label="Descrizione" placeholder="Descrizione" error-key="description"
            wire:model="description" />

        <div class="form-check">
            <input class="form-check-input" id="default" name="default" type="checkbox" wire:model="default">
            <label class="form-check-label" for="default"><strong>Imposta come predefinito</strong></label>
        </div> --}}

    </div>
</x-wire-elements-pro::bootstrap.modal>