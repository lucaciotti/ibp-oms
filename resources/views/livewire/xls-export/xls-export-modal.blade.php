<x-wire-elements-pro::bootstrap.modal on-submit="doExport" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <x-adminlte-select name="import_type_id" label="Seleziona Tipologia Export" error-key="import_type_id"
            wire:model="import_type_id" style="text-align: center;">
            @foreach ($importTypes as $type)
            <option value='{{ $type->id }}'><strong>{{ $type->name }}</strong></option>
            @endforeach
        </x-adminlte-select>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Genera Report</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>