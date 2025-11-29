<div class="card mb-4">
    <div class="ticket-header">
        <h3 class="mb-2">Ticket #{{ $ticket->id }}</h3>
        <h5 class="mb-0">{{ $ticket->subject }}</h5>
    </div>
    <div class="card-body">
        @include('partials.admin.tickets.info-row', [
            'label' => 'Status',
            'value' => view('partials.admin.tickets.status-badge', ['status' => $ticket->status])
        ])

        @include('partials.admin.tickets.info-row', [
            'label' => 'Type',
            'value' => '<span class="badge bg-info">' . $ticket->type . '</span>'
        ])

        @include('partials.admin.tickets.info-row', [
            'label' => 'Customer Name',
            'value' => $ticket->name
        ])

        @include('partials.admin.tickets.info-row', [
            'label' => 'Email',
            'value' => '<a href="mailto:' . $ticket->email . '">' . $ticket->email . '</a>'
        ])

        @include('partials.admin.tickets.info-row', [
            'label' => 'Created',
            'value' => $ticket->created_at->format('F d, Y \a\t H:i') . ' <small class="text-muted">(' . $ticket->created_at->diffForHumans() . ')</small>'
        ])

        @include('partials.admin.tickets.info-row', [
            'label' => 'Database',
            'value' => '<code>' . $connection . '</code>'
        ])

        <div class="mt-4">
            <h5 class="mb-3"><i class="fas fa-align-left me-2"></i>Description</h5>
            <div class="p-3 bg-light rounded">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>
    </div>
</div>