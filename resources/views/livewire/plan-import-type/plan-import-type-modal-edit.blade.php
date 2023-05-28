<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <x-adminlte-input name="name" label="Nome Tipo Import" placeholder="Nome Tipo Import" error-key="name" wire:model.lazy="name" />

        <x-adminlte-input name="description" label="Descrizione" placeholder="Descrizione" error-key="description" wire:model.lazy="description" />
        
        <div class="row">
            <div class="col-lg-6">
                <label class="mb-0">Utilizzabile in:</label>
                <div class="form-check">
                    <input class="form-check-input" id="use_in_export" name="use_in_import" type="checkbox" wire:model.lazy="use_in_import">
                    <label class="form-check-label" for="use_in_import"><strong>Import</strong></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="use_in_export" name="use_in_export" type="checkbox" wire:model.lazy="use_in_export">
                    <label class="form-check-label" for="use_in_export"><strong>Export</strong></label>
                </div>
            </div>

            <div class="col-lg-6">
                <label class="mb-0">Imposta come formato predefinito in:</label>
                <div class="form-check">
                    <input class="form-check-input" id="default_export" name="default_import" type="checkbox" wire:model.lazy="default_import">
                    <label class="form-check-label" for="default_import"><strong>Import</strong></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="default_export" name="default_export" type="checkbox" wire:model.lazy="default_export">
                    <label class="form-check-label" for="default_export"><strong>Export</strong></label>
                </div>
            </div>
        </div>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Salva</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>