<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <x-adminlte-input name="name" label="Nome Tipo Import" placeholder="Nome Tipo Import" error-key="name" wire:model="name" />

        <x-adminlte-input name="description" label="Descrizione" placeholder="Descrizione" error-key="description" wire:model="description" />

        <div class="form-check">
            <input class="form-check-input" id="default" name="default" type="checkbox" wire:model="default">
            <label class="form-check-label" for="default"><strong>Imposta come predefinito</strong></label>
        </div>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Salva</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>