<x-wire-elements-pro::bootstrap.modal on-submit="exitReport" :content-padding="false">
    <x-slot name="title">
        <h4 class="modal-title">{{ $title }}</h4>
    </x-slot>

    <!-- No padding will be applied because the component attribute "content-padding" is set to false -->
    <div class="modal-body pdf">
        {{-- <embed src="{{ asset('storage/tmp_pdf/'.$pdfReport) }}" frameborder="0" width="100%" style="height: calc(100vh - 110px);"> --}}
        <embed src="{{ asset('storage/tmp_pdf/'.$pdfReport) }}" frameborder="0" width="100%" style="height: calc(100vh - 110px);">
    </div>

    <x-slot name="buttons">
        {{-- <button type="button" class="btn btn-default float-left" wire:click="$emit('modal.close')">Cancella</button> --}}
        <div></div>
        <a class="btn btn-warning float-right" target="_blank" href="{{ asset('storage/tmp_pdf/'.$pdfReport) }}">Apri PDF</a>
        <button type="submit" class="btn btn-primary float-right">Chiudi</button>
    </x-slot>
    <style>    
        .pdf {
            padding: 0rem;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('.pdf').parent().removeAttr("style");
            $('.pdf').parent().parent().children('.modal-header').children('.close').prop("disabled", true);
            $('.pdf').parent().parent().children('.modal-header').children('.close').children().hide();
            // $('.close').prop("disabled", true);
        });
        // const isFirefox = navigator.userAgent.toLowerCase().includes('firefox');
        
        // if (isFirefox) {
        // console.log("Your browser is Firefox");
        // } else {
        // console.log("Your browser is not Firefox");
        // }
    </script>
</x-wire-elements-pro::bootstrap.modal>