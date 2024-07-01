jQuery(function($){
	xa_fedex_show_selected_tab($(".tab_general"),"general");
	$(".tab_general").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"general");
	});
	$(".tab_rates").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"rates");
	});
	$(".tab_labels").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"label");
	});
	$(".tab_commercial_invoice").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"commercial_invoice");
	});
	$(".tab_special_services").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"special_services");
	});
	$(".tab_packaging").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"packaging");
	});
	$(".tab_pickup").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"pickup");
	});
	$(".tab_freight").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"freight");
	});
	$(".tab_advanced").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"advanced");
	});
	$(".tab_help_and_support").on("click",function(){
		return xa_fedex_show_selected_tab($(this),"help_and_support");
	});

	function xa_fedex_show_selected_tab($element,$tab)
	{	
		$(".ph-fedex-tab").removeClass("nav-tab-active");
		$element.addClass("nav-tab-active");
			   
		$(".fedex_rates_tab").closest("tr,h3").hide();
		$(".fedex_rates_tab").next("p").hide();

		$(".fedex_general_tab").closest("tr,h3").hide();
		$(".fedex_general_tab").next("p").hide();

		$(".fedex_label_tab").closest("tr,h3").hide();
		$(".fedex_label_tab").next("p").hide();

		$(".fedex_commercial_invoice_tab").closest("tr,h3").hide();
		$(".fedex_commercial_invoice_tab").next("p").hide();

		$(".fedex_special_services_tab").closest("tr,h3").hide();
		$(".fedex_special_services_tab").next("p").hide();

		$(".fedex_packaging_tab").closest("tr,h3").hide();
		$(".fedex_packaging_tab").next("p").hide();

		$(".fedex_pickup_tab").closest("tr,h3").hide();
		$(".fedex_pickup_tab").next("p").hide();

		$(".fedex_freight_tab").closest("tr,h3").hide();
		$(".fedex_freight_tab").next("p").hide();

		$(".fedex_advanced_tab").closest("tr,h3").hide();
		$(".fedex_advanced_tab").next("p").hide();

		$(".fedex_help_and_support_tab").closest("tr,h3").hide();
		$(".fedex_help_and_support_tab").next("p").hide();

		$(".fedex_"+$tab+"_tab").closest("tr,h3").show();
		$(".fedex_"+$tab+"_tab").next("p").show();

		if( $tab == 'label' ){
			wf_fedex_return_label_options();
			wf_fedex_custom_shipment_message();
			wf_fedex_automatic_label_generation();
			ph_fedex_auto_label_trigger();
			ph_fedex_toggle_doc_tab();
			ph_fedex_toggle_csb_shipments();
			ph_fedex_toggle_label_show_browser();
			ph_toggle_zpl_content_in_email();
			ph_toggle_email_content();
		}
		if( $tab == 'general' ){

			// Hide newly registered API credentials
			jQuery('#woocommerce_wf_fedex_woocommerce_shipping_client_credentials').closest('tr').hide();
			jQuery('#woocommerce_wf_fedex_woocommerce_shipping_client_license_hash').closest('tr').hide();


			var phFedexClientCredentials = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_client_credentials').val();
			var phFedexClientLicenseHash = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_client_license_hash').val();

			if( (phFedexClientCredentials != 'undefined' && phFedexClientCredentials != '') && (phFedexClientLicenseHash != 'undefined' && phFedexClientLicenseHash != '') ) {

				jQuery('#woocommerce_wf_fedex_woocommerce_shipping_account_number').closest('tr').hide();
				jQuery('#woocommerce_wf_fedex_woocommerce_shipping_meter_number').closest('tr').hide();
				jQuery('#woocommerce_wf_fedex_woocommerce_shipping_meter_number').closest('tr').hide();
				jQuery('#woocommerce_wf_fedex_woocommerce_shipping_api_pass').closest('tr').hide();
				jQuery('#woocommerce_wf_fedex_woocommerce_shipping_api_key').closest('tr').hide();
				jQuery('#woocommerce_wf_fedex_woocommerce_shipping_production').closest('tr').hide();
				$('#xa_fedex_validate_credentials').closest('tr').hide();
			}

			xa_fedex_payment_type_options();
			ph_fedex_toggle_alt_return_address();
			ph_fedex_toggle_alt_return_address_as_billing();
			ph_fedex_silent_debug_option();
		}
		if( $tab == 'pickup' ){
			wf_fedex_load_pickup_options();
			wf_fedex_load_pickup_address_options();
		}
		if( $tab == 'packaging' ){
			wf_fedex_load_packing_method_options();
			xa_fedex_packing_method_options();
			ph_fedex_hazmat_package_options();
		}
		if( $tab == 'freight' ){
			wf_fedex_load_freight_options()
		}
		if( $tab == 'rates' ){
			ph_fedex_toggle_alt_estimated_delivery();
			wf_fedex_load_availability_options();
			ph_fedex_toggle_hold_at_location();
		}
		if( $tab == 'commercial_invoice' ){
			wf_fedex_load_commercialinvoice_image_uploader();
			ph_fedex_load_commercial_invoice_toggler();
			ph_fedex_load_usmca_toggler();
			ph_fedex_load_usmca_commercial_invoice_toggler();
		}
		if( $tab == 'special_services' ){
			xa_fedex_duties_payer_options();
			ph_fedex_toggle_home_delivery_premium();
		}
		if( $tab == 'advanced' ){
			
			ph_toggle_default_recipient_phone_num();
		}

		return_reason = jQuery('.ph_return_label_return').val();

		if( return_reason == 'OTHER' && $tab == 'label' )
		{
			jQuery('.ph_return_label_desc').closest('tr').show();
		}else{
			jQuery('.ph_return_label_desc').closest('tr').hide();
		}

		if( $tab == 'help_and_support' ){
			jQuery(".woocommerce-save-button").hide();
		}else{
			jQuery(".woocommerce-save-button").show();	
		}

		return false;
	}

	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_default_recipient_phone').click(function(){

		ph_toggle_default_recipient_phone_num();
	});
	// Toggle Doc Tab
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_doc_tab_content').click(function(){
		ph_fedex_toggle_doc_tab();
	});

	// Toggle CSB Shipments
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_csb5_shipments').click(function(){
		ph_fedex_toggle_csb_shipments();
	});

	// Toggle pickup options pickup
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_pickup_enabled').click(function(){
		wf_fedex_load_pickup_options();
		wf_fedex_load_pickup_address_options();
	});

	// Toggle Freight options pickup
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_freight_enabled').click(function(){
		wf_fedex_load_freight_options();
	});

	// Toggle Image uploader
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_commercial_invoice').click(function(){
		wf_fedex_load_commercialinvoice_image_uploader();
		ph_fedex_load_commercial_invoice_toggler();
	});

	// Toggle USMCA options
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_usmca_certificate').click(function(){
		ph_fedex_load_usmca_toggler();
	});

	// Toggle USMCA Commercial Invoice Cirtificate Of Origin
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_usmca_ci_certificate_of_origin').click(function(){
		ph_fedex_load_usmca_commercial_invoice_toggler();
	});

	// Toggle Est. Delivery Date
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_delivery_time').click(function(){
		ph_fedex_toggle_alt_estimated_delivery();
	});

	// Toggle Hold at Location
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_hold_at_location').click(function(){
		ph_fedex_toggle_hold_at_location();
	});

	// Toggle Alternative Return Address
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_alternate_return_address').click(function(){
		ph_fedex_toggle_alt_return_address();
	});

	// Toggle Alternative Return Address as Billing
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_billing_as_alternate_return_address').click(function(){
		ph_fedex_toggle_alt_return_address_as_billing();
	});

	// Toggle pickup options pickup address
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_use_pickup_address').click(function(){
		wf_fedex_load_pickup_address_options();
	});

	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_charges_payment_type').change(function(){
		xa_fedex_payment_type_options();
	});


	//myaccount return label
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_frontend_retun_label').click(function(){
		wf_fedex_return_label_options();
	});

	// Toggle Label Format based on Image Type option and Display Label in Browser.
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_image_type').change(function(){
		ph_fedex_toggle_label_show_browser();
	});

	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_customs_duties_payer').change(function(){
		xa_fedex_duties_payer_options()
	});

	// Toggle Home Delivery Premium Type.
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_home_delivery_premium').change(function(){
		ph_fedex_toggle_home_delivery_premium();
	});

	jQuery('.packing_method').change(function(){
		wf_fedex_load_packing_method_options();
		xa_fedex_packing_method_options();
	});

	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_hazmat_enabled').click(function(){
		ph_fedex_hazmat_package_options();
	});

	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_tracking_shipmentid').click(function(){
		wf_fedex_custom_shipment_message();
	});
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_package_generation').click(function(){
		wf_fedex_automatic_label_generation();
		ph_fedex_auto_label_trigger();
	});
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_label_generation').click(function(){
		ph_fedex_auto_label_trigger();
	});
	//Silent Debug mode
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_debug').click(function(){
		ph_fedex_silent_debug_option();
	});

	// FedEx Shipping Details Toggle
	jQuery('.ph_fedex_other_details').next('.ph_fedex_hide_show_product_fields').hide();
	jQuery('.ph_fedex_other_details').click(function(event){
		event.stopImmediatePropagation();
		jQuery('.toggle_symbol').toggleClass('toggle_symbol_click');
		jQuery(this).next('.ph_fedex_hide_show_product_fields').toggle();
	});

	// FedEx Shipping Details Toggle - Variation Level
	jQuery(document).on('click','.ph_fedex_var_other_details',function(){
		event.stopImmediatePropagation();
		jQuery(this).find('.var_toggle_symbol').toggleClass('var_toggle_symbol_click');
		jQuery(this).next('.ph_fedex_hide_show_var_product_fields').toggle();
	});

	// Toggle Dangerous Goods
	ph_fedex_toggle_dangerous_goods_option();
	jQuery('#_ph_fedex_dg_option').change(function(){
		ph_fedex_toggle_dangerous_goods_option();
	});

	// Toggle Dangerous Goods - Variation Level - By Default
	jQuery(document).on('click','.woocommerce_variation',function(){
		ph_fedex_toggle_var_dangerous_goods_option_on_load(this);
	});

	// Toggle Dangerous Goods - Variation Level - On Click
	jQuery(document).on('change','input.ph_fedex_variation_dg_option',function(){
		ph_fedex_toggle_var_dangerous_goods_option(this);
	});
	// End of Toggle Dangerous Goods

	// Toggle Alcohol Product
	ph_fedex_special_service_types_alcohol();
	jQuery('#_wf_fedex_special_service_types').change(function(){
		ph_fedex_special_service_types_alcohol();
	});

	// Toggle Aocohol Recipient Type - Variation Level - By Default
	jQuery(document).on('click','.woocommerce_variation',function(){
		ph_fedex_toggle_var_alcohol_product_on_load(this);
	});

	// Toggle Aocohol Recipient Type - Variation Level - On Click
	jQuery(document).on('change','input.ph_fedex_var_alcohol_recipient',function(){
		ph_fedex_toggle_var_alcohol_product(this);
	});

	// Toggle Battery Product
	ph_fedex_toggle_battery_products();
	jQuery('#_battery_products').change(function(){
		ph_fedex_toggle_battery_products();
	});

	// Toggle Battery Product - Variation Level - By Default
	jQuery(document).on('click','.woocommerce_variation',function(){
		ph_fedex_toggle_var_battery_product_on_load(this);
	});

	// Toggle Battery Product - Variation Level - On Click
	jQuery(document).on('change','input.ph_fedex_variation_battery_product',function(){
		ph_fedex_toggle_var_battery_product(this);
	});
	// End of Toggle Battery Product

	jQuery(document).on('change','select.ph_return_label_return',function(){
		if ($(this).val() == 'OTHER') {
			jQuery('.ph_return_label_desc').closest('tr').show();
		}else{
			jQuery('.ph_return_label_desc').closest('tr').hide();
		}
	});

	// Toggle Email format
	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_zpl_in_email').click(function(){
		ph_toggle_email_content();
	})

	// Prevent button click if already clicked
	jQuery('.ph-disable-on-click').on('click', function(e) {

		jQuery(this).css('pointer-events', 'none');
		jQuery('.ph-disable-on-click').attr('disabled', 'disabled');
		jQuery('.ph-disable-on-click').css({"color": "#555", "background": "#f7f7f7", "opacity": ".45", "cursor": "not-allowed" });

	})

	// Top Bulk Action
	jQuery("#doaction").click( function() 
	{
		selected = jQuery("#bulk-action-selector-top option:selected").val();

		phFedexDisableClick(selected); 
	});

	// Bottom Bulk Action
	jQuery("#doaction2").click( function() 
	{
		selected = jQuery("#bulk-action-selector-bottom option:selected").val();

		phFedexDisableClick(selected); 
	});

	jQuery('.ph_return_label_desc').attr({'maxlength':25});
	jQuery('#_ph_commodity_description').attr({'maxlength':450});
	jQuery('#_wf_manufacture_country').attr({'maxlength':2});
	jQuery('#_ph_commodity_description').attr({'minlength':3});
	jQuery('.ph_fedex_frieght_billing_country').attr({'maxlength':2});

	/********************************************* Help & Support Send Report Settings ************************************************/

	jQuery('#ph_fedex_ticket_number').keyup( function(){
		jQuery('#ph_fedex_ticket_number').removeClass('required_field');
		jQuery('.ph_fedex_ticket_number_error').hide();
	});

	jQuery("#ph_fedex_consent").click( function() {
		jQuery('#ph_fedex_consent').removeClass('required_field');
		jQuery('.ph_fedex_consent_error').hide();
	});

	jQuery("#ph_fedex_submit_ticket").click( function() {

		jQuery('.ph_error_message').remove();

		var required 	= false;
		var ticket_num 	= jQuery('#ph_fedex_ticket_number').val();
		var consent 	= jQuery('#ph_fedex_consent').is(':checked');

		if( !ticket_num ) {
			jQuery('#ph_fedex_ticket_number').addClass('required_field');
			jQuery('.ph_fedex_ticket_number_error').show();
			required 	= true;
		}

		if( !consent ) {
			jQuery('#ph_fedex_consent').addClass('required_field');
			jQuery('.ph_fedex_consent_error').show();
			required 	= true;
		}

		if( required ) {
			return false;
		}
		// Change Text and Disable the Button
		jQuery("#ph_fedex_submit_ticket").prop("value", "Please Wait...");
		jQuery("#ph_fedex_submit_ticket").attr( 'disabled', 'disabled');
		
		let key_data = {
			action 		: 'ph_get_fedex_log_data',
		}

		jQuery.post( ajaxurl, key_data, function( result, status ) {

			console.log(result);

			try{

				let response = JSON.parse(result);

				if( response.status == true ) {

						let key_data = {
							action 		: 'ph_fedex_submit_support_ticket',
							ticket_num 	: ticket_num,
							log_file	: response.file_path
						}

						jQuery.post( ajaxurl, key_data, function( result, status ) {

							let response2 = JSON.parse(result);

							if( response2.status == true ) {
								message = "<b>Diagnostic report sent successfully.</b> PluginHive Support Team will contact you shortly via email."
								jQuery( ".ph_fedex_help_table" ).after( "<p style='color:green;' class='ph_error_message'>"+message+"</p>" );

								// Add original text and enable the button
								jQuery("#ph_fedex_submit_ticket").prop("value", "Send Report");
								jQuery("#ph_fedex_submit_ticket").removeAttr("disabled");
							} else {

								// Add original text and enable the button
								jQuery("#ph_fedex_submit_ticket").prop("value", "Send Report");
								jQuery("#ph_fedex_submit_ticket").removeAttr("disabled");
							}
							
						});

				}else{
					message = response.message;
					jQuery( ".ph_fedex_help_table" ).after( "<p style='color:red;' class='ph_error_message'>"+message+"</p>" );

					// Add original text and enable the button
					jQuery("#ph_fedex_submit_ticket").prop("value", "Send Report");
					jQuery("#ph_fedex_submit_ticket").removeAttr("disabled");
				}

			} catch(err) {
				alert(err.message);

				// Add original text and enable the button
				jQuery("#ph_fedex_submit_ticket").prop("value", "Send Report");
				jQuery("#ph_fedex_submit_ticket").removeAttr("disabled");
			}
			
		});
	});

	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_insure_contents').click(function () {

		ph_toggle_min_order_amount_for_insurance();
	});

	ph_toggle_min_order_amount_for_insurance();
});

// Toggle Minimum Order amount for Insurance option
function ph_toggle_min_order_amount_for_insurance() {

	if ( jQuery("#woocommerce_wf_fedex_woocommerce_shipping_insure_contents").is(':checked')) {
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_min_order_amount_for_insurance').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_min_order_amount_for_insurance').closest('tr').hide();
	}
}


jQuery( document ).ready( function( $ ) {
	if($('#_wf_dry_ice').is(':checked'))
	{
		$('#shipping_product_data ._wf_dry_ice_weight_field').show();
	}
	else
	{
		$('#shipping_product_data ._wf_dry_ice_weight_field').hide();
	}
	$('#_wf_dry_ice').click(function(){
		if($('#_wf_dry_ice').is(':checked'))
		{
			$('#shipping_product_data ._wf_dry_ice_weight_field').show();
		}
		else
		{
			$('#shipping_product_data ._wf_dry_ice_weight_field').hide();
		}
	});
});

// Prevent repeated button clicks on All Orders Page
function phFedexDisableClick() {

	if( selected == 'wf_create_shipment' )
	{
		jQuery('#doaction, #doaction2').css('pointer-events', 'none');
		
		jQuery( "table" ).block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.5
			}
		});
	}

}

function wf_fedex_load_packing_method_options(){
	pack_method	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_packing_method').val();      // class packing_method
	jQuery('#fedex_packing_options').hide();
	jQuery('.fedex_weight_based_option').closest('tr').hide();
	switch(pack_method){
		case 'per_item':
		default:
			break;
			
		case 'box_packing':
			jQuery('#fedex_packing_options').show();
			break;
			
		case 'weight_based':
			jQuery('.fedex_weight_based_option').closest('tr').show();
			break;
	}
}

function ph_toggle_default_recipient_phone_num() {

	if ( jQuery('#woocommerce_wf_fedex_woocommerce_shipping_default_recipient_phone').is(':checked') ) {

		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_default_recipient_phone_num').closest('tr').show();
	} else {

		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_default_recipient_phone_num').closest('tr').hide();
	}
	
}

// Toggle ZPL Content in email body
function ph_toggle_zpl_content_in_email() {

	// jQuery("#woocommerce_wf_fedex_woocommerce_shipping_image_type").on('change', function(){

	selectedLabelType = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_image_type').val();

	if ( selectedLabelType == 'zplii' ) {

		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_zpl_in_email').closest('tr').show();
	} else {
		
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_zpl_in_email').closest('tr').hide();
	}
}

function xa_fedex_packing_method_options(){
	pack_method	=	jQuery('.packing_method').val();
	if( pack_method != 'box_packing'){
		jQuery('.speciality_box').closest('tr').hide();
		jQuery('.box_packing_algorithm').closest('tr').hide();
		jQuery('.fedex_stack_to_volume').closest('tr').hide();
	}

	jQuery('.packing_method').change(function(){
		if( pack_method == 'box_packing'){
			jQuery('.speciality_box').closest('tr').show();
			jQuery('.box_packing_algorithm').closest('tr').show();
			ph_fedex_toggle_box_packing_algorithm();
		}else{
			jQuery('.speciality_box').closest('tr').hide();
			jQuery('.box_packing_algorithm').closest('tr').hide();
			jQuery('.fedex_stack_to_volume').closest('tr').hide();
		}
	});
	
	ph_fedex_toggle_box_packing_algorithm();

	jQuery('.box_packing_algorithm').change(function(){
		ph_fedex_toggle_box_packing_algorithm();
	});
}

function ph_fedex_toggle_box_packing_algorithm(){

	pack_method	=	jQuery('.packing_method').val();
	pack_algorithm = jQuery('.box_packing_algorithm').val();

	if ( pack_algorithm == 'stack_first' && pack_method == 'box_packing'){

		jQuery('.fedex_stack_to_volume').closest('tr').show();
	} else {

		jQuery('.fedex_stack_to_volume').closest('tr').hide();
	}
}

function ph_fedex_toggle_doc_tab() {

	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_doc_tab_content').is(":checked");
	
	if(checked) {
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_doc_tab_orientation').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_doc_tab_orientation').closest('tr').hide();
	}
}

function ph_fedex_toggle_csb_shipments() {

	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_csb5_shipments').is(":checked");
	
	if(checked) {
		jQuery('.ph_fedex_csb5').closest('tr').show();
	}else{
		jQuery('.ph_fedex_csb5').closest('tr').hide();
	}
}

function ph_fedex_hazmat_package_options(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_hazmat_enabled').is(":checked");
	if(checked){
		jQuery('.ph_fedex_hazmat_grp').closest('tr').show();
	}else{
		jQuery('.ph_fedex_hazmat_grp').closest('tr').hide();
	}
}

function xa_fedex_payment_type_options(){
	me = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_charges_payment_type');
	if( me.val() =='THIRD_PARTY' ){
		jQuery('.thirdparty_grp').closest('tr').show();
	}else{
		jQuery('.thirdparty_grp').closest('tr').hide();
	}
}

function xa_fedex_duties_payer_options(){
	me = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_customs_duties_payer');
	if( me.val() =='THIRD_PARTY' ){
		jQuery('.broker_grp').closest('tr').show();
	}else{
		jQuery('.broker_grp').closest('tr').hide();
	}
	if( me.val() =='THIRD_PARTY_ACCOUNT' ){
		jQuery('.third_party_grp').closest('tr').show();
	}else{
		jQuery('.third_party_grp').closest('tr').hide();
	}
}

/**
 * Toggle Home Delivery Premium Type.
 */
function ph_fedex_toggle_home_delivery_premium(){

	var checked	= jQuery('#woocommerce_wf_fedex_woocommerce_shipping_home_delivery_premium').is(":checked");
	if(checked){
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_home_delivery_premium_type').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_home_delivery_premium_type').closest('tr').hide();
	}
}

function wf_fedex_return_label_options(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_frontend_retun_label').is(":checked");
	if(checked){
		jQuery('.ph_fedex_return_label').closest('tr').show();
	}else{
		jQuery('.ph_fedex_return_label').closest('tr').hide();
	}
}

/**
 * Toggle Label Format based on Image Type option and Display Label in Browser.
 */
function ph_fedex_toggle_label_show_browser(){
	label_image_type = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_image_type');
	if( label_image_type.val() =='png' ){
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_show_label_in_browser').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_show_label_in_browser').closest('tr').hide();
	}
}

function wf_fedex_load_freight_options(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_freight_enabled').is(":checked");
	if(checked){
		jQuery('.freight_group').closest('tr').show();
	}else{
		jQuery('.freight_group').closest('tr').hide();
	}
}
function wf_fedex_load_pickup_options(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_pickup_enabled').is(":checked");
	if(checked){
		jQuery('.wf_fedex_pickup_grp').closest('tr').show();
	}else{
		jQuery('.wf_fedex_pickup_grp').closest('tr').hide();
	}
}
function wf_fedex_load_pickup_address_options(){
	var pickup_checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_use_pickup_address').is(":checked");
	var address_checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_pickup_enabled').is(":checked");
	if( pickup_checked && address_checked ){
		jQuery('.wf_fedex_pickup_address_grp').closest('tr').show();
	}else{
		jQuery('.wf_fedex_pickup_address_grp').closest('tr').hide();
	}
}

function wf_fedex_load_commercialinvoice_image_uploader(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_commercial_invoice').is(":checked");
	if(checked){
		jQuery('.commercialinvoice-image-uploader').closest('tr').show();
	}else{
		jQuery('.commercialinvoice-image-uploader').closest('tr').hide();
	}
}

function ph_fedex_load_commercial_invoice_toggler(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_commercial_invoice').is(":checked");
	if(checked){
		jQuery('.commercial_invoice_toggle').closest('tr').show();
	}else{
		jQuery('.commercial_invoice_toggle').closest('tr').hide();
	}
}

// Toggle USMCA options
function ph_fedex_load_usmca_toggler(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_usmca_certificate').is(":checked");

	if( checked ){
		jQuery('.usmca_toggle').closest('tr').show();
		jQuery('.usmca_and_usmcaci_toggle').closest('tr').show();
	}
	else{
		jQuery('.usmca_and_usmcaci_toggle').closest('tr').hide();
		jQuery('.usmca_toggle').closest('tr').hide();
		ph_fedex_load_usmca_commercial_invoice_toggler();
	}
}

// Toggle USMCA options
function ph_fedex_load_usmca_commercial_invoice_toggler(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_usmca_ci_certificate_of_origin').is(":checked");

	if( checked ){
		jQuery('.usmca_and_usmcaci_toggle').closest('tr').show();
	}
	else{
		jQuery('.usmca_and_usmcaci_toggle').closest('tr').hide();
		ph_fedex_load_usmca_toggler();
	}
}

function wf_fedex_load_availability_options(){
	me = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_availability');
	if( me.val() =='all' ){
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_countries').closest('tr').hide();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_countries').closest('tr').show();
	}
}
function wf_fedex_custom_shipment_message(){
	checked = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_tracking_shipmentid').is(":checked");
	if(checked){
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_custom_message').closest('tr').show();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_disable_customer_tracking').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_custom_message').closest('tr').hide();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_disable_customer_tracking').closest('tr').hide();
	}
}
function wf_fedex_automatic_label_generation(){
	checked = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_package_generation').is(":checked");
	if(checked){
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_label_generation').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_label_generation').closest('tr').hide();
	}
}
function ph_fedex_auto_label_trigger(){
	let checked1 = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_package_generation').is(":checked");
	let checked2 = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_automate_label_generation').is(":checked");
	if( checked1 && checked2 ){
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_auto_label_trigger').closest('tr').show();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_auto_label_trigger').closest('tr').hide();
	}
}
//silent debug
function ph_fedex_silent_debug_option(){
	var checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_debug').is(":checked");
	if(checked){
		jQuery('.ph_fedex_silent_debug').closest('tr').show();
	}else{
		jQuery('.ph_fedex_silent_debug').closest('tr').hide();
	}
}

/**
 * Toggle Estimated Delivery
**/
function ph_fedex_toggle_alt_estimated_delivery(){

	var est_delivery	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_delivery_time').is(":checked");

	if( est_delivery ){
		jQuery('.ph_fedex_est_delivery_date').closest('tr').show();
	}else{
		jQuery('.ph_fedex_est_delivery_date').closest('tr').hide();
	}
}

/**
 * Toggle Hold at location 
**/
function ph_fedex_toggle_hold_at_location() {
	
	if(jQuery('#woocommerce_wf_fedex_woocommerce_shipping_hold_at_location').is(":checked")) {
		
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_hold_at_location_carrier_code').closest('tr').show();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_attribute_type').closest('tr').show();
		ph_fedex_custom_attributes();

	}else{

		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_hold_at_location_carrier_code').closest('tr').hide();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_attribute_type').closest('tr').hide();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_location_attributes').closest('tr').hide();

	}
}

/**
 * Toggle Location Attributes
 */
 function ph_fedex_custom_attributes(){
	
	var attribute_type = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_attribute_type').val();

	if( attribute_type == 'all' ) {
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_location_attributes').closest('tr').hide();
	}else{
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_location_attributes').closest('tr').show();
	}
	
}

/**
 * Toggle Alternative Return Address
**/
function ph_fedex_toggle_alt_return_address(){

	var alt_address_checked	=	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_alternate_return_address').is(":checked");

	if( alt_address_checked ){
		jQuery('.ph_fedex_alt_return_address').closest('tr').show();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_billing_as_alternate_return_address').closest('tr').show();
	}else{
		jQuery('.ph_fedex_alt_return_address').closest('tr').hide();
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_billing_as_alternate_return_address').closest('tr').hide();
	}

	ph_fedex_toggle_alt_return_address_as_billing();
}

/**
 * Toggle Billing as Alternative Return Address
**/
function ph_fedex_toggle_alt_return_address_as_billing(){

	var alt_address_checked		= jQuery('#woocommerce_wf_fedex_woocommerce_shipping_alternate_return_address').is(":checked");
	var alt_billing_address		= jQuery('#woocommerce_wf_fedex_woocommerce_shipping_billing_as_alternate_return_address').is(":checked");

	if( alt_address_checked && !alt_billing_address ){
		jQuery('.ph_fedex_alt_return_address').closest('tr').show();
	}else{
		jQuery('.ph_fedex_alt_return_address').closest('tr').hide();
	}
}

/**
 * Toggle Dangerous Goods Option Settings
**/
function ph_fedex_toggle_dangerous_goods_option(){

	let dg_option = jQuery("#_ph_fedex_dg_option").val();

	if( dg_option == 'LIMITED_QUANTITIES_COMMODITIES' ){

		jQuery(".ph_fedex_dangerous_goods_lqc").show();
		jQuery(".ph_fedex_dangerous_goods_ormd").hide();
		jQuery(".ph_fedex_hazardous_materials").hide();

	} else if( dg_option == 'ORM_D' ){

		jQuery(".ph_fedex_dangerous_goods_ormd").show();
		jQuery(".ph_fedex_dangerous_goods_lqc").hide();
		jQuery(".ph_fedex_hazardous_materials").hide();

	} else if( dg_option == 'HAZARDOUS_MATERIALS' || dg_option == 'BATTERY' ){

		jQuery(".ph_fedex_hazardous_materials").show();
		jQuery(".ph_fedex_dangerous_goods_lqc").hide();
		jQuery(".ph_fedex_dangerous_goods_ormd").hide();

	} else{

		jQuery(".ph_fedex_dangerous_goods_lqc").hide();
		jQuery(".ph_fedex_dangerous_goods_ormd").hide();
		jQuery(".ph_fedex_hazardous_materials").hide();
	}
}

/**
 * Toggle Dangerous Goods Option - Variation Level - Onload
 **/
 function ph_fedex_toggle_var_dangerous_goods_option_on_load(e){

 	let dg_option = jQuery(e).find(".ph_fedex_variation_dg_option").val();

 	if( dg_option == 'LIMITED_QUANTITIES_COMMODITIES' ){

 		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").show();
 		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").hide();
 		jQuery(e).find(".ph_fedex_var_hazardous_materials").hide();

 	} else if( dg_option == 'ORM_D' ){

 		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").show();
 		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").hide();
 		jQuery(e).find(".ph_fedex_var_hazardous_materials").hide();

 	} else if( dg_option == 'HAZARDOUS_MATERIALS' || dg_option == 'BATTERY' ){

 		jQuery(e).find(".ph_fedex_var_hazardous_materials").show();
 		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").hide();
 		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").hide();

 	} else{

 		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").hide();
 		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").hide();
 		jQuery(e).find(".ph_fedex_var_hazardous_materials").hide();
 	}
 }

/**
 * Toggle Dangerous Goods Option - Variation Level - Onclick
**/
 function ph_fedex_toggle_var_dangerous_goods_option(e){
 	
 	let dg_option = jQuery(e).find(".ph_fedex_variation_dg_option").val();
 	let dg_check  = jQuery(e).find(".ph_fedex_variation_dangerous_goods").is(':checked');

 	if( dg_option == 'LIMITED_QUANTITIES_COMMODITIES' ){

		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").show();
		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").hide();
		jQuery(e).find(".ph_fedex_var_hazardous_materials").hide();

	} else if( dg_option == 'ORM_D' ){

		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").show();
		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").hide();
		jQuery(e).find(".ph_fedex_var_hazardous_materials").hide();

	} else if( dg_option == 'HAZARDOUS_MATERIALS' || dg_option == 'BATTERY' ){

		jQuery(e).find(".ph_fedex_var_hazardous_materials").show();
		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").hide();
		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").hide();

	} else{

		jQuery(e).find(".ph_fedex_var_dangerous_goods_ormd").hide();
		jQuery(e).find(".ph_fedex_var_dangerous_goods_lqc").hide();
		jQuery(e).find(".ph_fedex_var_hazardous_materials").hide();
	}
 }

/**
 * Toggle Alcohol Product Settings alcohal_recipient
**/
function ph_fedex_special_service_types_alcohol(){
	if( jQuery("#_wf_fedex_special_service_types").is(":checked") ){
		jQuery(".ph_fedex_alcohol_recipient").show();
	}
	else{
		jQuery(".ph_fedex_alcohol_recipient").hide();
	}
}

/**
 * Toggle Aocohol Recipient Type Settings - Variation Level - Onload
**/
function ph_fedex_toggle_var_alcohol_product_on_load(e){
	if( jQuery(e).find(".ph_fedex_variation_alcohol_product").is(':checked') ){
		jQuery(e).find(".ph_fedex_var_alcohol_recipient").show();
	}else{
		jQuery(e).find(".ph_fedex_var_alcohol_recipient").hide();
	}
}

/**
* Toggle Aocohol Recipient Type Settings - Variation Level - Onclick
**/
function ph_fedex_toggle_var_alcohol_product(e){
	if( jQuery(e).is(':checked') ){
		jQuery(e).closest( '.woocommerce_variation' ).find(".ph_fedex_var_alcohol_recipient").show();
	}else{
		jQuery(e).closest( '.woocommerce_variation' ).find(".ph_fedex_var_alcohol_recipient").hide();
	}
}


/**
 * Toggle Battery Materials Settings
**/
function ph_fedex_toggle_battery_products(){
	if( jQuery("#_battery_products").is(":checked") ){
		jQuery(".ph_fedex_battery_materials").show();
	}
	else{
		jQuery(".ph_fedex_battery_materials").hide();
	}
}

/**
 * Toggle Battery Materials Settings - Variation Level - Onload
**/
 function ph_fedex_toggle_var_battery_product_on_load(e){
 	if( jQuery(e).find(".ph_fedex_variation_battery_product").is(':checked') ){
 		jQuery(e).find(".ph_fedex_var_battery_materials").show();
 	}else{
 		jQuery(e).find(".ph_fedex_var_battery_materials").hide();
 	}
 }
 
/**
 * Toggle Battery Materials Settings - Variation Level - Onclick
**/
 function ph_fedex_toggle_var_battery_product(e){
 	if( jQuery(e).is(':checked') ){
 		jQuery(e).closest( '.woocommerce_variation' ).find(".ph_fedex_var_battery_materials").show();
 	}else{
 		jQuery(e).closest( '.woocommerce_variation' ).find(".ph_fedex_var_battery_materials").hide();
 	}
 }

jQuery( document ).ready( function( $ ) {

	$('#xa_fedex_validate_credentials').on('click', function( event ){
		jQuery( ".fedex-validation-result").html('<span style="float:left" class="spinner is-active"&nbsp;</span>' );
		event.preventDefault();
		var data = {
			'action'		: 'xa_fedex_validate_credential',
			'production'	: $('#woocommerce_wf_fedex_woocommerce_shipping_production').is(":checked") ? true : false,
			'account_number': $('#woocommerce_wf_fedex_woocommerce_shipping_account_number').val(),
			'meter_number'	: $('#woocommerce_wf_fedex_woocommerce_shipping_meter_number').val(),
			'api_key'		: $('#woocommerce_wf_fedex_woocommerce_shipping_api_key').val(),
			'api_pass'		: $('#woocommerce_wf_fedex_woocommerce_shipping_api_pass').val(),
			'origin'		: $('#woocommerce_wf_fedex_woocommerce_shipping_origin').val(),
			'origin_country': $('#woocommerce_origin_country_state').val(),
		};

		jQuery.post(ajaxurl, data, function(response) {
			response = JSON.parse(response);
			if( response.success=='yes' ){
				$(".fedex-validation-result").html('<span style="color: green;">'+response.message+'</span>')
			}else{
				$(".fedex-validation-result").html('<span style="color: red">'+response.message+'</span>')
			}
		});
	});
	

	
	var file_frame;
	$('#company_logo_picker').on('click', function( event ){

		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select a image to set Company Logo on Commercial invoice',
			button: {
				text: 'Use this image',
			},
			multiple: false
		});
		file_frame.on( 'select', function() {
			$( "#company_logo_result").html('<span style="float:left" class="spinner is-active"&nbsp;</span>' );

			attachment = file_frame.state().get('selection').first().toJSON();
			$( '#woocommerce_wf_fedex_woocommerce_shipping_company_logo' ).val( attachment.url );
			
			var data = {
				'action': 'xa_fedex_upload_image',
				'image': attachment.url,
				'image_id': 'IMAGE_1' //If changed image id here, Change in admin_helper.php also
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				response = JSON.parse(response);
				if( response.success==true ){
					$("#company_logo_result").html('<span style="color: #35a335;">'+response.message+'</span>')
				}else{
					$("#company_logo_result").html('<span style="color: #d83434;">'+response.message+'</span>')
				}
			});
		});
		file_frame.open();
	});
	
	$('#digital_signature_picker').on('click', function( event ){
		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select a image to set Digital Signature on Commercial invoice',
			button: {
				text: 'Use this image',
			},
			multiple: false
		});
		file_frame.on( 'select', function() {
			$( "#digital_signature_result").html('<span style="float:left" class="spinner is-active"&nbsp;</span>' );
			attachment = file_frame.state().get('selection').first().toJSON();
			$( '#woocommerce_wf_fedex_woocommerce_shipping_digital_signature' ).val( attachment.url );
			var data = {
				'action': 'xa_fedex_upload_image',
				'image': attachment.url,
				'image_id': 'IMAGE_2' //If changed image id here, Change in admin_helper.php also
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				response = JSON.parse(response);
				if( response.success==true ){
					$("#digital_signature_result").html('<span style="color: #35a335;">'+response.message+'</span>')
				}else{
					$("#digital_signature_result").html('<span style="color: #d83434;">'+response.message+'</span>')
				}
			});
		});
		file_frame.open();
	});

	$('#ph_track_fedex').on('click', function( event ){
		
		order_id = $('#order_id').val();

		var data = {
			'action': 'ph_fedex_shipment_tracking',
			'order_id': order_id,
		};

		//since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			location.reload();
		});
	});

	// Set the selected weight/dimension unit in a hidden field to convert while saving the settings
	$('#woocommerce_wf_fedex_woocommerce_shipping_dimension_weight_unit').on( 'change', function () {

		$('#selected_dim_unit').val( $('#woocommerce_wf_fedex_woocommerce_shipping_dimension_weight_unit').val() );

	});

});


/**
 * Order Edit Page, Toggle Home Delivery Premium
**/

jQuery(document).ready( function() {

	var manual_service_arr	= [];
	var manual_single_service_arr = [];

	jQuery('.fedex_manual_service').each(function(){
		manual_service_arr.push( jQuery(this).val() );
		manual_single_service_arr.push(jQuery("input[id='wf_fedex_service_choosing_radio']:checked").val());
	});
	var manual_service = manual_service_arr;

	if( jQuery("input[id='wf_fedex_service_choosing_radio']:checked").val() != 'wf_fedex_individual_service' ){
		var manual_service = manual_single_service_arr;
	}

	jQuery(document).on("change", ".fedex_manual_service", function(){
		
		var manual_service_arr	= [];
		var manual_single_service_arr = [];

		jQuery('.fedex_manual_service').each(function(){
			manual_service_arr.push( jQuery(this).val() );
			manual_single_service_arr.push(jQuery("input[id='wf_fedex_service_choosing_radio']:checked").val());
		});
		var manual_service = manual_service_arr;

		if( jQuery("input[id='wf_fedex_service_choosing_radio']:checked").val() != 'wf_fedex_individual_service' ){
			var manual_service 	= manual_single_service_arr;
		}

		ph_fedex_toggle_home_delivery_premium_date(manual_service);
	});

	ph_fedex_toggle_home_delivery_premium_date(manual_service);

	// Filter label sizes
	jQuery("#woocommerce_wf_fedex_woocommerce_shipping_image_type").on('change', function(){
		ph_fedex_toggle_label_type();
		ph_toggle_zpl_content_in_email();
		ph_toggle_email_content();		
	});

	// Toggle location attributes
	jQuery("#woocommerce_wf_fedex_woocommerce_shipping_attribute_type").on('change', function(){    // 2nd way
		ph_fedex_custom_attributes();
	
	});

	jQuery(".fedex_manual_service") .on('change', function(){

		toggle_ph_fedex_booking_conf_num_field();
	});

	toggle_ph_fedex_booking_conf_num_field();

	jQuery('#ph_fedex_booking_conf_num').keyup(function() {
		
		ph_toggle_create_shipment_button();
	});

});

// Toggle Booking Confirmation Number option based on services
function toggle_ph_fedex_booking_conf_num_field() {

	let enable_booking_conf_num = false;

	jQuery('.fedex_manual_service').each(function(){

		if ( jQuery(this).val() == 'INTERNATIONAL_ECONOMY_FREIGHT' || jQuery(this).val() == 'INTERNATIONAL_PRIORITY_FREIGHT' ) {
			enable_booking_conf_num = true;
			return false;
		}
	});

	if ( enable_booking_conf_num ) {

		jQuery('#ph_fedex_booking_conf_num').closest('tr').show();

		ph_toggle_create_shipment_button();
	} else {

		jQuery('#ph_fedex_booking_conf_num').closest('tr').hide();
		jQuery(".ph-empty-BCN-erroe-message").remove();
	}
}

// Toggle Create Shipment Button based on Booking confirmation number field empty or not
function ph_toggle_create_shipment_button() {

	if ( jQuery('#ph_fedex_booking_conf_num').val().trim() == '') {

		jQuery(".fedex_create_shipment").addClass("ph-empty-BCN");
	} else {

		jQuery(".fedex_create_shipment").removeClass("ph-empty-BCN");
		jQuery('.fedex_create_shipment').removeAttr('disabled');
		jQuery(".ph-empty-BCN-erroe-message").remove();

		jQuery('.fedex_create_shipment').css({"color": "#fff", "background": "#2271b1","opacity": "","cursor": "","pointer-events":"",});
	}
}

// Toggle email content option if label type is ZPLII
function ph_toggle_email_content() {

	isChecked = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_zpl_in_email').is(":checked");
	labelType = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_image_type').val();

	if( isChecked && labelType == 'zplii' ) {
		jQuery('.ph_email_content').hide();
	} else {
		jQuery('.ph_email_content').show();
	}
}


/**
 * Filter label sizes based on seleted label types
 */
function ph_fedex_toggle_label_type(){

	var pdf_label_type 			= {
	
		'PAPER_4X6'						 :	'PAPER_4X6', 
		'PAPER_4X6.75'					 :	'PAPER_4X6.75', 		
		'PAPER_4X8'						 :	'PAPER_4X8', 							  
		'PAPER_4X9'						 :	'PAPER_4X9', 							
		'PAPER_7X4.75'					 :	'PAPER_7X4.75', 						  		
		'PAPER_8.5X11_BOTTOM_HALF_LABEL' :	'PAPER_8.5X11_BOTTOM_HALF_LABEL', 		  	
		'PAPER_8.5X11_TOP_HALF_LABEL'	 :	'PAPER_8.5X11_TOP_HALF_LABEL',			  	
		'PAPER_LETTER'					 :	'PAPER_LETTER', 						  		
		'STOCK_4X6'						 :	'STOCK_4X6 (For Thermal Printer Only)', 						  		
		'STOCK_4X6.75'					 :	'STOCK_4X6.75 (For Thermal Printer Only)',			
		'STOCK_4X8'						 :	'STOCK_4X8 (For Thermal Printer Only)', 						  		
		'STOCK_4X9'						 :	'STOCK_4X9 (For Thermal Printer Only)',
	}; 	

	var png_label_type 			= {

		'PAPER_4X6'						 :	'PAPER_4X6', 
		'PAPER_4X6.75'					 :	'PAPER_4X6.75', 		
		'PAPER_4X8'						 :	'PAPER_4X8', 							  
		'PAPER_4X9'						 :	'PAPER_4X9', 							
		'PAPER_7X4.75'					 :	'PAPER_7X4.75', 						  		
		'PAPER_8.5X11_BOTTOM_HALF_LABEL' :	'PAPER_8.5X11_BOTTOM_HALF_LABEL', 		  	
		'PAPER_8.5X11_TOP_HALF_LABEL'	 :	'PAPER_8.5X11_TOP_HALF_LABEL',			  	
		'PAPER_LETTER'					 :	'PAPER_LETTER', 
	}
	

	var epl2_and_zplii_label_type 			= {
									
		'STOCK_4X6'						:	'STOCK_4X6 (For Thermal Printer Only)',						  		
		'STOCK_4X6.75_LEADING_DOC_TAB'	:	'STOCK_4X6.75_LEADING_DOC_TAB (For Thermal Printer Only)', 			
		'STOCK_4X6.75_TRAILING_DOC_TAB'	:	'STOCK_4X6.75_TRAILING_DOC_TAB (For Thermal Printer Only)', 			
		'STOCK_4X8'						:	'STOCK_4X8 (For Thermal Printer Only)',						  		
		'STOCK_4X9_LEADING_DOC_TAB'		:	'STOCK_4X9_LEADING_DOC_TAB (For Thermal Printer Only)', 				
		'STOCK_4X9_TRAILING_DOC_TAB'	:	'STOCK_4X9_TRAILING_DOC_TAB (For Thermal Printer Only)',
	}; 				
	


	selected_label_type = jQuery('#woocommerce_wf_fedex_woocommerce_shipping_image_type').val();


	jQuery('#woocommerce_wf_fedex_woocommerce_shipping_output_format option').each(function( index, element ) {

		jQuery("#woocommerce_wf_fedex_woocommerce_shipping_output_format option[value='" + element.value + "']").detach();
	});
	

	var label_types = {};

	if( selected_label_type == 'pdf'){
		label_types = pdf_label_type;
	} else if( selected_label_type == 'png' ){
		label_types = png_label_type;
	} else if( selected_label_type == 'epl2' || selected_label_type == 'zplii' ){
		label_types = epl2_and_zplii_label_type;
	}

	jQuery.each( label_types, function( key, value ) {
		jQuery('#woocommerce_wf_fedex_woocommerce_shipping_output_format').append(new Option( value, key ));
	});
}

function ph_fedex_toggle_home_delivery_premium_date(manual_service){

	var flag;

	for( x in manual_service ){

		var manual_service_temp = manual_service[x];

		if ( manual_service_temp == 'GROUND_HOME_DELIVERY' ) {
			
			flag = true;
		}
	}
	if ( flag ) {

		jQuery('.ph_fedex_home_delivery_premium_date').closest('tr').show();

	}else{

		jQuery('.ph_fedex_home_delivery_premium_date').closest('tr').hide();
	}

	// Migration Banner
	jQuery('.ph-fedex-view-progress').on('click', function (event) {
		
		jQuery(".ph-fedex-progress-details").toggle();
		jQuery(".ph-fedex-view-symbol").toggleClass("ph-fedex-view-symbol-toggle");
	});


	jQuery('.ph-fedex-close-migration-banner').off('click').on('click', function(e) {

		var data = {
			'action': 'ph_fedex_closing_migration_banner',
		};
		
		// Since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {

			response = JSON.parse(response);

			if( response ){

				setTimeout(function () {
					location.reload();
				}, 1000);
			}
		});
	
	});
}