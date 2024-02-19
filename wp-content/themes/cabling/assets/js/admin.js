( function( $ ) {
	/*console.log('start');
	$('.wp-admin').on('click', '.cabling_verify', function(event) {
		event.preventDefault();
		console.log('good');
		if ( confirm('Are you sure to verify this user?') ) {

		}
	});*/
} )( jQuery );

function cabling_verify_user(e){
	var j = jQuery.noConflict();

	if ( confirm('Are you sure to verify this user?') ) {

		j.ajax({
			url: ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'cabling_verify_user_ajax',
				data: j(e).data('user')
			},
			success: function(data, textStatus, xhr) {
		        //console.log(data);
		        alert(data.mess);
		        if ( !data.error ) {
		        	j(e).append('<strong>Verified</strong>');
		        	j(e).remove();
		        }		        
		    },
		})
		.fail(function() {
			console.log("error");
		});
	}

	return false;	
}