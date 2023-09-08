<x-wire-elements-pro::bootstrap.modal on-submit="delete" :content-padding="true">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <div class="modal-body">

        <div class="row justify-content-center">
            <h4 class="text-danger text-center text-bold">Attenzione!</h4>
            <h5 class="text-danger text-center">
                Si è sicuridi cancellare la Pianificazioni Selezionate?
                <br>
                L'operazione non potrà essere annullata!
            </h5>
        </div>
        
    </div>

        <x-slot name="buttons">
            <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Annulla</button>
            <button type="submit" class="btn btn-danger">Sì, Elimina</button>
        </x-slot>
</x-wire-elements-pro::bootstrap.modal>