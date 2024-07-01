jQuery( document ).ready( function( $ ) {

	$('#ph_fedex_hold_at_locations').data('select2', '1');

	$( document.body ).on( 'change', 'select.country_to_state, input.country_to_state,input#billing_city,input#billing_state,select#billing_state,input#billing_postcode', function() {
		$('#ph_fedex_hold_at_locations').val('');
	});

	jQuery('#billing_country').on('change', function(){

		shipping_country = jQuery('#billing_country').val();
		
		if ( !jQuery('#ship-to-different-address-checkbox').is(":checked") ) {

			ph_toggle_fedex_hold_at_location_option( shipping_country );
		}
	});

	jQuery('#ship-to-different-address-checkbox').on('change', function(){

		if ( jQuery('#ship-to-different-address-checkbox').is(":checked") ) {

			shipping_country = jQuery('#shipping_country').val();
		} else {

			shipping_country = jQuery('#billing_country').val();
		}

		ph_toggle_fedex_hold_at_location_option( shipping_country );
	});

	jQuery('#shipping_country').on('change', function(){

		shipping_country = jQuery('#shipping_country').val();
		
		ph_toggle_fedex_hold_at_location_option( shipping_country );
	});

	ph_toggle_fedex_hold_at_location_option( jQuery('#billing_country').val() );
});


/**
 * Toggle HAL option based on Method Available Countries.
 *
 * @param string $shipping_country
 */
function ph_toggle_fedex_hold_at_location_option( shipping_country ) {

	if ( fedex_method_available_countries.availability == 'all' || jQuery.inArray( shipping_country, fedex_method_available_countries.countries ) != -1 ) {

		jQuery('#ph_fedex_hold_at_locations').closest('p').show();
	} else {

		jQuery('#ph_fedex_hold_at_locations').closest('p').hide();
	}
}