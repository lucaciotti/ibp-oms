<x-wire-elements-pro::bootstrap.modal on-submit="doExport" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body">
        <div class="row">

            <div class="col-lg-6">
                <h6 class="text-center text-bold">Filtri</h6>
                <hr>
                
                <div class="row">
                    @foreach ($filters as $key => $filter)
                        @php
                            ++$index;
                            $model = 'filters.'.$key.'.value';
                            $idEl = $key;
                            $type = $filter['type'];
                            $label = $filter['label'];
                            $value = $filter['value'];
                            $valuelist = $filter['valuelist'];
                            $disabled = false;
                            if ($type=='divider') {
                                --$index;
                            }
                        @endphp
                        @if ($type=='divider')
                            </div>
                            <hr>
                            <div class="row" cs='{{ $label }}'>
                        @endif
                        @if ($type=="date")
                        <div class="form-group col-lg-6">
                            <label>{{ $label }}</label>
                            <div class="mb-3 mb-md-0 input-group">
                                <input error-key="{{ $model }}" wire:model.lazy="{{ $model }}" id="{{ $idEl }}" type="date"
                                class="form-control"
                                />
                            </div>
                        </div>
                        @endif
                        @if ($type=="string")
                            @if ($disabled)
                                <x-adminlte-input name="{{ $model }}" label="{{ $label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-6" disabled/>               
                            @else
                                <x-adminlte-input name="{{ $model }}" label="{{ $label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-6"/>           
                            @endif
                        @endif
                        @if ($type=="text")
                            @if ($disabled)
                                <x-adminlte-textarea name="{{ $model }}" label="{{ $label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-6" disabled/>           
                            @else
                                <x-adminlte-textarea name="{{ $model }}" label="{{ $label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-6"/>           
                            @endif
                        @endif
                        @if ($type=="choice")
                            <x-adminlte-select name="{{ $model }}" label="{{ $label }}" error-key="{{ $model }}" wire:model.lazy="{{ $model }}" fgroup-class="col-lg-6" style="text-align: center;">
                                @foreach ($valuelist as $item)
                                    <option value='{{ $item['value']}}'><strong>{{ $item['label'] }}</strong></option>
                                @endforeach
                            </x-adminlte-select>
                        @endif
                        @if ($index!=0 && ($index % 2)==0)
                            </div>
                            <div class="row" cs='{{ $label }}'>
                        @endif
                    @endforeach
                </div>
                @if($error_filter!='')
                
                    <h6 class="text-bold text-danger text-center">{{ $error_filter }}</h6>
                
                @endif
                
            </div>
            
            <div class="col-lg-6">

                <h6 class="text-center text-bold">Seleziona Tipologie di Export</h6>
                <hr>

                @foreach ($eachPlanConfs as $planConf)
                @php
                    $planId = strval($planConf['planType']['id']);
                @endphp
                <div class="form-check">
                <input class="form-check-input" id="select_{{ $planId }}" name="select_{{ $planId }}" type="checkbox" wire:model="eachPlanConfs.{{ $planId }}.selected">
                <label class="form-check-label" for="select_{{ $planId }}"><strong>{{ $planConf['planType']['name'] }}</strong></label>
                @error('select_{{ $planId }}') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <x-adminlte-select name="import_type_id" error-key="import_type_id" wire:model.lazy="eachPlanConfs.{{ $planId }}.xlsTypeId" style="text-align: center;">
                        @foreach ($planConf['xlsTypes'] as $type)
                        <option value='{{ $type['id']}}'><strong>{{ $type['name'] }}</strong></option>
                        @endforeach
                    </x-adminlte-select>

                @endforeach
            </div>
        </div>

    </div>

    <x-slot name="buttons">
        <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button>
        <button type="submit" class="btn btn-primary">Download Xls</button>
    </x-slot>
</x-wire-elements-pro::bootstrap.modal>