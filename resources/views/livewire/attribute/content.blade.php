<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista Attributi</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div style="">
                    <livewire:attribute.attribute-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button class="btn btn-outline-success btn-block"
                    onclick="Livewire.emit('modal.open', 'attribute.attribute-modal-edit');">
                    <span class="fa fa-edit"></span> Crea nuovo attributo
                </button>
                <hr>
                @if (!$syncJob)
                    <button class="btn btn-outline-warning btn-block" wire:click='fetchAttributeFromTasksTable'>
                        <span class="fa fa-undo"></span> Aggiorna lista attributi
                    </button>
                @else
                    <button class="btn btn-warning btn-block" disabled>
                        <span class="fa fa-spinner fa-pulse fa-fw"></span> Loading...
                    </button>
                @endif
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>