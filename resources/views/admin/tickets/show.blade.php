@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id)

@section('page-content')
<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to All Tickets
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        @include('partials.admin.tickets.detail-card', ['ticket' => $ticket, 'connection' => $connection])
        
        @include('partials.admin.tickets.notes-card', ['ticket' => $ticket])
    </div>

    <div class="col-lg-4">
        @include('partials.admin.tickets.add-note-form', ['ticket' => $ticket, 'connection' => $connection])
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Disable file attachments in Trix
    document.addEventListener("trix-file-accept", function(e) {
        e.preventDefault();
    });
</script>
@endpush