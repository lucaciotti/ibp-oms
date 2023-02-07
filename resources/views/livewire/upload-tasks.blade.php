
<form wire:submit.prevent="save">
    <div class="form-group" style="margin-bottom:5px;">
        <label for="task">File:</label>
        <input type="file" class="form-control" id="task" placeholder="File Tasks" wire:model="task">
        @error('task') <span class="error">{{ $message }}</span> @enderror
    <div>

    <div>
        <button class="btn btn-success btn-sm btn-block" style="margin-top:10px;" type="submit">Importa</button>
    </div>
</form>
