<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        <x-adminlte-input name="filename" label="Nome File" placeholder="Nome File" error-key="filename" wire:model="filename" disabled/>

        <x-adminlte-select name="type_id" label="Tipo di Pianificazione" error-key="type_id" wire:model="type_id"
            style="text-align: center;">
            @foreach ($planTypes as $plantype)
            <option value='{{ $plantype->id }}'><strong>{{ $plantype->name }}</strong> - {{ $plantype->description }}
            </option>
            @endforeach
        </x-adminlte-select>

        <x-adminlte-select name="import_type_id" label="Tipo di Import" error-key="import_type_id"
            wire:model="import_type_id" style="text-align: center;" fgroup-class="mb-0">
            @if ($planImportTypes)
            @foreach ($planImportTypes as $planimporttype)
            <option value='{{ $planimporttype->id }}'><strong>{{ $planimporttype->name }}</strong> - {{
                $planimporttype->description }}</option>
            @endforeach
            @else
            <option value=''>...seleziona un Tipo di Pianificazione</option>
            @endif
        </x-adminlte-select>
        <div style="font-size: smaller; margin-bottom: 1rem;"><em>Definisce l'ordine delle colonne da importare</em>
        </div>

        <div class="form-check">
            <input class="form-check-input" id="force_import" name="force_import" type="checkbox" wire:model="force_import">
            <label class="form-check-label" for="force_import"><strong>Forza importazione</strong><em
                    style="font-size: smaller"> - Sovrascrive righe esistenti</em></label>
            @error('force_import') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <hr>
        <button type="button" class="btn btn-outline-danger btn-block" wire:click="deleteConfirmation">Cancella File Import</button>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Salva</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>