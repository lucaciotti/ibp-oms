<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <x-adminlte-input name="label" label="Nome Attributo" placeholder="Nome Attributo" error-key="label" wire:model.lazy="label" />
        
        @if($mode=='insert')
        <x-adminlte-select name="col_type" label="Tipo Attributo" error-key="col_type" wire:model.lazy="col_type">
            <option value=''></option>
            <option value='string'>Testo</option>
            <option value='integer'>Numerico semplice</option>
            <option value='float'>Numerico decimale</option>
            <option value='date'>Data</option>
            <option value='boolean'>Vero/Falso</option>
            <option value='text'>Testo esteso (note)</option>
        </x-adminlte-select>
        @else
        <x-adminlte-select name="col_type" label="Tipo Attributo" error-key="col_type" wire:model.lazy="col_type" disabled>
            <option value=''></option>
            <option value='string'>Testo</option>
            <option value='integer'>Numerico semplice</option>
            <option value='float'>Numerico decimale</option>
            <option value='date'>Data</option>
            <option value='boolean'>Vero/Falso</option>
            <option value='text'>Testo esteso (note)</option>
        </x-adminlte-select>
        @endif

        <div class="form-check">
            <input class="form-check-input" id="hidden_in_view" name="hidden_in_view" type="checkbox" wire:model.lazy="hidden_in_view">
            <label class="form-check-label" for="hidden_in_view"><strong>Nascondi di Default in visualizzazione Tabella</strong></label>
        </div>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Salva</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>