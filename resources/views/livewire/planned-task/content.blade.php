<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>

        <div class="card">
            <div class="card-body">
                <div style="">
                    <x-adminlte-select name="plantype_id" label="Tipo di Pianificazione" error-key="plantype_id" wire:model="plantype_id" style="text-align: center;">
                        @foreach ($plantypes as $plantype)
                            <option value='{{ $plantype->id }}'><strong>{{ $plantype->name }}</strong> - {{ $plantype->description }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lista pianificazioni</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div style="">
                    <livewire:planned-task.planned-task-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <button class="btn btn-outline-success btn-block"
                    onclick="Livewire.emit('modal.open', 'plan-import-file.plan-import-file-modal');">
                    <span class="fa fa-edit"></span> Importa Pianificazioni
                </button>
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>