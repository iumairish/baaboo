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