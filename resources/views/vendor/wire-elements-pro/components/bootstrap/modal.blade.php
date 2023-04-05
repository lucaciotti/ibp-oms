@props(['contentPadding' => true, 'onSubmit' => null])
<form wire:submit.prevent="{{ $onSubmit }}">
    <div class="modal-header">
        @if($title ?? false)
            <h5 class="modal-title">{{ $title }}</h5>
        @endif
        <button type="button" class="close" wire:click="$emit('modal.close')" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{-- <button type="button" class="btn btn-close" wire:click="$emit('modal.close')" aria-label="Close"></button> --}}
    </div>
    <div @class(['modal-body' , 'px-0 py-0' => !$contentPadding])>
        {{ $slot }}
    </div>
    @if($buttons ?? false)
        <div class="modal-footer">
            {{ $buttons }}
        </div>
    @endif
</form>
