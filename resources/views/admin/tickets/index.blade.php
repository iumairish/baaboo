@extends('admin.layouts.app')

@section('title', 'All Tickets')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-inbox me-2"></i>
                    All Support Tickets
                </h4>
                <span class="badge bg-primary">{{ $tickets->count() }} Total</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ticketsTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td><strong>#{{ $ticket->id }}</strong></td>
                                    <td>{{ $ticket->name }}</td>
                                    <td>
                                        <small>{{ $ticket->email }}</small>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;">
                                            {{ $ticket->subject }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $ticket->type }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($ticket->status) {
                                                'open' => 'bg-primary',
                                                'noted' => 'bg-info',
                                                'in_progress' => 'bg-warning',
                                                'resolved' => 'bg-success',
                                                'closed' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ $ticket->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <a 
                                            href="{{ route('admin.tickets.show', ['id' => $ticket->id, 'connection' => $ticket->getConnectionName()]) }}" 
                                            class="btn btn-sm btn-primary"
                                        >
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#ticketsTable').DataTable({
            order: [[6, 'desc']], // Sort by created date
            pageLength: 25,
            responsive: true,
            language: {
                search: "Search tickets:",
                lengthMenu: "Show _MENU_ tickets per page",
                info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                infoEmpty: "No tickets available",
                infoFiltered: "(filtered from _MAX_ total tickets)"
            },
            columnDefs: [
                { orderable: false, targets: 7 } // Disable sorting on action column
            ]
        });
    });
</script>
@endpush