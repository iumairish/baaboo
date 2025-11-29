@php
    $statusClass = match($status) {
        'open' => 'bg-primary',
        'noted' => 'bg-info',
        'in_progress' => 'bg-warning',
        'resolved' => 'bg-success',
        'closed' => 'bg-secondary',
        default => 'bg-secondary'
    };
@endphp
<span class="badge {{ $statusClass }}">
    {{ $status }}
</span>