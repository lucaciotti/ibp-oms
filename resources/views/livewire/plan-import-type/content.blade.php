<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista Tipologie Import</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div style="">
                    <livewire:plan-import-type.plan-import-type-table type_id='{{ $type_id }}'/>
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button class="btn btn-outline-success btn-block"
                    onclick="Livewire.emit('modal.open', 'plan-import-type.plan-import-type-modal-edit', {'type_id': {{ $type_id }}});">
                    <span class="fa fa-edit"></span> Crea nuovo import
                </button>
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>