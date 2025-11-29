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
                    <td><small>{{ $ticket->email }}</small></td>
                    <td>
                        <div class="text-truncate" style="max-width: 300px;">
                            {{ $ticket->subject }}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-info">{{ $ticket->type->value }}</span>
                    </td>
                    <td>
                        @include('partials.admin.tickets.status-badge', ['status' => $ticket->status])
                    </td>
                    <td>
                        <small>{{ $ticket->created_at->format('M d, Y H:i') }}</small>
                    </td>
                    <td>
                        <a href="{{ route('admin.tickets.show', ['id' => $ticket->id, 'connection' => $ticket->getConnectionName()]) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>