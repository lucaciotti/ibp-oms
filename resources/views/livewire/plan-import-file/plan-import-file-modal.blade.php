<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        {{-- <x-adminlte-input-file name="file" label="Carica file" placeholder="{{ $file_placeolder }}" error-key="file" wire:model="file" />
        @error('file_extension') <span class="text-danger">{{ $message }}</span> @enderror --}}
        <div class="form-group">
            <label for="file">Carica Excel</label>
            <input id="file" type="file" class="btn btn-default w-100" wire:model="file">
            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
            @error('file_extension') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div wire:loading wire:target="file">Uploading...</div>

        <x-adminlte-select name="type_id" label="Tipo di Pianificazione" error-key="type_id" wire:model="type_id"
            style="text-align: center;">
            @foreach ($planTypes as $plantype)
            <option value='{{ $plantype->id }}'><strong>{{ $plantype->name }}</strong> - {{ $plantype->description }}</option>
            @endforeach
        </x-adminlte-select>

        <x-adminlte-select name="import_type_id" label="Tipo di Import" error-key="import_type_id" wire:model="import_type_id"
            style="text-align: center;" fgroup-class="mb-0">
            @if ($planImportTypes)
            @foreach ($planImportTypes as $planimporttype)
                <option value='{{ $planimporttype->id }}'><strong>{{ $planimporttype->name }}</strong> - {{ $planimporttype->description }}</option>
            @endforeach
            @else
                <option value=''>...seleziona un Tipo di Pianificazione</option>
            @endif
        </x-adminlte-select>
        <div style="font-size: smaller; margin-bottom: 1rem;"><em>Definisce l'ordine delle colonne da importare</em></div>

        <div class="form-check">
            <input class="form-check-input" id="force_import" name="force_import" type="checkbox" wire:model="force_import">
            <label class="form-check-label" for="force_import"><strong>Forza importazione</strong><em style="font-size: smaller"> - Sovrascrive righe esistenti</em></label>
            @error('force_import') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Importa</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>