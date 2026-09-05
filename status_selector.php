<?php if (isset($_SESSION['user']) && ($_SESSION['access'] === 'Administrator' || $_SESSION['access'] === 'Executive')): ?>
<style>
.status-select option {
    background-color: #ffffff !important;
    color: #333333 !important;
}
</style>
<script>
jQuery(document).ready(function() {
    // Style the status selects on load
    jQuery('.status-select').each(function() {
        updateSelectStyle(jQuery(this));
    });

    // Handle status change
    jQuery(document).on('change', '.status-select', function() {
        let select = jQuery(this);
        let table = select.attr('data-table') || select.data('table');
        let id = select.attr('data-id') || select.data('id');
        let status = select.val();

        updateSelectStyle(select);

        // Send AJAX update
        jQuery.ajax({
            url: 'update_application_status.php',
            type: 'POST',
            data: { table: table, id: id, status: status },
            dataType: 'json',
            success: function(response) {
                // Success highlight flash
                select.fadeOut(100).fadeIn(100);
            },
            error: function(xhr) {
                alert('Failed to update status: ' + xhr.responseText);
            }
        });
    });

    function updateSelectStyle(select) {
        let val = (select.val() || '').toLowerCase();
        if (val === 'pending') {
            select.css({'background-color': '#fff3cd', 'color': '#856404'});
        } else if (val === 'approved') {
            select.css({'background-color': '#d4edda', 'color': '#155724'});
        } else if (val === 'denied') {
            select.css({'background-color': '#f8d7da', 'color': '#721c24'});
        }
    }
});
</script>
<?php endif; ?>
