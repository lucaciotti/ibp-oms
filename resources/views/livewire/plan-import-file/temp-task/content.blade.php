<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Righe File [<strong>{{ $file_imported->filename }}</strong>]</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" title="Font Size Smaller" id="card-text-size">
                        <i class="fas fa-text-height fa-xs"></i>
                    </button>
                    <button type="button" class="btn btn-tool" title="Toggle fullscreen" id="card-fullscreen">
                        <i class="fas fa-expand-alt"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div style="">
                    <livewire:plan-import-file.temp-task.planned-temp-task-table file_id='{{ $file_id }}' type_id='{{ $type_id }}'/>
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                {{-- <button class="btn btn-success btn-block"
                    onclick="Livewire.emit('modal.open', 'plan-import-file.plan-import-file-modal');">
                    <span class="fa fa-file-excel"></span> Importa Pianificazioni
                </button> --}}
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>