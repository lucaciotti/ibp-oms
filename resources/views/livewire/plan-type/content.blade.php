<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista Pianificazioni</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div style="">
                    <livewire:plan-type.plan-type-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                @permission('config-create')
                <button class="btn btn-outline-success btn-block"
                    onclick="Livewire.emit('modal.open', 'plan-type.plan-type-modal-edit');">
                    <span class="fa fa-edit"></span> Crea nuovo piano
                </button>
                @endpermission
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>