<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista File Importati</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div style="">
                    <livewire:plan-import-file.plan-import-file-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button class="btn btn-success btn-block"
                    onclick="Livewire.emit('modal.open', 'plan-import-file.plan-import-file-modal');">
                    <span class="fa fa-file-excel"></span> Importa Pianificazioni
                </button>
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>
