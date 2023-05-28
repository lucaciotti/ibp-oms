<x-wire-elements-pro::bootstrap.modal on-submit="save" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">

        {{-- VALORI STATICI --}}
        <div class="row">

            <x-adminlte-input name="aTask.ibp_cliente_ragsoc" label="Cliente" error-key="aTask.ibp_cliente_ragsoc" wire:model.lazy="aTask.ibp_cliente_ragsoc" fgroup-class="col-lg-6" disabled />
            
            <x-adminlte-input name="aTask.ibp_prodotto_tipo" label="Tipo Prodotto" error-key="aTask.ibp_prodotto_tipo" wire:model.lazy="aTask.ibp_prodotto_tipo" fgroup-class="col-lg-6" disabled/>
        
        </div>
        <div class="row">

            @php
            $model = 'aTask.ibp_data_consegna';
            $idEl = 'ibp_data_consegna';
            $value = $aTask[$idEl];
            @endphp
            <div class="form-group col-lg-6">
                <label>Data Consegna</label>
                <div class="input-group date" id="{{ $idEl }}" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#{{ $idEl }}" {{ $disabled }}>
                    <div class="input-group-append" data-target="#{{ $idEl }}" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                                $('#{{ $idEl }}').datetimepicker({
                                    autoclose: true,
                                    todayHighlight: true,
                                    locale: moment.locale('it'),
                                    format: 'L',
                                    defaultDate: moment('{{ $value }}', 'DD-MM-YYYY').toDate(),
                                });
                                // $('#{{ $idEl }}').on('changeDate', function(e){
                                $('#{{ $idEl }}').on("change.datetimepicker", ({date, oldDate}) => {
                                    console.log("New date", moment(date).format('MM/DD/YYYY'));
                                    // console.log("Old date", oldDate);
                                    @this.set('{{ $model }}', date);
                                });
                            });
            </script>

            @php
            $model = 'aTask.ibp_dt_inizio_prod';
            $idEl = 'ibp_dt_inizio_prod';
            $value = $aTask[$idEl];
            @endphp
            <div class="form-group col-lg-6">
                <label>Data Inizio Prod.</label>
                <div class="input-group date" id="{{ $idEl }}" data-target-input="nearest">
                    <input type="text" class="form-control datetimepicker-input" data-target="#{{ $idEl }}" {{ $disabled }}>
                    <div class="input-group-append" data-target="#{{ $idEl }}" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(function () {
                                $('#{{ $idEl }}').datetimepicker({
                                    autoclose: true,
                                    todayHighlight: true,
                                    locale: moment.locale('it'),
                                    format: 'L',
                                    defaultDate: moment('{{ $value }}', 'DD-MM-YYYY').toDate(),
                                });
                                // $('#{{ $idEl }}').on('changeDate', function(e){
                                $('#{{ $idEl }}').on("change.datetimepicker", ({date, oldDate}) => {
                                    console.log("New date", moment(date).format('MM/DD/YYYY'));
                                    // console.log("Old date", oldDate);
                                    @this.set('{{ $model }}', date);
                                });
                            });
            </script>

        </div>

        <hr>

        <div class="row">

        @foreach ($typeAttrs as $typeA)
            @if (!in_array(strtolower($typeA->attribute->col_name), $exclAttribute))     
                @php
                    ++$index;
                    $model = 'aTask.'.$typeA->attribute->col_name;
                    $idEl = $typeA->attribute->col_name;
                    $value = $aTask[$typeA->attribute->col_name];
                @endphp
                @if ($typeA->attribute->col_type=="date")
                <div class="form-group col-lg-4">
                    <label>{{ $typeA->attribute->label }}</label>
                    <div class="input-group date" id="{{ $idEl }}" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#{{ $idEl }}" {{ $disabled }}>
                        <div class="input-group-append" data-target="#{{ $idEl }}" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function () {
                        $('#{{ $idEl }}').datetimepicker({
                            autoclose: true,
                            todayHighlight: true,
                            locale: moment.locale('it'),
                            format: 'L',
                            defaultDate: moment('{{ $value }}', 'DD-MM-YYYY').toDate(),
                        });
                        // $('#{{ $idEl }}').on('changeDate', function(e){
                        $('#{{ $idEl }}').on("change.datetimepicker", ({date, oldDate}) => {
                            console.log("New date", date);
                            // console.log("Old date", oldDate);
                            @this.set('{{ $model }}', date);
                        });
                    });
                </script>
                @endif
                @if ($typeA->attribute->col_type=="string")
                    @if ($disabled=='disabled')
                        <x-adminlte-input name="{{ $model }}" label="{{ $typeA->attribute->label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-4" disabled/>               
                    @else
                        <x-adminlte-input name="{{ $model }}" label="{{ $typeA->attribute->label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-4"/>           
                    @endif
                @endif
                @if ($typeA->attribute->col_type=="text")
                    @if ($disabled=='disabled')
                        <x-adminlte-textarea name="{{ $model }}" label="{{ $typeA->attribute->label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-4" disabled/>           
                    @else
                        <x-adminlte-textarea name="{{ $model }}" label="{{ $typeA->attribute->label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-4"/>           
                    @endif
                @endif
                @if ($index!=0 && ($index % 3)==0)
                    </div>
                    <div class="row" cs='{{ $typeA->attribute->label }}'>
                @endif
            @endif
            
        @endforeach

        {{-- <div class="form-check">
            <input class="form-check-input" id="default" name="default" type="checkbox" wire:model.lazy="default">
            <label class="form-check-label" for="default"><strong>Imposta come predefinito</strong></label>
        </div> --}}

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="button" class="btn btn-default" onclick="Livewire.emit('slide-over.open', 'audits.audits-slide-over', {'ormClass': '{!! class_basename(get_class($task)) !!}', 'ormId': {{ $task->id }}});"><i>Ultima Modifica:</i> <span class="fa fa-history pr-1"></span><strong>{{ $task->updated_at->format('d-m-Y') }}</strong></button>
        <button type="submit" class="btn btn-primary" {{ $disabled }}>Salva</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>