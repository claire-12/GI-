(function ($) {
	$('.informed-log').on('click', '.delete-row', function () {
		const logId = $(this).data('log-id');
		if (confirm('Are you sure you want to delete this row?')) {
            $(this).text('Deleting...');
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'delete_user_informed_row',
                    log_id: logId
                },
                success: function (response) {
                    if (response.success === true) {
                        window.location.reload();
                    }
                }
            });
        }
    });
})(jQuery);
