<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<!-- DataTables CSS (for admin) -->
@if(request()->is('admin*'))
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endif

<!-- Trix Editor (for admin ticket notes) -->
@if(request()->is('admin/tickets/*'))
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
@endif

<!-- Custom CSS -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">