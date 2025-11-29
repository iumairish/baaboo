<div class="card sticky-top" style="top: 20px;">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-plus-circle me-2"></i>
            Add Note
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.tickets.add-note', $ticket->id) }}" method="POST">
            @csrf
            <input type="hidden" name="connection" value="{{ $connection }}">
            
            <div class="mb-3">
                <label class="form-label">Note Content</label>
                <input id="note-input" type="hidden" name="note" value="{{ old('note') }}">
                <trix-editor input="note-input"></trix-editor>
                @error('note')
                    <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                @enderror
                <small class="text-muted">This will update the ticket status to "Noted"</small>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-save me-2"></i> Save Note & Update Status
            </button>
        </form>
    </div>
</div>