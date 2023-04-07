@props(['contentPadding' => true, 'onSubmit' => null])
<form wire:submit.prevent="{{ $onSubmit }}">
    <div class="modal-header pt-reduced pb-reduced" style="background: #D0D0D0; padding-top: 10px; padding-bottom: 10px">
        @if($title ?? false)
            <h6 class="modal-title">{{ $title }}</h6>
        @endif
        <button type="button" class="close" wire:click="$emit('modal.close')" aria-label="Close">
            <span aria-hidden="true"><span class="fa fa-times pt-1" style="color:#505050"></span></span>
        </button>
        {{-- <button type="button" class="btn btn-close" wire:click="$emit('modal.close')" aria-label="Close"></button> --}}
    </div>
    <div @class(['modal-body' , 'px-0 py-0' => !$contentPadding])>
        {{ $slot }}
    </div>
    @if($buttons ?? false)
        <div class="modal-footer pt-reduced pb-reduced justify-content-between" style="background: #F0F0F0; padding-top: 10px; padding-bottom: 10px">
            {{ $buttons }}
        </div>
    @endif
</form>
