<x-wire-elements-pro::bootstrap.modal on-submit="doReport" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <x-adminlte-select name="selectedReport" label="Seleziona Report" error-key="selectedReport" wire:model.lazy="selectedReport"
            style="text-align: center;">
            @foreach ($reports as $key => $value)
            <option value='{{ $key }}'><strong>{{ $value }}</strong></option>
            @endforeach
        </x-adminlte-select>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Genera Report</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>