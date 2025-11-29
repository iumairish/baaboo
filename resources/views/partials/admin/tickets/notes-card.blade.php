<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-sticky-note me-2"></i>
            Admin Notes ({{ $ticket->notes->count() }})
        </h5>
    </div>
    <div class="card-body">
        @if($ticket->notes->count() > 0)
            @foreach($ticket->notes as $note)
                <div class="note-item">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <strong>
                            <i class="fas fa-user-shield me-2"></i>Admin
                        </strong>
                        <small class="text-muted">
                            {{ $note->created_at->format('M d, Y H:i') }}
                        </small>
                    </div>
                    <div>{!! $note->note !!}</div>
                </div>
            @endforeach
        @else
            <div class="text-center text-muted py-4">
                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                <p>No notes added yet.</p>
            </div>
        @endif
    </div>
</div>