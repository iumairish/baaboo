<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS (for admin) -->
@if(request()->is('admin*'))
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endif

<!-- Trix Editor (for admin ticket notes) -->
@if(request()->is('admin/tickets/*'))
<script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
@endif

<!-- Custom JS -->
<script src="{{ asset('js/app.js') }}"></script>