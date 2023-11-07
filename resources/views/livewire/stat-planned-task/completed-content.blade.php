<x-layouts.dynamic-content collapsed='{{ $collapsed }}'>
    <x-slot:content>

        <div class="card">
            <div class="card-body">
                <div style="">
                    <x-adminlte-select name="plantype_id" label="Tipo di Pianificazione" error-key="plantype_id" wire:model="plantype_id" style="text-align: center;" class="text-bold">
                        @foreach ($plantypes as $plantype)
                            <option value='{{ $plantype->id }}'><strong>{{ $plantype->name }}</strong> - {{ $plantype->description }}</option>
                        @endforeach
                    </x-adminlte-select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filtri</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <x-adminlte-select name="month" label="Mese di riferimento" error-key="month" wire:model="month" style="text-align: center;" class="text-bold" fgroup-class="col-lg-4">
                        @foreach ($months as $key => $value)
                            <option value='{{ $key }}'><strong>{{ $value }}</strong></option>
                        @endforeach
                    </x-adminlte-select>
            
                    <x-adminlte-select name="completed" label="Completati" error-key="completed" wire:model="completed" style="text-align: center;" class="text-bold" fgroup-class="col-lg-4">
                        @foreach ($completed_opt as $key => $value)
                            <option value='{{ $key }}'><strong>{{ $value }}</strong></option>
                        @endforeach
                    </x-adminlte-select>

                    <x-adminlte-select name="datetype" label="Data di aggregazione" error-key="datetype" wire:model="datetype" style="text-align: center;" class="text-bold" fgroup-class="col-lg-4">
                        @foreach ($datetypes as $key => $value)
                        <option value='{{ $key }}'><strong>{{ $value }}</strong></option>
                        @endforeach
                    </x-adminlte-select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistica Pianificazioni - Numero macchine per settimana</h3>
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
                @if ($refresh_table)
                <div class="text-danger text-bold text-center pt-2 pb-2" style="background-color: lightgrey; font-size:medium;">
                    Attenzione: Nuove Pianificazioni Importate! 
                    <br> 
                    <button class="btn btn-sm btn-outline-danger" onclick="Livewire.emit('refreshDatatable');" >Aggiorna la tabella</button>
                </div>
                @endif
                <div style="">
                    <livewire:stat-planned-task.completed-pln-tsk-table />
                </div>
            </div>
        </div>
    </x-slot:content>

    <x-slot:extraContent>
        <div class="card">
            <div class="card-body">
                <a type="button" class="btn btn-primary btn-block" href="exportxls_stat_plntask" target="_blank">
                    <span class="fa fa-download"></span> Excel Statistica con filtri attuali
            </a>
            </div>
        </div>
    </x-slot:extraContent>

</x-layouts.dynamic-content>