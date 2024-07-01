<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings 			= apply_filters( 'xa_fedex_settings',get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null ) );
$saturday_pickup 	= ( isset($settings['saturday_pickup']) && !empty($settings['saturday_pickup']) && $settings['saturday_pickup'] == 'yes' ) ? true : false;
$default_invoice_commodity_value	= ( isset($settings['discounted_price']) && !empty($settings['discounted_price']) && $settings['discounted_price'] == 'yes' ) ? 'discount_price' : 'declared_price';

if( $saturday_pickup ) {

	$working_days = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );
} else {
	$working_days = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri' );
}

$freight_classes = include( 'data-wf-freight-classes.php' );
$smartpost_hubs  = include( 'data-wf-smartpost-hubs.php' );

$ship_from_address_option = array(
				'origin_address' => __('Origin Address', 'ph-fedex-woocommerce-shipping'),
				'shipping_address' => __('Shipping Address', 'ph-fedex-woocommerce-shipping')
				);
$ship_from_address_options = apply_filters('wf_filter_label_ship_from_address_options', $ship_from_address_option);

$auto_email_label_option 	= array(
							'shipper' 	=> __( 'Shipper', 'ph-fedex-woocommerce-shipping' ),
							'customer'	=> __( 'Recipient', 'ph-fedex-woocommerce-shipping' ),
						);
$auto_email_label_options 	= apply_filters( 'ph_fedex_filter_label_send_in_email_to_options', $auto_email_label_option );

$pickup_start_time_options	=	array();
foreach(range(8,18,0.5) as $pickup_start_time){ // Pickup ready time must contain a time between 08:00am and 06:00pm
	$pickup_start_time_options[(string)$pickup_start_time]	=	date("H:i",strtotime(date('Y-m-d'))+3600*$pickup_start_time);
}

$pickup_close_time_options	=	array();
foreach(range(8.5,24,0.5) as $pickup_close_time){ // Pickup ready time must contain a time between 08:00am and 06:00pm
	$pickup_close_time_options[(string)$pickup_close_time]	=	date("H:i",strtotime(date('Y-m-d'))+3600*$pickup_close_time);
}


$wc_countries   = new WC_Countries();
// This function will not support prior to WC 2.2
$country_list   = $wc_countries->get_countries();
global $woocommerce;
array_unshift( $country_list, "" );

// Show services based on origin country
$services 				= include('data-wf-service-codes.php');
$countryServiceMapper	= include('data-wf-country-service-mapper.php');
$originCountry			= ( isset( $settings['origin_country'] ) && !empty( $settings['origin_country'] ) ) ? $settings['origin_country'] : '';
$originCountry			= current( explode(':', $originCountry ) );
$mappedCountry			= array_key_exists( $originCountry, $countryServiceMapper ) ? $countryServiceMapper[$originCountry] : '';
$services				= array_key_exists( $mappedCountry, $services ) ? $services[$mappedCountry] : $services['US'];

$int_services = array();
$dom_services = array();
foreach ($services as $key => $value) {

	if ( strpos($key, 'INTERNATIONAL') !== false ) {

		$int_services = array_merge($int_services, array($key=>$value));
	} else {

		$dom_services = array_merge($dom_services, array($key=>$value));
	}

	if ( $key == 'FEDEX_GROUND' && ( $originCountry == 'CA' || $originCountry == 'US' ) ) {
			
		$int_services = array_merge($int_services, array($key=>$value));
	}
}
$shipping_type	= ( isset($settings['fedex_duties_and_taxes_rate']) && !empty($settings['fedex_duties_and_taxes_rate']) && $settings['fedex_duties_and_taxes_rate'] == 'yes' ) ? 'DUTIES_AND_TAXES' : 'NET_CHARGE';

$this->image_type = isset( $settings['image_type'] ) ? $settings['image_type'] : '';

if ( $this->image_type == 'png' ) {
	
	$label_output_format = array(
		'PAPER_4X6' 							  	=> __( 'PAPER_4X6',	'ph-fedex-woocommerce-shipping'),
		'PAPER_4X6.75' 							  	=> __( 'PAPER_4X6.75',	'ph-fedex-woocommerce-shipping'),
		'PAPER_4X8' 							  	=> __( 'PAPER_4X8', 'ph-fedex-woocommerce-shipping'),
		'PAPER_4X9' 							  	=> __( 'PAPER_4X9', 'ph-fedex-woocommerce-shipping'),
		'PAPER_7X4.75' 						  		=> __( 'PAPER_7X4.75', 'ph-fedex-woocommerce-shipping'),
		'PAPER_8.5X11_BOTTOM_HALF_LABEL' 		  	=> __( 'PAPER_8.5X11_BOTTOM_HALF_LABEL', 'ph-fedex-woocommerce-shipping'),
		'PAPER_8.5X11_TOP_HALF_LABEL'			  	=> __( 'PAPER_8.5X11_TOP_HALF_LABEL', 'ph-fedex-woocommerce-shipping'),
		'PAPER_LETTER' 						  		=> __( 'PAPER_LETTER', 'ph-fedex-woocommerce-shipping'),
	);
} elseif ( $this->image_type == 'epl2' || $this->image_type == 'zplii' ) {

	$label_output_format = array(
		'STOCK_4X6' 						  		=> __( 'STOCK_4X6 (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X6.75_LEADING_DOC_TAB' 				=> __( 'STOCK_4X6.75_LEADING_DOC_TAB (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X6.75_TRAILING_DOC_TAB' 			=> __( 'STOCK_4X6.75_TRAILING_DOC_TAB (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X8' 						  		=> __( 'STOCK_4X8 (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X9_LEADING_DOC_TAB' 				=> __( 'STOCK_4X9_LEADING_DOC_TAB (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X9_TRAILING_DOC_TAB' 				=> __( 'STOCK_4X9_TRAILING_DOC_TAB (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
	);
} else {
	
	$label_output_format = array(
		'PAPER_4X6' 							  	=> __( 'PAPER_4X6',	'ph-fedex-woocommerce-shipping'),
		'PAPER_4X6.75' 							  	=> __( 'PAPER_4X6.75',	'ph-fedex-woocommerce-shipping'),
		'PAPER_4X8' 							  	=> __( 'PAPER_4X8', 'ph-fedex-woocommerce-shipping'),
		'PAPER_4X9' 							  	=> __( 'PAPER_4X9', 'ph-fedex-woocommerce-shipping'),
		'PAPER_7X4.75' 						  		=> __( 'PAPER_7X4.75', 'ph-fedex-woocommerce-shipping'),
		'PAPER_8.5X11_BOTTOM_HALF_LABEL' 		  	=> __( 'PAPER_8.5X11_BOTTOM_HALF_LABEL', 'ph-fedex-woocommerce-shipping'),
		'PAPER_8.5X11_TOP_HALF_LABEL'			  	=> __( 'PAPER_8.5X11_TOP_HALF_LABEL', 'ph-fedex-woocommerce-shipping'),
		'PAPER_LETTER' 						  		=> __( 'PAPER_LETTER', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X6' 						  		=> __( 'STOCK_4X6 (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X6.75' 						  		=> __( 'STOCK_4X6.75 (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X8' 						  		=> __( 'STOCK_4X8 (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
		'STOCK_4X9' 						  		=> __( 'STOCK_4X9 (For Thermal Printer Only)', 'ph-fedex-woocommerce-shipping'),
	);
}

$shipping_class_option_arr = array();

$shipping_class_arr = get_terms( array('taxonomy' => 'product_shipping_class', 'hide_empty' => false ) );

foreach ( $shipping_class_arr as $shipping_class_detail ) {

	if ( is_object( $shipping_class_detail ) ) {

		$shipping_class_option_arr[ $shipping_class_detail->slug ] = $shipping_class_detail->name;
	}
}


/**
 * Array of settings
 */
return array(
	'tabs_wrapper'=>array(
		'type'=>'settings_tabs'
	),
	'fedex_registration_banner' => array(

		'type'	=>	'fedex_registration_banner'
	),
	'api'					=> array(
		'title'			  => __( 'Generic API Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			   => 'title',
		'description'		=> __( 'Get your <a href="https://www.pluginhive.com/register-fedex-account-get-developer-test-credentials/" target="_blank"><strong>FedEx Developer Key</strong></a> and <a href="https://www.pluginhive.com/get-fedex-production-credentials-enable-shipping-labels/" target="_blank"><strong>FedEx Production Key</strong></a> to use this plugin.', 'ph-fedex-woocommerce-shipping' ),
		'class'			=>'fedex_general_tab',
	),
	'account_number'		   => array(
		'title'		   => __( 'FedEx Account Number', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	 => '',
		'default'		 => '',
		'class'			=>'fedex_general_tab',
	),
	'meter_number'		   => array(
		'title'		   => __( 'FedEx Meter Number', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	 => '',
		'default'		 => '',
		'class'			=>'fedex_general_tab',

	),
	'api_key'		   => array(
		'title'		   => __( 'Web Services Key', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	 => '',
		'default'		 => '',
		'class'			=>'fedex_general_tab',
		'custom_attributes' => array('autocomplete' => 'off'),
	),
	'api_pass'		   => array(
		'title'		   => __( 'Web Services Password', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'password',
		'description'	 => '',
		'default'		 => '',
		'class'			=>'fedex_general_tab',
		'custom_attributes' => array('autocomplete' => 'off'),
	),
	'client_credentials'     => array(
		'title'         => __( 'Client Credentials', 'ph-fedex-woocommerce-shipping' ),
		'type'          => 'hidden',
		'default'       => '',
		'class'         => 'fedex_general_tab',
	),
	'client_license_hash'       => array(
		'title'         => __( 'Client License Hash', 'ph-fedex-woocommerce-shipping' ),
		'type'          => 'hidden',
		'default'       => '',
		'class'         => 'fedex_general_tab',
	),
	'production'	  => array(
		'title'		   => __( 'Production Key', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'This is a FedEx Production Key', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'desc_tip'	=> true,
		'class'			=>'fedex_general_tab',
		'description'	 => __( 'If this is a production API key and not a developer key, check this box.', 'ph-fedex-woocommerce-shipping' ),
	),

	'validate_credentials' => array(
		'type'			=> 'validate_button',
	),

	'debug'	  => array(
		'title'		   => __( 'Debug Mode', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'desc_tip'	=> true,
		'description'	 => __( 'Enable debug mode to show debugging information on the cart/checkout.', 'ph-fedex-woocommerce-shipping' ),
		'class'			=>'fedex_general_tab',
	),
	'silent_debug'	  => array(
		'title'		   => __( 'Silent Debug Mode', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'desc_tip'	=> true,
		'description'	 => __( 'Enable silent debug mode to create debug information without showing debugging information on the cart/checkout.', 'ph-fedex-woocommerce-shipping' ),
		'class'			=>'fedex_general_tab ph_fedex_silent_debug',
	),
	'dimension_weight_unit' => array(
			'title'		   => __( 'Dimension/Weight Unit', 'ph-fedex-woocommerce-shipping' ),
			'label'		   => __( 'This unit will be passed to FedEx.', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'select',
			'default'		 => 'LBS_IN',
			'class'		   => 'wc-enhanced-select fedex_general_tab',
			'desc_tip'	=> true,
			'description'	 => __('Product dimensions and weight will be converted to the selected unit and will be passed to FedEx.', 'ph-fedex-woocommerce-shipping' ),
			'options'		 => array(
				'LBS_IN'	=> __( 'Pounds & Inches', 'ph-fedex-woocommerce-shipping'),
				'KG_CM' 	=> __( 'Kilograms & Centimeters', 'ph-fedex-woocommerce-shipping')			
			)
	),
	'residential'	  => array(
		'title'		   => __( 'Residential Delivery', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Default to residential delivery.', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'desc_tip'	=> true,
		'class'		=>'fedex_general_tab',
		'description'	 => __( 'Enables Residential Delivery and validates the shipping address automatically (if your FedEx Account has this functionality enabled).', 'ph-fedex-woocommerce-shipping' ),
	),
	'insure_contents'	  => array(
		'title'	   => __( 'Insurance', 'ph-fedex-woocommerce-shipping' ),
		'label'	   => __( 'Enable Insurance', 'ph-fedex-woocommerce-shipping' ),
		'type'		=> 'checkbox',
		'default'	 => 'yes',
		'class'			=>'fedex_general_tab',
		'desc_tip'	=> true,
		'description' => __( 'Sends the package value to FedEx for insurance. SmartPost shipments will cover upto $100 only.', 'ph-fedex-woocommerce-shipping' ),
	),

	'min_order_amount_for_insurance' 	=> array(
		'title'			=> __( 'Min Order Amount', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'number',
		'description'	=> __( 'Insurance will apply only if Order subtotal amount is greater or equal to the Min Order Amount. Note - For Comparison it will take only the sum of product price i.e Order Subtotal amount. In Cart It will take Cart Subtotal Amount.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'class'			=> 'fedex_general_tab',
		'custom_attributes' => array(
			'step' => 'any',
		),
	),

	'ship_from_address'   => array(
		'title'		   => __( 'Ship From Address Preference', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'		   => 'wc-enhanced-select fedex_general_tab',
		'default'		 => 'origin_address',
		'options'		 => $ship_from_address_options,
		'description'	 => __( 'Change the preference of Ship From Address printed on the label. You can make  use of Billing Address from Order admin page, if you ship from a different location other than shipment origin address given below.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true
	),
	'origin'		   => array(
		'title'		   => __( 'Origin Zipcode', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'desc_tip'	=> true,
		'class'	=> 'fedex_general_tab',
		'description'	 => __( 'Enter postcode for the <strong>Shipper</strong>.', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => ''
	),
	'shipper_person_name'		   => array(
			'title'		   => __( 'Shipper Person Name', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'text',
			'default'		 => '',
			'desc_tip'	=> true,
			'class'	=> 'fedex_general_tab',
			'description'	 => __('Required for label Printing', 'ph-fedex-woocommerce-shipping' )		
	),	
	'shipper_company_name'		   => array(
			'title'		   => __( 'Shipper Company Name', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'text',
			'default'		 => ''	,
			'desc_tip'	=> true,
			'class'	=> 'fedex_general_tab',
			'description'	 => __('Required for label Printing', 'ph-fedex-woocommerce-shipping' )
	),	
	'shipper_phone_number'		   => array(
			'title'		   => __( 'Shipper Phone Number', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'text',
			'default'		 => ''	,
			'desc_tip'	=> true,
			'class'	=> 'fedex_general_tab',
			'description'	 => __('Required for label Printing', 'ph-fedex-woocommerce-shipping' )
	),
	'shipper_email'		   => array(
			'title'		   => __( 'Shipper Email', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'text',
			'default'		 => ''	,
			'desc_tip'	=> true,
			'class'	=> 'fedex_general_tab',
			'description'	 => __('Required for sending email notification', 'ph-fedex-woocommerce-shipping' )
	),
	//freight_shipper_street
	'frt_shipper_street'		   => array(
		'title'		   => __( 'Shipper Street Address', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
			'class'	=> 'fedex_general_tab',
		'description'	 => __('Required for label Printing. And should be filled if LTL Freight is enabled.', 'ph-fedex-woocommerce-shipping' )
	),
	'shipper_street_2'		   => array(
		'title'		   => __( 'Shipper Street Address 2', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
		'class'	=> 'fedex_general_tab',
		'description'	 => __('Required for label Printing. And should be filled if LTL Freight is enabled.', 'ph-fedex-woocommerce-shipping' )
	),
	'freight_shipper_city'		   => array(
		'title'		   => __( 'Shipper City', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
		'class'	=> 'fedex_general_tab',
		'description'	 => __('Required for label Printing. And should be filled if LTL Freight is enabled.', 'ph-fedex-woocommerce-shipping' )
	),
    'origin_country'    => array(
		'type'                => 'single_select_country',
	),
	'shipper_residential' 	=> array(
		'title'		   => __( 'Shipper Address is Residential', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'	=> 'fedex_general_tab',
		'default'		 => 'no'
	),
	'charges_payment_type'   => array(
		'title'		   => __( 'Shipping Charges', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'desc_tip'	=> true,
		'description'	 => __('Choose who is going to pay shipping and customs charges. Please fill Third Party settings below if Third Party is choosen. It will override freight shipement also', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => 'SENDER',
		'class'		   => 'wc-enhanced-select fedex_general_tab',
		'options'		 => array(
			'SENDER' 							  	=> __( 'Sender', 						'ph-fedex-woocommerce-shipping'),
			//'RECIPIENT' 							  	=> __( 'Recipient', 						'ph-fedex-woocommerce-shipping'),
			'THIRD_PARTY' 							  	=> __( 'Third Party', 						'ph-fedex-woocommerce-shipping'),
		)				
	),
	'shipping_payor_acc_no'	=> array(
		'title'		   => __( 'Third party Account Number', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'description'	 => __('Third Party Account Number. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
	),
	'shipping_payor_cname'	 => array(
		'title'		   => __( 'Contact Person', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Contact Person. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),

	//shipping_payor_company
	'shipp_payor_company'   => array(
		'title'		   => __( 'Company', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Company. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),
	'shipping_payor_phone'	 => array(
		'title'		   => __( 'Contact Number', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Contact Number. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),
	'shipping_payor_email'	 => array(
		'title'		   => __( 'Contact Email', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Contact Email. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),

	//shipping_payor_address1
	'shipp_payor_address1'   => array(
		'title'		   => __( 'Address Line 1', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Address Line 1. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),

	//shipping_payor_address2
	'shipp_payor_address2'   => array(
		'title'		   => __( 'Address Line 2', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Address Line 2. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),
	'shipping_payor_city'	   => array(
		'title'		   => __( 'City', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer City. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),
	'shipping_payor_state'	   => array(
		'title'		   => __( 'State Code', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer State Code. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),

	//shipping_payor_postal_code
	'shipping_payor_zip' => array(
		'title'		   => __( 'Postal Code', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp fedex_general_tab',
		'type'			=> 'text',
		'default'		 => '',
		'description'	 => __('Third Party Payer Postal Code. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),

	//shipping_payor_country
	'shipp_payor_country'	=> array(
		'title'		   => __( 'Country', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'thirdparty_grp wc-enhanced-select fedex_general_tab',
		'type'			=> 'select',
		'default'		 => '',
		'options'		  => $country_list,
		'description'	 => __('Third Party Payer Country. Required if third party payment selected', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		  => true,
	),

	'alternate_return_address'	=>	array(
		'title'			=>	__( 'Display Alternate Return Address on Label', 'ph-fedex-woocommerce-shipping' ),
		'label'			=>	__( 'Enable', 'ph-fedex-woocommerce-shipping'),
		'description'	=>	__( 'Alternate return address option that allows you to display different address on the shipping label. For example, if you send a package that is undeliverable, you may use this option to display your returns processing facility address so that FedEx will return the package to that address instead of your shipping facility address.', 'ph-fedex-woocommerce-shipping'),
		'desc_tip'		=> true,
		'type'			=>	'checkbox',
		'default'		=>	'no',
		'class'			=> 'fedex_general_tab',
	),
	'billing_as_alternate_return_address'	=>	array(
		'title'			=>	__( 'Billing Address as Alternate Return Address', 'ph-fedex-woocommerce-shipping' ),
		'label'			=>	__( 'Enable', 'ph-fedex-woocommerce-shipping'),
		'type'			=>	'checkbox',
		'default'		=>	'no',
		'class'			=> 'fedex_general_tab',
	),
	'alt_return_person_name'	=> array(
		'title'			=> __( 'Alternate Return Person Name', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		=> '',
		'class' 		=> 'fedex_general_tab ph_fedex_alt_return_address'
	),
	'alt_return_company_name'	=> array(
		'title'			=> __( 'Alternate Return Company Name', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		=> '',
		'class' 		=> 'fedex_general_tab ph_fedex_alt_return_address'
	),
	'alt_return_phone_number'	=> array(
		'title'			=> __( 'Alternate Return Phone Number', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		=> '',
		'class' 		=> 'fedex_general_tab ph_fedex_alt_return_address'
	),
	'alt_return_streetline'  => array(
		'title' 		=> __( 'Alternate Return Address Line', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'text',
		'default' 		=> '',
		'class' 		=> 'fedex_general_tab ph_fedex_alt_return_address'
	),
	'alt_return_city'	  	  => array(
		'title' 		=> __( 'Alternate Return City', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'text',
		'default' 		=> '',
		'class' 		=>	'fedex_general_tab ph_fedex_alt_return_address'
	),
	'alt_return_country_state'	=> array(
		'type'			=> 'alt_return_country_state',
	),
	'alt_return_custom_state'		=> array(
		'title' 		=> __( 'Alternate Return State Code', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		=> '',
		'class'			=> 'fedex_general_tab ph_fedex_alt_return_address'
	),
	'alt_return_postcode'	 => array(
		'title' 		=> __( 'Alternate Return Zipcode', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'text',
		'default'		=> '',
		'class'			=> 'fedex_general_tab ph_fedex_alt_return_address'
	),
	'fedex_working_days' 	=> array(
		'title'			=> __( 'Working Days', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'multiselect',
		'desc_tip'		=> true,
		'description'	=> __( 'Select the Working Days. This will be used for Shipping Rates, Labels and Pickup.', 'ph-fedex-woocommerce-shipping' ),
		'class'			=> 'fedex_general_tab chosen_select',
		'css'			=> 'width: 400px;',
		'default'		=> $working_days,
		'options'		=> array( 
								'Sun'=> __('Sunday', 'ph-fedex-woocommerce-shipping' ), 
								'Mon'=> __('Monday', 'ph-fedex-woocommerce-shipping' ),
								'Tue'=> __('Tuesday', 'ph-fedex-woocommerce-shipping' ), 
								'Wed'=> __('Wednesday', 'ph-fedex-woocommerce-shipping' ), 
								'Thu'=> __('Thursday', 'ph-fedex-woocommerce-shipping' ), 
								'Fri'=> __('Friday', 'ph-fedex-woocommerce-shipping' ), 
								'Sat'=> __('Saturday', 'ph-fedex-woocommerce-shipping' )
							),
	),

	'skip_products'	=> array(
		'title'			=>	__( 'Skip Products', 'ph-fedex-woocommerce-shipping' ),
		'type'			=>	'multiselect',
		'options'		=>	$shipping_class_option_arr,
		'description'	=>	__( 'Skip all the products belonging to the selected Shipping Classes while fetching rates and creating Shipping Label.', 'ph-fedex-woocommerce-shipping'),
		'desc_tip'		=>	true,
		'class'			=>	'chosen_select fedex_general_tab',
	),
	
	'client_side_reset'	  => array(
		'title'	   => __( 'Clear Data & Recreate Shipment', 'ph-fedex-woocommerce-shipping' ),
		'label'	   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'		=> 'checkbox',
		'default'	 => 'yes',
		'class'			=>'fedex_general_tab',
		'desc_tip'	=> true,
		'description' => __( 'By enabling this option you can delete the shipment from the order page and thereby recreate the shipping labels.', 'ph-fedex-woocommerce-shipping' ),
	),

	'title_special_services'	=> array(
		'title'		   => __( 'Special Services', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_special_services_tab',
		'description'	 => __( 'Configure special services related setting.', 'ph-fedex-woocommerce-shipping' ),
	),
	'signature_option'	 => array(
		'title'		   => __( 'Delivery Signature', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		 => '',
		'class'		   => 'wc-enhanced-select fedex_special_services_tab',
		'desc_tip'		=> true,
		'options'		 => array(
			''	   				=> __( 'None', 'ph-fedex-woocommerce-shipping' ),
			'ADULT'	   			=> __( 'Adult', 'ph-fedex-woocommerce-shipping' ),
			'DIRECT'	  			=> __( 'Direct', 'ph-fedex-woocommerce-shipping' ),
			'INDIRECT'	  		=> __( 'Indirect', 'ph-fedex-woocommerce-shipping' ),
			'NO_SIGNATURE_REQUIRED' => __( 'No Signature Required', 'ph-fedex-woocommerce-shipping' ),
			'SERVICE_DEFAULT'	  	=> __( 'Service Default', 'ph-fedex-woocommerce-shipping' ),
		),
		'description'    => __( 'FedEx Freight services are not eligible for Signature Service. Hence, Signature option will be ignored for Freight Shipments.', 'ph-fedex-woocommerce-shipping'),
	),
	'smartpost_hub'		   => array(
		'title'		   => __( 'FedEx SmartPost Hub', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'		   => 'wc-enhanced-select fedex_special_services_tab',
		'description'	 => __( 'Only required if using SmartPost.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'default'		 => '',
		'options'		 => $smartpost_hubs
	),
	'indicia'   => array(
		'title'		   => __( 'Indicia', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'desc_tip'	=> true,
		'description'	 => __('Applicable only for SmartPost. Ex: Parcel Select option requires weight of at-least 1LB. Automatic will choose PRESORTED STANDARD if the weight is less than 1lb and PARCEL SELECT if the weight is more than 1lb', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => 'PARCEL_SELECT',
		'class'		   => 'wc-enhanced-select fedex_special_services_tab',
		'options'		 => array(
			'MEDIA_MAIL'		 => __( 'MEDIA MAIL', 'ph-fedex-woocommerce-shipping' ),
			'PARCEL_RETURN'	=> __( 'PARCEL RETURN', 'ph-fedex-woocommerce-shipping' ),
			'PARCEL_SELECT'	=> __( 'PARCEL SELECT', 'ph-fedex-woocommerce-shipping' ),
			'PRESORTED_BOUND_PRINTED_MATTER' => __( 'PRESORTED BOUND PRINTED MATTER', 'ph-fedex-woocommerce-shipping' ),
			'PRESORTED_STANDARD' => __( 'PRESORTED STANDARD', 'ph-fedex-woocommerce-shipping' ),
			'AUTOMATIC' => __( 'AUTOMATIC', 'ph-fedex-woocommerce-shipping' )
		),
	),

	//shipping_customs_duties_payer
	'customs_duties_payer'  => array(
		'title' 		=> __( 'Customs Duties Payer', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'select',
		'desc_tip' 		=> true,
		'description' 	=> 'Select customs duties payer',
		'default' 		=> 'SENDER',
		'class' 		=> 'wc-enhanced-select fedex_special_services_tab',
		'options'		=> array(
			'SENDER' 	  			=> __( 'Sender', 'ph-fedex-woocommerce-shipping'),
			'RECIPIENT'	  			=> __( 'Recipient', 'ph-fedex-woocommerce-shipping'),
			'THIRD_PARTY'	  		=> __( 'Third Party (Broker)', 'ph-fedex-woocommerce-shipping'),
			'THIRD_PARTY_ACCOUNT'	=> __( 'Third Party', 'ph-fedex-woocommerce-shipping'),
		)				
	),

	'third_party_acc_no' 	=> array(
		'title' 		=> __( 'Third Party Account number', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'text',
		'class' 		=> 'third_party_grp fedex_special_services_tab',
		'default' 		=> '',
		'desc_tip' 		=> true,
		'description' 	=> 'Third Party Account number'			
	),
	'broker_acc_no'		   => array(
		'title'		   => __( 'Broker Account number', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			  => 'broker_grp fedex_special_services_tab',
		'default'		 => '',
		'desc_tip'	=> true,
		'description'	 => __('Broker account number', 'ph-fedex-woocommerce-shipping' )			
	),	
	'broker_name'		   => array(
		'title'		   => __( 'Broker name', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
		'description'	 => __('Broker name', 'ph-fedex-woocommerce-shipping' )
	),	
	'broker_company'		   => array(
		'title'		   => __( 'Broker Company name', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
		'description'	 => __('Broker Company Name', 'ph-fedex-woocommerce-shipping' )		
	),	
	'broker_phone'		   => array(
		'title'		   => __( 'Broker phone number', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
		'description'	 => __('Broker phone number', 'ph-fedex-woocommerce-shipping' )			
	),	
	'broker_email'		   => array(
		'title'		   => __( 'Brocker Email Address', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
	),	
	'broker_address'		   => array(
		'title'		   => __( 'Broker Address', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
	),	
	'broker_city'		   => array(
		'title'		   => __( 'Broker City', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
	),	
	'broker_state'		   => array(
		'title'		   => __( 'Broker State', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
	),	
	'broker_zipcode'		   => array(
		'title'		   => __( 'Zip Code', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
	),	
	'broker_country'		   => array(
		'title'		   => __( 'Country Code', 'ph-fedex-woocommerce-shipping' ),
		'class'			  => 'broker_grp fedex_special_services_tab',
		'type'			=> 'text',
		'default'		 => '',
		'desc_tip'	=> true,
	),
	'dropoff_type'  => array(
		'title' 		=> __( 'Dropoff Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'desc_tip' 		=> true,
		'description' 	=> 'Select the option that identifies the method by which the package is to be tendered to FedEx.',
		'default' 		=> 'REGULAR_PICKUP',
		'class' 		=> 'wc-enhanced-select fedex_special_services_tab',
		'options' 		=> array(
			'BUSINESS_SERVICE_CENTER' 	=> __( 'Business Service Center', 'ph-fedex-woocommerce-shipping'),
			'DROP_BOX' 					=> __( 'Drop Box', 'ph-fedex-woocommerce-shipping'),
			'REGULAR_PICKUP' 			=> __( 'Regular Pickup', 'ph-fedex-woocommerce-shipping'),
			'REQUEST_COURIER' 			=> __( 'Request Courier', 'ph-fedex-woocommerce-shipping'),
			'STATION' 					=> __( 'Station', 'ph-fedex-woocommerce-shipping'),
		)				
	),
	'document_content'	=> array(
		'title'		=> __('Document Content', 'ph-fedex-woocommerce-shipping'),
		'type'		=> 'select',
		'class'		=> 'wc-enhanced-select fedex_special_services_tab',
		'default'	=> '',
		'options'	=> array(
			''					=> __( 'None', 'ph-fedex-woocommerce-shipping'),
			'DERIVED'			=> __( 'Derived', 'ph-fedex-woocommerce-shipping'),
			'DOCUMENTS_ONLY'	=> __( 'Documents Only', 'ph-fedex-woocommerce-shipping'),
			'NON_DOCUMENTS'		=> __( 'Non Documents', 'ph-fedex-woocommerce-shipping'),
		)
	),
	// 'saturday_pickup'	  => array(
	// 	'title'	   => __( 'FedEx Saturday Pickup', 'ph-fedex-woocommerce-shipping' ),
	// 	'label'		=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
	// 	'type'		=> 'checkbox',
	// 	'class'		=>'fedex_special_services_tab',
	// 	'default'	 => 'yes',
	// 	'desc_tip'	=> true,
	// 	'description' => __( 'If enabled, FedEx will charge additional amount and the pickup will be requested for Saturdays too. Otherwise, the pickups will not happen on Saturdays and will be re-scheduled for Mondays instead.', 'ph-fedex-woocommerce-shipping' ),
	// ),
	'thirdparty_consignee' => array(
		'title' 		=> __('Third Party Consignee', 'ph-fedex-woocommerce-shipping'),
		'type' 			=> 'checkbox',
		'class'			=>'fedex_special_services_tab',
		'desc_tip' 		=> true
	),
	'dry_ice_enabled'	  => array(
		'title'		   => __( 'Ship Dry Ice', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Enable this to activate dry ice option to product level', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'	=>'fedex_special_services_tab',
		'default'		 => 'no'
	),
	'exclude_tax'	  => array(
		'title'		   => __( 'Exclude Tax', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Taxes will be excluded from product prices while generating label', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'			=>'fedex_special_services_tab',
		'default'		 => 'no'
	),
	'home_delivery_premium'	  => array(
		'title'		  	=> __( 'Home Delivery Premium', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'label' 		=> __( 'Enable this option to select from various FedEx Premium delivery services', 'ph-fedex-woocommerce-shipping' ),
		'class'			=> 'fedex_special_services_tab'
	),
	'home_delivery_premium_type' => array(
		'title'		 	=> __('Home Delivery Premium Types', 'ph-fedex-woocommerce-shipping'),
		'type'		 	=> 'select',
		'class'		 	=> 'fedex_special_services_tab',
		'default'	 	=> '',
		'description'	=> __( '<small>Note: For Date Certain delivery type, make sure to select the date while fulfilling the order under WooCommerce Order Edit page.</small>' ),
		'options'	 	=> array(
			'APPOINTMENT'	=> __( 'Appointment', 'ph-fedex-woocommerce-shipping' ),
			'DATE_CERTAIN'	=> __( 'Date Certain', 'ph-fedex-woocommerce-shipping' ),
			'EVENING'		=> __( 'Evening', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'fedex_tracking'	=> array(
		'title'		  	=> __( 'Display FedEx Live Tracking Details', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'	 	=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'Enabling this option will display live FedEx tracking details on the order edit page.' ),
		'class'			=> 'fedex_special_services_tab'
	),
	

	'title_rate'		   => array(
		'title'		   => __( 'Rate Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_rates_tab',
		'description'	 => __( 'Configure the rate related settings here. You can enable the desired FedEx services and other rate options.', 'ph-fedex-woocommerce-shipping' ),
	),
	'enabled'		  => array(
		'title'		   	=> __( 'Real-time Rates', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'default'		=> 'no',
		'class'			=>'fedex_rates_tab'
	),
	'title'			=> array(
		'title'		   => __( 'Method Title', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	 => __( 'This controls the title which the user sees during checkout.', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => __( 'FedEx', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'class'			=>'fedex_rates_tab'
	),
	'availability'		=> array(
		'title'		   => __( 'Method Available to', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		 => 'all',
		'class'		   => 'availability wc-enhanced-select fedex_rates_tab',
		'options'		 => array(
			'all'			=> __( 'All Countries', 'ph-fedex-woocommerce-shipping' ),
			'specific'	   => __( 'Specific Countries', 'ph-fedex-woocommerce-shipping' ),
		),
	),
	'countries'		   => array(
		'title'		   => __( 'Specific Countries', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'multiselect',
		'class'		   => 'chosen_select fedex_rates_tab',
		'css'			 => 'width: 450px;',
		'default'		 => '',
		'options'		 => $wc_countries->get_allowed_countries(),
	),
	'delivery-title'		   => array(
		'title'		   => __( 'FedEx Estimated Delivery Date', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_rates_tab',
	),
	'delivery_time'	  => array(
		'title'		   => __( 'Display Delivery Date', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'desc_tip'	=> true,
		'class'			=>'fedex_rates_tab',
		'description'	 => __( 'Show delivery information on the cart/checkout. Applicable for US destinations only.', 'ph-fedex-woocommerce-shipping' )
	),
	'ship_time_adjustment'	  => array(
		'title'		   => __( 'Shipping Time Adjustment', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'decimal',
		'default'		 => 1,
		'desc_tip'	=> true,
		'class'			=>'fedex_rates_tab ph_fedex_est_delivery_date',
		'description'	 => __( 'Adjust number of days to get the estimated delivery accordingly (Numeric Only).', 'ph-fedex-woocommerce-shipping' )
	),
	'cut_off_time'	=>	array(
		'title' 		=>	__( 'Cut-Off Time', 'ph-fedex-woocommerce-shipping' ),
		'type'			=>	'time',
		'placeholder'	=>	'23:00',
		'css'			=>	'width:400px',
		'desc_tip'		=> __( 'Estimated delivery will be adjusted to the next day if any Rate Request is made after cut off time. Use 24 hour format (Hour:Minute). Example - 23:00.', 'ph-fedex-woocommerce-shipping' ),
		'class'			=> 'fedex_rates_tab ph_fedex_est_delivery_date'
	),
	'fedex_one_rate'	  => array(
		'title'	   => __( 'FedEx One Rate', 'ph-fedex-woocommerce-shipping' ),
		'label'	   => sprintf( __( 'Enable %sFedEx One Rates%s', 'ph-fedex-woocommerce-shipping' ), '<a href="https://www.fedex.com/us/onerate/" target="_blank">', '</a>' ),
		'type'		=> 'checkbox',
		'class'		=>'fedex_rates_tab',
		'default'	 => 'yes',
		'desc_tip'	=> true,
		'description' => __( 'FedEx One Rates will be offered if the items are packed into a valid FedEx One box, and the origin and destination is the US. For other countries this option will enable FedEx packing. Note: All FedEx boxes are not available for all countries, disable this option or disable different boxes if you are not receiving any shipping services.', 'ph-fedex-woocommerce-shipping' ),
	),
	'fedex_cod_rate' 		=> array(
		'title' 		=> __( 'FedEx COD', 'ph-fedex-woocommerce-shipping' ),
		'label' 		=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'checkbox',
		'class' 		=> 'fedex_rates_tab',
		'default' 		=> 'no',
		'desc_tip' 		=> true,
		'description' 	=> __( 'Additional charges will be applied on Shipping Rates on enabling this service', 'ph-fedex-woocommerce-shipping' ),
	),
	'saturday_delivery'	=> array(
		'title'				=> __( 'FedEx Saturday Delivery', 'ph-fedex-woocommerce-shipping' ),
		'label'				=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'				=> 'checkbox',
		'default'			=> 'no',
		'desc_tip'			=> true,
		'class'				=> 'fedex_rates_tab',
		'description'		=> __( 'This option will enable Saturday Delivery Shipping Services.', 'ph-fedex-woocommerce-shipping' ),
	),
	'hold_at_location'	=> array(
		'title'				=> __( 'FedEx Hold at Location', 'ph-fedex-woocommerce-shipping' ),
		'label'				=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'				=> 'checkbox',
		'default'			=> 'no',
		'desc_tip'			=> true,
		'class'				=> 'fedex_rates_tab',
		'description'		=> __( 'This option will enable FedEx Hold at Location service. If it is enabled, customers can select any hold at location while checkout. FedEx will then hold the shipment at the selected location and the customers will have to pick their shipment from that location .', 'ph-fedex-woocommerce-shipping' ),
	),
	'hold_at_location_carrier_code'	 => array(
		'title'		  	=> __( 'FedEx Service', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		=> '',
		'class'		  	=> 'wc-enhanced-select fedex_rates_tab',
		'desc_tip'		=> true,
		'options'		=> array(
			''		    	=> __( 'Any', 'ph-fedex-woocommerce-shipping' ),
			'FDXE'	   		=> __( 'FedEx Express', 'ph-fedex-woocommerce-shipping' ),
			'FDXG'			=> __( 'FedEx Ground', 'ph-fedex-woocommerce-shipping' ),
			'FXFR'	    	=> __( 'FedEx Freight', 'ph-fedex-woocommerce-shipping' ),
		),
		'description'	=> __( 'Select the FedEx Service based on which the hold at location will be displayed at the cart & checkout page.', 'ph-fedex-woocommerce-shipping' )
	),

	'attribute_type'	=> array(
		'title'		   	=> __( 'Attribute Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		=> 'all',
		'class'		   	=> 'wc-enhanced-select fedex_rates_tab',
		'options'		=> array(
			'all'		    => __( 'All', 'ph-fedex-woocommerce-shipping' ),
			'custom'	    => __( 'Custom', 'ph-fedex-woocommerce-shipping' ),
		),
	),
	'location_attributes'	 => array(
		'title'		   => __( 'Location Attributes', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'multiselect',
		'class'			=> 'wc-enhanced-select fedex_rates_tab',
		'css'			=> 'width: 400px;',
		'default'		=> '',
		'options'		=> array(
			'ACCEPTS_CASH'						=> __( 'Accepts Cash', 'ph-fedex-woocommerce-shipping' ),
			'ALREADY_OPEN'						=> __( 'Already Open', 'ph-fedex-woocommerce-shipping' ),
			'CLEARANCE_SERVICES'				=> __( 'Clearance Services', 'ph-fedex-woocommerce-shipping' ),
			'COPY_AND_PRINT_SERVICES'			=> __( 'Copy and Print Services', 'ph-fedex-woocommerce-shipping' ),
			'DANGEROUS_GOODS_SERVICES'			=> __( 'Dangerous Goods Services', 'ph-fedex-woocommerce-shipping' ),
			'DIRECT_MAIL_SERVICES'				=> __( 'Direct Mail Services', 'ph-fedex-woocommerce-shipping' ),
			'DOMESTIC_SHIPPING_SERVICES'		=> __( 'Domestic Shipping Services', 'ph-fedex-woocommerce-shipping' ),
			'DROP_BOX'							=> __( 'Drop Box', 'ph-fedex-woocommerce-shipping' ),
			'INTERNATIONAL_SHIPPING_SERVICES'	=> __( 'International Shipping Services', 'ph-fedex-woocommerce-shipping' ),
			'LOCATION_IS_IN_AIRPORT'			=> __( 'Location is in Airport', 'ph-fedex-woocommerce-shipping' ),
			'NOTARY_SERVICES'					=> __( 'Notary Services', 'ph-fedex-woocommerce-shipping' ),
			'OBSERVES_DAY_LIGHT_SAVING_TIMES'	=> __( 'Observes Day Light Saving Times', 'ph-fedex-woocommerce-shipping' ),
			'OPEN_TWENTY_FOUR_HOURS'			=> __( 'Open Twenty Four Hours', 'ph-fedex-woocommerce-shipping' ),
			'PACKAGING_SUPPLIES'				=> __( 'Packaging Supplies', 'ph-fedex-woocommerce-shipping' ),
			'PACK_AND_SHIP'						=> __( 'Pack and Ship', 'ph-fedex-woocommerce-shipping' ),
			'PASSPORT_PHOTO_SERVICES'			=> __( 'Passport Photo Services', 'ph-fedex-woocommerce-shipping' ),
			'RETURNS_SERVICES'					=> __( 'Returns Services', 'ph-fedex-woocommerce-shipping' ),
			'SIGNS_AND_BANNERS_SERVICE'			=> __( 'Signs and Banners Service', 'ph-fedex-woocommerce-shipping' ),
			'SONY_PICTURE_STATION'				=> __( 'Sony Picture Station', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'request_type'	 => array(
		'title'		   => __( 'Request Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		 => 'LIST',
		'class'		   => 'wc-enhanced-select fedex_rates_tab',
		'desc_tip'		=> true,
		'options'		 => array(
			'LIST'		=> __( 'List Rates', 'ph-fedex-woocommerce-shipping' ),
			'ACCOUNT'	 => __( 'Account Rates', 'ph-fedex-woocommerce-shipping' ),
		),
		'description'	 => __( 'Choose whether to return List or Account (discounted) rates from the API.', 'ph-fedex-woocommerce-shipping' )
	),
	'shipping_quote_type'	  => array(
		'title'			=> __( 'Shipping Quote Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		=> $shipping_type,
		'description'	=> __( '<small>Base Shipping Cost: Shipping Cost without any discounts, taxes & surcharges.<br/>Total Net Shipping Cost without Tax: Shipping Cost with discount & surcharges.<br/>Total Net Shipping Cost: Shipping Cost with discount, surcharges & transportation taxes.<br/>Total Net Shipping Cost With Duties & Taxes: Shipping Cost with discount, surcharges, transportation taxes & all other international taxes.</small>', 'ph-fedex-woocommerce-shipping' ),
		'options'		 => array(
			'BASE_CHARGE'	    => __( 'Base Shipping Cost', 'ph-fedex-woocommerce-shipping' ),
			'NET_FEDEX_CHARGE'	=> __( 'Total Net Shipping Cost without Tax', 'ph-fedex-woocommerce-shipping' ),
			'NET_CHARGE'		=> __( 'Total Net Shipping Cost', 'ph-fedex-woocommerce-shipping' ),
			'DUTIES_AND_TAXES'	=> __( 'Total Net Shipping Cost With Duties & Taxes', 'ph-fedex-woocommerce-shipping' ),
		),
		'class'			=> 'fedex_rates_tab',
	),
	'offer_rates'   => array(
		'title'		   => __( 'Offer Rates', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'description'	 => '',
		'default'		 => 'all',
		'class'		   => 'wc-enhanced-select fedex_rates_tab',
		'options'		 => array(
			'all'		 => __( 'Offer the customer all returned rates', 'ph-fedex-woocommerce-shipping' ),
			'cheapest'	=> __( 'Offer the customer the cheapest rate only, anonymously', 'ph-fedex-woocommerce-shipping' ),
		),
	),
	'services'  => array(
		'type'			=> 'services'
	),
	'fedex_currency'	=> array(
		'title'			=> __('FedEx Currency', 'ph-fedex-woocommerce-shipping'),
		'type'			=> 'select',
		'default'		=> get_woocommerce_currency(),
		'options'		=>	get_woocommerce_currencies(),
		'class'			=>'fedex_rates_tab',
		'description'	=> __('Currency used to Communicate with FedEx. Conversion Rate required from store to FedEx Currency if it is different from Store Currency','ph-fedex-woocommerce-shipping'),
		'desc_tip'		=> true
	),

	'fedex_conversion_rate'	 => array(
		'title' 		  => __('Conversion Rate', 'ph-fedex-woocommerce-shipping'),
		'type' 			  => 'decimal',
		'default'		 => 1,
		'class'			=>'fedex_rates_tab',
		'description' 	  => __('Enter the conversion amount in case you have a different currency set up in store comparing to the currency of FedEx Account. This amount will be multiplied with all the cost of Store.','ph-fedex-woocommerce-shipping'),
		'desc_tip' 		  => true
	),

	'conversion_rate'	 => array(
		'title' 		  => __('Adjustment', 'ph-fedex-woocommerce-shipping'),
		'type' 			  => 'decimal',
		'default'		 => '',
		'class'			=>'fedex_rates_tab',
		'description' 	  => __('Enter the conversion amount in case you have a different currency set up comparing to the currency of origin location. This amount will be multiplied with the shipping rates. Leave it empty if no conversion required.','ph-fedex-woocommerce-shipping'),
		'desc_tip' 		  => true
	),
	'convert_currency' => array(
		'title'		   => __( 'Rates in Base Currency', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Convert FedEx returned rates to base currency.', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_rates_tab',
		'default'		 => 'no',
		'desc_tip'		  => true,
		'description'	 => __('Ex: FedEx returned rates in USD and would like to convert to the base currency EUR. Convertion happens only FedEx API provide the exchange rate.', 'ph-fedex-woocommerce-shipping' )
	),
	'min_amount'  => array(
		'title'		   => __( 'Minimum Order Amount', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'decimal',
		'placeholder'	=> wc_format_localized_price( 0 ),
		'default'		 => '0',
		'class'			=>'fedex_rates_tab',
		'description'	 => __( 'Users will need to spend this amount to get this shipping available.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
	),
	'min_shipping_cost'  => array(
		'title'		   => __( 'Minimum Shipping Cost', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'decimal',
		'placeholder'	=> 0,
		'class'			=>'fedex_rates_tab',
		'description'	 => __( 'If rates returned by FedEx API will be less than Minimum Shipping Cost then Customer will be charged Minimum Shipping Cost.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
	),
	'max_shipping_cost'  => array(
		'title'		   => __( 'Maximum Shipping Cost', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'decimal',
		'placeholder'	=> 0,
		'class'			=>'fedex_rates_tab',
		'description'	 => __( 'If rates returned by FedEx API will be greater than Maximun Shipping Cost then Customer will be charged Maximum Shipping Cost.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
	),
	'fedex_fallback' 				=> array(
		'title' 		=> __( 'Fallback', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'decimal',
		'default' 		=> '',
		'desc_tip' 		=> true,
		'class'			=>'fedex_rates_tab',
		'description' 	=> __( 'If FedEx returns no matching rates, offer this amount for shipping so that the user can still checkout. Leave blank to disable.', 'ph-fedex-woocommerce-shipping' ),
	),





	'title_label'		   => array(
		'title'		   => __( 'Label Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_label_tab',
		'description'	 => __( 'Configure the label and tracking related settings here.', 'ph-fedex-woocommerce-shipping' ),
	),

	'display_fedex_meta_box_on_order'	=>	array(
		'title'			=>	__( 'FedEx Label Printing', 'ph-fedex-woocommerce-shipping' ),
		'class'			=>	'fedex_label_tab',
		'type'			=>	'select',
		'options'			=> array(
				'yes'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
				'no' 			=> __( 'Disable', 'ph-fedex-woocommerce-shipping' ),
			),
		'default'		=>	'yes',
		'description'	=>	__( 'Disable this to hide FedEx meta boxes (Generate label and tracking meta box) on order page).', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=>	true,

	),
	'label_maskable_type'	=> array(
		'title'			=> __( 'Masking Data on the Shipping Labels', 'ph-fedex-woocommerce-shipping' ),
		'description'	=> __( 'Names for data elements / areas which may be masked from printing on the shipping labels.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'type'			=> 'multiselect',
		'class'			=> 'fedex_label_tab chosen_select',
		'default'		=> '',
		'options'	 	=> array(
			'CUSTOMS_VALUE'									=> __( 'Custom Value', 'ph-fedex-woocommerce-shipping' ),
			'DIMENSIONS'									=> __( 'Dimensions', 'ph-fedex-woocommerce-shipping' ),
			'DUTIES_AND_TAXES_PAYOR_ACCOUNT_NUMBER'			=> __( 'Duties And Taxes Payor Account Number', 'ph-fedex-woocommerce-shipping' ),
			'FREIGHT_PAYOR_ACCOUNT_NUMBER'					=> __( 'Freight payer Account Number', 'ph-fedex-woocommerce-shipping' ),
			'INSURED_VALUE'									=> __( 'Insured Value', 'ph-fedex-woocommerce-shipping' ),
			'PACKAGE_SEQUENCE_AND_COUNT'					=> __( 'Package Sequence And Count', 'ph-fedex-woocommerce-shipping' ),
			'SECONDARY_BARCODE'								=> __( 'Secondary Barcode', 'ph-fedex-woocommerce-shipping' ),
			'SHIPPER_ACCOUNT_NUMBER'						=> __( 'Shipper Account Number', 'ph-fedex-woocommerce-shipping' ),
			'SHIPPER_INFORMATION'							=> __( 'Shipper Information', 'ph-fedex-woocommerce-shipping' ),
			'SUPPLEMENTAL_LABEL_DOC_TAB'					=> __( 'Supplemental Label Doc Tab', 'ph-fedex-woocommerce-shipping' ),
			'TERMS_AND_CONDITIONS'							=> __( 'Terms And Conditions', 'ph-fedex-woocommerce-shipping' ),
			'TOTAL_WEIGHT'									=> __( 'Total Weight', 'ph-fedex-woocommerce-shipping' ),
			'TRANSPORTATION_CHARGES_PAYOR_ACCOUNT_NUMBER'	=> __( 'Transportation Charges Payor Account Number', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'timezone_offset' => array(
		'title' 		=> __('Time Zone Offset (Minutes)', 'ph-fedex-woocommerce-shipping'),
		'type' 			=> 'text',
		'description' 	=> __('Please enter a value in this field, if you want to change the shipment time while Label Printing. Enter a negetive value to reduce the time.','ph-fedex-woocommerce-shipping'),
		'class'			=>'fedex_label_tab',
		'desc_tip' 		=> true
	),
	//shipping_customs_shipment_purpose
	'customs_ship_purpose'   => array(
		'title'		   => __( 'Purpose of Shipment', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'desc_tip'	=> true,
		'description'	 => __('Select purpose of shipment', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => 'SOLD',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'options'		 => array(
			'GIFT' 				=> __( 'Gift', 				'ph-fedex-woocommerce-shipping'),
			'NOT_SOLD' 			=> __( 'Not Sold', 			'ph-fedex-woocommerce-shipping'),
			'PERSONAL_EFFECTS' 	=> __( 'Personal effects', 	'ph-fedex-woocommerce-shipping'),
			'REPAIR_AND_RETURN' => __( 'Repair and return', 'ph-fedex-woocommerce-shipping'),
			'SAMPLE' 			=> __( 'Sample', 			'ph-fedex-woocommerce-shipping'),
			'SOLD' 				=> __( 'Sold', 	 			'ph-fedex-woocommerce-shipping'),
		)				
	),
	'email_notification'	  => array(
		'title'		   => __( 'Email Notification', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		 => '',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'options'		  => array(
			''					=> __('None',					'ph-fedex-woocommerce-shipping'),
			'CUSTOMER'			=> __('Recipient',			'ph-fedex-woocommerce-shipping'),
			'SHIPPER'			=> __('Shipper',			'ph-fedex-woocommerce-shipping'),
			'BOTH'				=> __('Recipient and Shipper',	'ph-fedex-woocommerce-shipping'), 
		),
		'desc_tip'	=> true,
		'description'	 => __( 'Select recipients for email notifications regarding the shipment from FedEx', 'ph-fedex-woocommerce-shipping' )
	),
	'output_format'   => array(
		'title'		   => __( 'Print Label Size', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'desc_tip'	=> true,
		'description'	 => __('8.5x11 indicates paper and 4x6 indicates thermal size.', 'ph-fedex-woocommerce-shipping' ),
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'options'		 => $label_output_format,				
	),
	'image_type'   => array(
		'title'		   => __( 'Image Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'desc_tip'	=> true,
		'description'	 => __('4x6 output format best fit with type PNG', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => 'pdf',
		'options'		 => array(
			'pdf' 							  	=> __( 'PDF', 'ph-fedex-woocommerce-shipping'),
			'png' 							  	=> __( 'PNG', 'ph-fedex-woocommerce-shipping'),
			'epl2' 							  	=> __( 'EPL2', 'ph-fedex-woocommerce-shipping'),
			'zplii' 							=> __( 'ZPLII', 'ph-fedex-woocommerce-shipping')
		)				
	),
	'show_label_in_browser'  => array(
		'title'			=> __( 'Display Labels in Browser for Individual Order', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable' ),
		'type'			=> 'checkbox',
		'default'		=> 'no',
		'description'	=> __( 'Enabling this will display the label in the browser instead of downloading it. Useful if your downloaded file is getting currupted because of PHP BOM (ByteOrderMark).', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'class'			=> 'fedex_label_tab',
	),
	'label_custom_scaling'  => array(
		'title'			=> __( 'Custom Scaling (%)', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable' ),
		'type'			=> 'decimal',
		'default'		=> '100',
		'description'	=> __( 'Provide a percentage value to scale the shipping label image based on your preference for bulk printing.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'class'			=> 'fedex_label_tab',
	),
	'doc_tab_content'	=> array(
		'title'			=> __( 'Doc Tab Content', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Applicable only for ZPLII Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		=> 'no',
		'class'			=> 'fedex_label_tab',
		'description'	=> '',
	),
	'doc_tab_orientation'   => array(
		'title' 		=> __( 'Doc Tab Orientation', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'			=> 'wc-enhanced-select fedex_label_tab',
		'default'		=> 'TOP_EDGE_OF_TEXT_FIRST',
		'options'		=> array(
			'TOP_EDGE_OF_TEXT_FIRST' 		=> __( 'Top Edge of Text First', 'ph-fedex-woocommerce-shipping'),
			'BOTTOM_EDGE_OF_TEXT_FIRST'		=> __( 'Bottom Edge of Text First', 'ph-fedex-woocommerce-shipping'),
		),
	),
	'tracking_shipmentid'	=> array(
			'title'			=> __( 'FedEx Shipment Tracking', 'ph-fedex-woocommerce-shipping' ),
			'label'			=> __( 'Enable Shipment Tracking for your WooCommerce Orders', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'checkbox',
			'default'		=> 'no',
			'class'			=> 'fedex_label_tab',
			'description'	=> '',
		),
	'disable_customer_tracking' => array(
			'title' 		=> __( 'Disable Tracking for Customers', 'ph-fedex-woocommerce-shipping' ),
			'label'			=> __( 'Disable the tracking message sent to the customers via Email and on the My Account page', 'ph-fedex-woocommerce-shipping' ),
			'type'			=> 'checkbox',
			'default'		=> 'no',
			'class'			=> 'fedex_label_tab',
			'description'	=> '',
		),
	'custom_message'		=> array(
			'title'				=> __( 'Custom Shipment Message', 'ph-fedex-woocommerce-shipping' ),
			'type'				=> 'text',
			'class'			=> 'fedex_label_tab',
			'description'		=> __( 'Define your own shipment message. Use the place holder tags [ID], [SERVICE] and [DATE] for Shipment Id, Shipment Service and Shipment Date respectively. Leave it empty for default message.<br>', 'ph-fedex-woocommerce-shipping' ),
			'css'				=> 'width:900px',
			//'id'				=> Ph_FedEx_Tracking_Util::TRACKING_SETTINGS_TAB_KEY.Ph_FedEx_Tracking_Util::TRACKING_MESSAGE_KEY,
			'placeholder'		=> 'Your order was shipped on [DATE] via [SERVICE]. To track shipment, please follow the link of shipment ID(s) [ID]',
			'desc_tip'		   => true
		),
	'cod_collection_type'   => array(
		'title'		   => __( 'COD Collection Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'desc_tip'	=> true,
		'description'	 => __('Identifies the type of funds FedEx should collect upon shipment delivery.', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => 'ANY',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'options'		 => array(
			'ANY' 							  	=> __( 'ANY', 						'ph-fedex-woocommerce-shipping'),
			'CASH' 							  	=> __( 'CASH', 						'ph-fedex-woocommerce-shipping'),
			'COMPANY_CHECK'					=> __( 'COMPANY CHECK',		'ph-fedex-woocommerce-shipping'),
			'PERSONAL_CHECK'					=> __( 'PERSONAL CHECK',		'ph-fedex-woocommerce-shipping'),
			'GUARANTEED_FUNDS'   			  	=> __( 'GUARANTEED FUNDS',			'ph-fedex-woocommerce-shipping')
			)				
	),
	'default_dom_service' => array(
		'title'		   => __( 'Default Service for Domestic Shipment', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'FedEx labels will be generated for this Domestic Service if no FedEx Shipping Method is selected on the cart page and the shipping address is a Domestic Address', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'select',
		'default'		 => '',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'options'		  => array_merge(array(''=>__('Select one', 'ph-fedex-woocommerce-shipping')), $dom_services)
	),
	'default_int_service'	=> array(
		'title'		   => __( 'Default Service for International Shipment', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'FedEx labels will be generated for this International Service if no FedEx Shipping Method is selected on the cart page and the shipping address is a International Address', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'select',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'default'		 => '',
		'options'		  => array_merge(array(''=>__('Select one', 'ph-fedex-woocommerce-shipping')), $int_services)
	),
	'item_description'  => array(
		'title'		   => __( 'Item Description', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	 => __( 'Required for UAE; Otherwise: Optional – This element is for the customer to describe the content of the package for customs clearance purposes. This applies to intra-UAE, intra-Columbia and intra-Brazil shipments.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'class'			=> 'fedex_label_tab',
	),
	'tin_number'  => array(
		'title'		   => __( 'TIN number', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'placeholder'	  => __( 'TIN number', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'TIN or VAT number .', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'class'			=> 'fedex_label_tab',
	),
	'tin_type'	=> array(
		'title'		   => __( 'TIN type', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'The category of the taxpayer identification', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'select',
		'default'		 => 'BUSINESS_STATE',
		'class'		   => 'wc-enhanced-select fedex_label_tab',
		'options'		  => array(
			'BUSINESS_STATE'	=> __('BUSINESS STATE', 'ph-fedex-woocommerce-shipping' ),
			'BUSINESS_NATIONAL'	=> __('BUSINESS NATIONAL', 'ph-fedex-woocommerce-shipping' ),
			'BUSINESS_UNION'	=> __('BUSINESS UNION', 'ph-fedex-woocommerce-shipping' ),
			'PERSONAL_NATIONAL'	=> __('PERSONAL NATIONAL', 'ph-fedex-woocommerce-shipping' ),
			'PERSONAL_STATE'	=> __('PERSONAL STATE', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'frontend_retun_label'	  => array(
		'title'		   => __( 'Enable Return Label in My Account Page', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'By enabling this the customers can generate the return label themself from my account page', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'class'			=> 'fedex_label_tab',
	),
	'frontend_retun_label_reason'	  => array(
		'title'				=> __( 'Reason for Return Label', 'ph-fedex-woocommerce-shipping' ),
		'label'				=> __( 'Allow customers to provide the reason for return before generating a return label', 'ph-fedex-woocommerce-shipping' ),
		'description'		=> __( "By enabling this customer will be able to generate return label only after providing the reason. Reason will be displayed in order notes.", 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'			=> true,
		'type'				=> 'checkbox',
		'default'			=> 'no',
		'class'				=> 'fedex_label_tab ph_fedex_return_label',
	),
	'int_return_label_reason'	=> array(
		'title' 		=> __( 'Customs Options Type', 'ph-fedex-woocommerce-shipping' ),
		'description' 	=> __( 'Details the return reason used for clearance processing of international dutiable outbound and international dutiable return shipments.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip' 		=> true,
		'type' 			=> 'select',
		'default' 		=> 'TRIAL',
		'class' 		=> 'wc-enhanced-select fedex_label_tab ph_return_label_return',
		'options' 		=> array(
			'COURTESY_RETURN_LABEL'	=> __('COURTESY_RETURN_LABEL', 'ph-fedex-woocommerce-shipping' ),
			'EXHIBITION_TRADE_SHOW'	=> __('EXHIBITION_TRADE_SHOW', 'ph-fedex-woocommerce-shipping' ),
			'FAULTY_ITEM' 			=> __('FAULTY_ITEM', 'ph-fedex-woocommerce-shipping' ),
			'FOLLOWING_REPAIR' 		=> __('FOLLOWING_REPAIR', 'ph-fedex-woocommerce-shipping' ),
			'FOR_REPAIR' 			=> __('FOR_REPAIR', 'ph-fedex-woocommerce-shipping' ),
			'ITEM_FOR_LOAN' 		=> __('ITEM_FOR_LOAN', 'ph-fedex-woocommerce-shipping' ),
			'OTHER' 				=> __('OTHER', 'ph-fedex-woocommerce-shipping' ),
			'REJECTED' 				=> __('REJECTED', 'ph-fedex-woocommerce-shipping' ),
			'REPLACEMENT' 			=> __('REPLACEMENT', 'ph-fedex-woocommerce-shipping' ),
			'TRIAL' 				=> __('TRIAL', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'int_return_label_desc'		=> array(
		'title' 		=> __( 'Customs Options Description', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'text',
		'class'			=> 'fedex_label_tab ph_return_label_desc',
		'desc_tip' 		=> true,
		'description' 	=> __( 'Specifies additional description about customs options. This is a required field when the customs options type is "OTHER".', 'ph-fedex-woocommerce-shipping' ),
	),
	'csb5_shipments'	=> array(
		'title' 		=> __( 'CSB V International Shipments - India', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		=> 'no',
		'class'			=> 'fedex_label_tab',
		'description'	=> __( 'Applicable for International Shipments', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
	),
	'ad_code'		=> array(
		'title' 		=> __( 'Bank AD Code', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'text',
		'class'			=> 'fedex_label_tab ph_fedex_csb5',
		'desc_tip' 		=> true,
		'description' 	=> __( 'Authorized Dealer code is normally given by the Bank.', 'ph-fedex-woocommerce-shipping' ),
	),
	'gst_shipment'	=> array(
		'title' 		=> __( 'Is GST', 'ph-fedex-woocommerce-shipping' ),
		'description' 	=> __( 'Specifies Shipment has a GST Invoice or not', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip' 		=> true,
		'type' 			=> 'select',
		'default' 		=> 'N',
		'class' 		=> 'wc-enhanced-select fedex_label_tab ph_fedex_csb5',
		'options' 		=> array(
			'G'	=> __('Yes', 'ph-fedex-woocommerce-shipping' ),
			'N'	=> __('No', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'under_bond'	=> array(
		'title' 		=> __( 'Shipments under BOND', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'select',
		'default' 		=> 'U',
		'class' 		=> 'wc-enhanced-select fedex_label_tab ph_fedex_csb5',
		'options' 		=> array(
			'B'	=> __('BOND', 'ph-fedex-woocommerce-shipping' ),
			'U'	=> __('Letter of Undertaking', 'ph-fedex-woocommerce-shipping' ),
			'-'	=> __('NONE', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'meis_shipment'	=> array(
		'title' 		=> __( 'MEIS Shipments', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'select',
		'default' 		=> 'M',
		'class' 		=> 'wc-enhanced-select fedex_label_tab ph_fedex_csb5',
		'options' 		=> array(
			'M'	=> __('MEIS', 'ph-fedex-woocommerce-shipping' ),
			'-'	=> __('Non MEIS', 'ph-fedex-woocommerce-shipping' ),
		)
	),
	'xa_show_all_shipping_methods' => array(
		'title'		   => __( 'Show All Services In Order Edit Page', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'yes',
		'class'			=> 'fedex_label_tab',
		'description'	 => __( 'Check this option to show all services in create label drop down(FEDEX).', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
	),
	'saturday_delivery_label' => array(
		'title'			=> __( 'FedEx Saturday Delivery', 'ph-fedex-woocommerce-shipping' ),
		'label'		   	=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		=> 'no',
		'class'			=> 'fedex_label_tab',
		'description'	=> __('This option will enable Saturday Delivery Shipping Services, It will effect for all orders.', 'ph-fedex-woocommerce-shipping'),
		'desc_tip'		=> true,
	),
	'remove_special_char_product' => array(
		'title'		   => __('Remove Special Characters from Product Name','ph-fedex-woocommerce-shipping'),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'class'			=> 'fedex_label_tab',
		'description'	 => __('While passing product details in request to the FedEx API, remove special characters from product name.','ph-fedex-woocommerce-shipping'),
		'desc_tip'		   => true,
	),
	'automate_package_generation'	  => array(
		'title'		   => __( 'Generate Packages Automatically After Order Received', 'ph-fedex-woocommerce-shipping' ),
		'label'			  => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),			
		'description'	 => __( 'This will generate packages automatically after order is received and payment is successful', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'default'		 => 'no',
		'class'			=> 'fedex_label_tab',
	),
	'automate_label_generation'	  => array(
		'title'		   => __( 'Generate Shipping Labels Automatically After Order Received', 'ph-fedex-woocommerce-shipping' ),
		'label'			  => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),			
		'description'	 => __( 'This will generate shipping labels automatically after order is received and payment is successful', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'			=> 'fedex_label_tab',
		'default'		 => 'no'
	),
	'auto_label_trigger' 	=> array(
		'title' 		=> __( 'Trigger Automatic Label Generation', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		=> 'thankyou_page',
		'class'			=> 'fedex_label_tab',
		'options' 		=> array(
			'thankyou_page'	=> __( 'Default - When the order is placed successfully', 'ph-fedex-woocommerce-shipping'),
			'payment_status'=> __( 'When the payment is confirmed', 'ph-fedex-woocommerce-shipping'),
		),
	),
	'allow_label_btn_on_myaccount'	  => array(
		'title'		   => __( 'Allow customers to print label from their My Account->Orders page', 'ph-fedex-woocommerce-shipping' ),
		'label'			  => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),			
		'description'	 => __( 'A button will be available for downloading the label and printing', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'			=> 'fedex_label_tab',
		'default'		 => 'no'
	),
	'auto_email_label'	=> array(
		'title'			=> __( 'Send Shipping Label To', 'ph-fedex-woocommerce-shipping' ),
		'description'	=> __( 'Choose the recipient who will get the shipping label(s) via Email.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'type'			=> 'multiselect',
		'class'			=> 'fedex_label_tab chosen_select',
		'default'		=> '',
		'options'		=> $auto_email_label_options,
	),
	'email_subject'	  => array(
		'title'		  	=> __( 'Email Subject', 'ph-fedex-woocommerce-shipping' ),
		'description'	=> __( 'Subject of Email sent for FedEx Label. Supported Tags : [ORDER NO] - Order Number.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip' 		=> true,
		'type'			=> 'text',
		'placeholder'	=> __( 'Shipment Label For Your Order', 'ph-fedex-woocommerce-shipping' ).' [ORDER NO]',
		'class'			=>	'fedex_label_tab',
	),
	'zpl_in_email'		=> array(
		'title'			=> __( 'Send ZPL Label content via email', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_label_tab zpl_in_email',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'Enabling this option will send the ZPL label content as a plain text email to the shipper, recipient or the vendors, as per the plugin settings.', 'ph-fedex-woocommerce-shipping' ),
	),
	'email_content'   => array(
		'type'          => 'fedex_email_format',
	),

	// Commercial Invoice
	'title_commercial_invoice' 		=> array(
		'title' 		=> __( 'Commercial Invoice Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_commercial_invoice_tab',
		'description'	=> __( 'Configure the commercial invoice related settings here.', 'ph-fedex-woocommerce-shipping' ),
	),
	'commercial_invoice' => array(
		'title'			=> __( 'Commercial Invoice', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_commercial_invoice_tab',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'On enabling this option Commercial Invoice will be received as an additional label. Applicable for international shipping only.', 'ph-fedex-woocommerce-shipping' ),
	),
	'etd_label' => array(
		'title'			=> __( 'ETD - Electronic Trade Documents', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_commercial_invoice_tab commercial_invoice_toggle',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'On enabling this option the shipment details will be sent electronically and ETD will be printed in the Shipping Label', 'ph-fedex-woocommerce-shipping' ),
	),
	//PDS-149
	'invoice_commodity_value'   => array(
		'title'		   => __( 'Price Value', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'		   => 'wc-enhanced-select fedex_commercial_invoice_tab commercial_invoice_toggle',
		'desc_tip'	=> true,
		'description'	 => __('Select whether you want to display the discounted price, original product price or the declared value to be printed on the commercial invoice.', 'ph-fedex-woocommerce-shipping' ),
		'default'		 => $default_invoice_commodity_value,
		'options'		 => array(
			'discount_price' 			=> __( 'Discounted', 'ph-fedex-woocommerce-shipping'),
			'product_declared' 			=> __( 'Product', 'ph-fedex-woocommerce-shipping'),
			'declared_price' 			=> __( 'Declared', 'ph-fedex-woocommerce-shipping')
		)				
	),	
	'commercial_invoice_shipping' => array(
		'title'			=> __( 'Shipping Charges in Commercial Invoice', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> 'Enabling this option will display shipping charges (if any) in Commercial Invoice.'
	),
	'commercial_invoice_order_currency' => array(
		'title'			=> __( 'Order Currency in Commercial Invoice', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Supports only FOX-Currency Switcher Multi-Currency', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> 'Enabling this option will display Order Currency in Commercial Invoice.'
	),
	'shipment_comments'  => array(
		'title'		   	=> __( 'Comments', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'textarea',
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
		'default' 		=> '',
		'desc_tip' 		=> true,
		'description'	=> __( 'Any comments that need to be communicated about this shipment.', 'ph-fedex-woocommerce-shipping' ),
		'css' 			=> 'width:44%;height: 100px;',
	),
	'special_instructions'  => array(
		'title'			=> __( 'Special Instructions', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'textarea',
		'description'	=> __( 'Specify Special Instructions for Commercial Invoice.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'css' 			=> 'width:44%;height: 100px;',
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
	),
	'custom_declaration_statement'  => array(
		'title'			=> __( 'Customs Declaration Statement (Applicable for non-US shippers)', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'textarea',
		'desc_tip'		=> true,
		'css' 			=> 'width:44%;height: 100px;',
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
	),
	'payment_terms'  => array(
		'title'			=> __( 'Payment Terms', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	=> __( 'Specify Payment Terms for Commercial Invoice.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
	),
	'csb_termsofsale'	=> array(
		'title' 		=> __( 'Terms of Sale', 'ph-fedex-woocommerce-shipping' ),
		'description' 	=> __( 'Select Terms of Sale for International Shipments.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip' 		=> true,
		'type' 			=> 'select',
		'default' 		=> 'FOB',
		'class' 		=> 'wc-enhanced-select fedex_commercial_invoice_tab',
		'options' 		=> array(
			'none'  => __( 'None', 'ph-fedex-woocommerce-shipping' ),
			'FOB'	=> __( 'Free On Board', 'ph-fedex-woocommerce-shipping' ),
			'CFR'	=> __( 'Cost And Freight', 'ph-fedex-woocommerce-shipping' ),
			'CIF'	=> __( 'Cost Insurance and Freight', 'ph-fedex-woocommerce-shipping' ),
			'DAT'	=> __( 'Delivered At Terminal', 'ph-fedex-woocommerce-shipping' ),
			'DDP'	=> __( 'Delivered Duty Paid', 'ph-fedex-woocommerce-shipping' ),
			'DDU' 	=> __( 'Delivered Duty Unpaid', 'ph-fedex-woocommerce-shipping' ),
			'EXW'	=> __( 'ExWorks', 'ph-fedex-woocommerce-shipping' ),
			'FCA'	=> __( 'Free Carrier', 'ph-fedex-woocommerce-shipping' ),
			'CIP'	=> __( 'Carraige Insurance Paid', 'ph-fedex-woocommerce-shipping' ),
			'CPT'	=> __( 'Carriage Paid To', 'ph-fedex-woocommerce-shipping' ),
			'DAP'	=> __( 'Delivered At Place', 'ph-fedex-woocommerce-shipping' )
		)
	),
	'global_hs_code'  => array(
		'title'			=> __( 'HS Tariff Number ', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'description'	=> __( 'This HS Code will be used for Commercial Invoice, when Product Level HS Code is not available.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
		'class'			=> 'commercial_invoice_toggle fedex_commercial_invoice_tab',
	),
	'company_logo' => array(
		'title' 		=> __('Company Logo', 'ph-fedex-woocommerce-shipping'),
		'description' 	=> sprintf('<span class="button" id="company_logo_picker">Choose Image</span> <div id="company_logo_result"></div>'),
		'class'			=> 'commercialinvoice-image-uploader fedex_commercial_invoice_tab',
		'type' 			=> 'text',
		'placeholder' 	=> 'Upload an image to set Company Logo on Commercial Invoice'
	),
	'digital_signature' => array(
		'title' 		=> __('Digital Signature', 'ph-fedex-woocommerce-shipping'),
		'description' 	=> sprintf('<span class="button" id="digital_signature_picker">Choose Image</span> <div id="digital_signature_result"></div>'),
		'class'			=> 'commercialinvoice-image-uploader fedex_commercial_invoice_tab',
		'type' 			=> 'text',
		'placeholder' 	=> 'Upload an image to set Digital Signature on Commercial Invoice'
	),
	//PRO_FORMA_INVOICE
	'ph_pro_forma_invoice' => array(
		'title'			=> __( 'Pro Forma Invoice', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_commercial_invoice_tab',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'On enabling this option PRO FORMA INVOICE will be received as an additional label. Applicable for international shipping only.', 'ph-fedex-woocommerce-shipping' ),
	),
	//USMCA Certificate
	'usmca_certificate' => array(
		'title'			=> __( 'USMCA Certificate', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_commercial_invoice_tab',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'On enabling this option USMCA Certificate will be received as an additional label. Applicable for international shipping only.', 'ph-fedex-woocommerce-shipping' ),
	),
	'usmca_ci_certificate_of_origin' => array(
		'title'			=> __( 'USMCA Commercial Invoice Certificate', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_commercial_invoice_tab',
		'default'		=> 'no',
		'desc_tip'		=> true,
		'description'	=> __( 'On enabling this option USMCA Commercial Invoice Certification Of Origin will be received as an additional label. Applicable for international shipping only.', 'ph-fedex-woocommerce-shipping' ),
	),
	'certifier_specification'   => array(
		'title'		   => __( 'Certifier Specification', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'		   => 'wc-enhanced-select fedex_commercial_invoice_tab usmca_and_usmcaci_toggle',
		'default'		 => 'IMPORTER',
		'options'		 => array(
			'EXPORTER' 			=> __( 'Exporter', 'ph-fedex-woocommerce-shipping'),
			'IMPORTER' 			=> __( 'Importer', 'ph-fedex-woocommerce-shipping'),
			'PRODUCER' 			=> __( 'Producer', 'ph-fedex-woocommerce-shipping')
		)				
	),
	'producer_specification'   => array(
		'title'		    => __( 'Producer Specification', 'ph-fedex-woocommerce-shipping' ),
		'type' 			=> 'select',
		'class'		   	=> 'wc-enhanced-select fedex_commercial_invoice_tab usmca_and_usmcaci_toggle',
		'default'		=> 'SAME_AS_EXPORTER',
		'options'		=> array(
			'SAME_AS_EXPORTER' 	=> __( 'Same as Exporter', 'ph-fedex-woocommerce-shipping'),
			'VARIOUS' 			=> __( 'Various', 'ph-fedex-woocommerce-shipping'),
		)				
	),
	'importer_specification'   => array(
		'title'		    => __( 'Importer Specification', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'		    => 'wc-enhanced-select fedex_commercial_invoice_tab usmca_toggle',
		'default'		=> 'UNKNOWN',
		'options'		=> array(
			'UNKNOWN' 			=> __( 'Unknown', 'ph-fedex-woocommerce-shipping'),
			'VARIOUS' 			=> __( 'Various', 'ph-fedex-woocommerce-shipping'),
		)				
	),
	'blanket_begin_period' => array(
		'title' 		=> __( 'Blanket Period Begin Date', 'ph-fedex-woocommerce-shipping' ),
		'label' 		=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip' 		=> true,
		'type' 			=> 'date',
		'css'			=> 'width:400px',
		'description' 	=> __('Begin date of the blanket period. It is the date upon which the Certificate becomes applicable to the good covered by the blanket Certificate (it may be prior to the date of signing this Certificate)', 'ph-fedex-woocommerce-shipping'),
		'class'			=> 'fedex_commercial_invoice_tab usmca_toggle'
	),
	'blanket_end_period' => array(
		'title' 		=> __( 'Blanket Period End Date', 'ph-fedex-woocommerce-shipping' ),
		'label' 		=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip' 		=> true,
		'type' 			=> 'date',
		'css'			=> 'width:400px',
		'description' 	=> __('End Date of the blanket period. It is the date upon which the blanket period expires', 'ph-fedex-woocommerce-shipping'),
		'class'			=> 'fedex_commercial_invoice_tab usmca_toggle'
	),

	'title_packaging'		   => array(
		'title'		   => __( 'Packaging Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_packaging_tab',
		'description'	 => __( 'Choose the packing options suitable for your store here.', 'ph-fedex-woocommerce-shipping' ),
	),
	'packing_method'   => array(
		'title'		   => __( 'Parcel Packing Method', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		 => 'weight_based',
		'class'		   => 'packing_method wc-enhanced-select fedex_packaging_tab',
		'options'		 => array(
			'per_item'	   => __( 'Pack items individually', 'ph-fedex-woocommerce-shipping' ),
			'box_packing'	=> __( 'Pack into boxes with weights and dimensions', 'ph-fedex-woocommerce-shipping' ),
			'weight_based'   => __( 'Recommended: Weight based, calculate shipping based on weight', 'ph-fedex-woocommerce-shipping' ),
		),
		'desc_tip'	=> true,
		'description'	 => __( 'Determine how items are packed before being sent to FedEx.', 'ph-fedex-woocommerce-shipping' ),
	),
	'packing_algorithm'  	 => array(
		'title'		   		=> __( 'Packing Algorithm', 'ph-fedex-woocommerce-shipping' ),
		'type'				=> 'select',
		'default'		 	=> 'volume_based',
		'class'		   		=> 'fedex_packaging_tab wc-enhanced-select box_packing_algorithm',
		'options'		 	=> array(
			'volume_based'	=> __( 'Default: Volume Based Packing', 'ph-fedex-woocommerce-shipping' ),
			'stack_first'	=> __( 'Stack First Packing', 'ph-fedex-woocommerce-shipping' ),
			'new_algorithm'	=> __( 'Based on Volume Used * Item Count', 'ph-fedex-woocommerce-shipping' ),	
		),
	),
	'stack_to_volume'	=> array(
		'title'   			=> __( 'Convert Stack First to Volume Based', 'ph-fedex-woocommerce-shipping' ),
		'type'				=> 'checkbox',
		'class'				=> 'fedex_stack_to_volume fedex_packaging_tab',
		'label'				=> __( 'Automatically change packing method when the products are packed in a box and the filled up space is less less than 44% of the box volume', 'ph-fedex-woocommerce-shipping' ),
		'default' 			=> 'yes',
	),
	'volumetric_weight'	=> array(
		'title'   			=> __( 'Enable Volumetric weight', 'ph-fedex-woocommerce-shipping' ),
		'type'				=> 'checkbox',
		'class'				=> 'fedex_weight_based_option fedex_packaging_tab',
		'label'				=> __( 'This option will calculate the volumetric weight. Then a comparison is made on the total weight of cart to the volumetric weight.</br>The higher weight of the two will be sent in the request.', 'ph-fedex-woocommerce-shipping' ),
		'default' 			=> 'no',
	), 

	'box_max_weight'		   => array(
		'title'		   => __( 'Max Package Weight', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'default'		 => '10',
		'class'		   => 'fedex_weight_based_option fedex_packaging_tab',
		'desc_tip'		=> true,
		'description'	 => __( 'Maximum weight allowed for single box.', 'ph-fedex-woocommerce-shipping' ),
	),

	//weight_packing_process
	'weight_pack_process'   => array(
		'title'		   => __( 'Packing Process', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default'		 => '',
		'class'		   => 'fedex_weight_based_option wc-enhanced-select fedex_packaging_tab',
		'options'		 => array(
			'pack_descending'	   => __( 'Pack heavier items first', 'ph-fedex-woocommerce-shipping' ),
			'pack_ascending'		=> __( 'Pack lighter items first.', 'ph-fedex-woocommerce-shipping' ),
			'pack_simple'			=> __( 'Pack purely divided by weight.', 'ph-fedex-woocommerce-shipping' ),
		),
		'desc_tip'	=> true,
		'description'	 => __( 'Select your packing order.', 'ph-fedex-woocommerce-shipping' ),
	),

	'boxes'  => array(
		'type'			=> 'box_packing'
	),
	'enable_speciality_box'	  => array(
		'title'	   => __( 'Include Speciality Boxes', 'ph-fedex-woocommerce-shipping' ),
		'label'	   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'class'		  => 'speciality_box fedex_packaging_tab',
		'type'		=> 'checkbox',
		'default'	 => 'yes',
		'desc_tip'	=> true,
		'description' => __( 'Check this to load Speciality Boxes with boxes.', 'ph-fedex-woocommerce-shipping' ),
	),

	// Hazmat Packaging
	'hazmat_enabled'	  => array(
		'title'		   => __( 'Hazardous(HazMat) Packaging', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Enable this option if you are shipping Hazardous Materials', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'			=> 'fedex_packaging_tab',
		'default'		 => 'no'
	),

	'hp_packaging_type' => array(
		'title'			=> __( 'Type of Packaging', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'			=> 'wc-enhanced-select fedex_packaging_tab ph_fedex_hazmat_grp',
		'options'		=> array(
			'1'			=> __( 'Drum', 'ph-fedex-woocommerce-shipping' ),
			'2'			=> __( 'Wooden Barrel', 'ph-fedex-woocommerce-shipping' ),
			'3'			=> __( 'Jerrican', 'ph-fedex-woocommerce-shipping' ),
			'4'			=> __( 'Box', 'ph-fedex-woocommerce-shipping' ),
			'5'			=> __( 'Bag', 'ph-fedex-woocommerce-shipping' ),
			'6'			=> __( 'Composite Package', 'ph-fedex-woocommerce-shipping' ),
			'7'			=> __( 'Pressure Receptacle', 'ph-fedex-woocommerce-shipping' ),
		),
		'desc_tip'		=> true,
		'description'	=> __( 'Choose Type of Packaging', 'ph-fedex-woocommerce-shipping' ),
	),

	'hp_packaging_material' => array(
		'title'			=> __( 'Packaging Material', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'class'			=> 'wc-enhanced-select fedex_packaging_tab ph_fedex_hazmat_grp',
		'options'		=> array(
			'A'			=> __( 'Steel', 'ph-fedex-woocommerce-shipping' ),
			'B'			=> __( 'Aluminum', 'ph-fedex-woocommerce-shipping' ),
			'C'			=> __( 'Natural Wood', 'ph-fedex-woocommerce-shipping' ),
			'D'			=> __( 'Plywood', 'ph-fedex-woocommerce-shipping' ),
			'F'			=> __( 'Reconstituted Wood', 'ph-fedex-woocommerce-shipping' ),
			'G'			=> __( 'Fiberboard', 'ph-fedex-woocommerce-shipping' ),
			'H'			=> __( 'Plastic', 'ph-fedex-woocommerce-shipping' ),
			'L'			=> __( 'Textile', 'ph-fedex-woocommerce-shipping' ),
			'M'			=> __( 'Paper, Multi-wall', 'ph-fedex-woocommerce-shipping' ),
			'N'			=> __( 'Metal', 'ph-fedex-woocommerce-shipping' ),
			'P'			=> __( 'Glass, Porcelain or Stoneware', 'ph-fedex-woocommerce-shipping' ),
		),
		'desc_tip'		=> true,
		'description'	=> __( 'Choose Packaging Material', 'ph-fedex-woocommerce-shipping' ),
	),

	
	

	
	'title_pickup'		   => array(
		'title'		   => __( 'Pickup Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_pickup_tab',
		'description'	 => __( 'Configure the pickup options here to avail FedEx pickup for your orders', 'ph-fedex-woocommerce-shipping' ),
	),
	'pickup_enabled'	  => array(
		'title'		   => __( 'Enable FedEx Pickup', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Enable this to setup pickup request', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'			=> 'fedex_pickup_tab',
		'default'		 => 'no'
	),
	'use_pickup_address'	  => array(
		'title'		   => __( 'Use Different Pickup Address', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Check this to set a defferent store address to pick up from', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'checkbox',
		'class'			  => 'wf_fedex_pickup_grp fedex_pickup_tab',
		'default'		 => 'no',
	),
	'pickup_contact_name'		   => array(
		'title'		   => __( 'Contact Person Name', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Contact person name', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_company_name'		   => array(
		'title'		   => __( 'Pickup Company Name', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Name of the company', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_phone_number'		   => array(
		'title'		   => __( 'Pickup Phone Number', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Contact number', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_address_line'		   => array(
		'title'		   => __( 'Pickup Address', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Address line', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_address_city'		   => array(
		'title'		   => __( 'Pickup City', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'City', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_address_state_code'		   => array(
		'title'		   => __( 'Pickup State Code', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'State code. Eg: CA', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_address_postal_code'		   => array(
		'title'		   => __( 'Pickup Zipcode', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Postal code', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_address_country_code'		   => array(
		'title'		   => __( 'Pickup Country Code', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Country code Eg: US', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'text',
		'class'			  => 'wf_fedex_pickup_grp wf_fedex_pickup_address_grp fedex_pickup_tab',
		'default'		 => '',
	),
	'pickup_start_time'		   => array(
		'title'		   => __( 'Pickup Start Time', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Items will be ready for pickup by this time from shop', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'select',
		'class'			  => 'wf_fedex_pickup_grp wc-enhanced-select fedex_pickup_tab',
		'default'		 => current($pickup_start_time_options),
		'options'		  => $pickup_start_time_options,
	),
	'pickup_close_time'		   => array(
		'title'		   => __( 'Company Close Time', 'ph-fedex-woocommerce-shipping' ),
		'description'	 => __( 'Your shop closing time. It must be greater than company open time', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		   => true,
		'type'			=> 'select',
		'class'			  => 'wf_fedex_pickup_grp wc-enhanced-select fedex_pickup_tab',
		'default'		 => '18',
		'options'		  => $pickup_close_time_options,
	),

	'freight'		   => array(
		'title'		   => __( 'FedEx LTL Freight Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_freight_tab',
		'description'	 => __( 'Please enter your FedEx Freight Account Details & Shipper’s Address below.<br><b>Note:</b> The City field of the Shipping Address is mandatory to display FedEx Freight Rates.', 'ph-fedex-woocommerce-shipping' ),
	),
	'freight_enabled'	  => array(
		'title'		   => __( 'FedEx Freight', 'ph-fedex-woocommerce-shipping' ),
		'label'		   => __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_freight_tab',
		'default'		 => 'no'
	),
	'freight_number' => array(
		'title'	   => __( 'Freight Account Number', 'ph-fedex-woocommerce-shipping' ),
		'type'		=> 'text',
		'class'			=> 'fedex_freight_tab freight_group ',
		'description' => '',
		'default'	 => '',
	),
	'freight_bill_street'		   => array(
		'title'		   => __( 'Billing Street Address', 'ph-fedex-woocommerce-shipping' ),
		'class'			=> 'fedex_freight_tab freight_group',
		'type'			=> 'text',
		'default'		 => ''
	),
	'billing_street_2'		   => array(
		'title'		   => __( 'Billing Street Address 2', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		 => ''
	),
	'freight_billing_city'		   => array(
		'title'		   => __( 'Billing City', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		 => ''
	),
	'freight_billing_state'		   => array(
		'title'		   => __( 'Billing State Code', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		 => '',
	),
	'billing_postcode'		   => array(
		'title'		   => __( 'Billing Zipcode', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		 => '',
	),
	'billing_country'		   => array(
		'title'		   => __( 'Billing Country Code', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			=> 'fedex_freight_tab ph_fedex_frieght_billing_country freight_group',
		'default'		 => '',
	),
	
	'freight_class'		   => array(
		'title'		   => __( 'Default Freight Class', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'	=> true,
		'description'	 => sprintf( __( 'This is the default freight class for shipments. This can be overridden using <a href="%s">shipping classes</a>', 'ph-fedex-woocommerce-shipping' ), admin_url( 'edit-tags.php?taxonomy=product_shipping_class&post_type=product' ) ),
		'type'			=> 'select',
		'default'		 => '50',
		'class'		   => 'wc-enhanced-select fedex_freight_tab freight_group',
		'options'		 => $freight_classes
	),

	'freight_document_type' 		=> array(
		'title' 		=> __( 'Freight Document Type', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'select',
		'default' 		=> 'VICS_BILL_OF_LADING',
		'class' 		=> 'wc-enhanced-select fedex_freight_tab freight_group',
		'options' 		=> array(
			'VICS_BILL_OF_LADING' 						=> __( 'VICS BILL OF LADING', 'ph-fedex-woocommerce-shipping' ),
			'FEDEX_FREIGHT_STRAIGHT_BILL_OF_LADING' 	=> __( 'FEDEX FREIGHT STRAIGHT BILL OF LADING', 'ph-fedex-woocommerce-shipping' ),
		),
	),

	'lift_gate_for_delivery'	=> array(
		'title'			=> __( 'Lift Gate Delivery', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		=> 'no'
	),

	'lift_gate_for_pickup'	=> array(
		'title'			=> __( 'Lift Gate Pickup', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		=> 'no'
	),

	'inside_delivery'	=> array(
		'title'			=> __( 'Inside Delivery', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		=> 'no'
	),
	
	'inside_pickup'	=> array(
		'title'			=> __( 'Inside Pickup', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_freight_tab freight_group',
		'default'		=> 'no'
	),

	'advanced'		   	=> array(
		'title'		   	=> __( 'FedEx Advanced Settings', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'title',
		'class'			=> 'fedex_advanced_tab',
		// 'description'	 => __( 'FedEx Advanced Settings', 'ph-fedex-woocommerce-shipping' ),
	),

	'default_recipient_phone'		=> array(
		'title'			=> __( 'Recipient Phone Number', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_advanced_tab',
		'description'	=> __( 'Enable this option & provide a default phone number that FedEx will use on the shipping labels.', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
	),

	'default_recipient_phone_num'	=> array(
		'title'		    => __( 'Enter Phone Number', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'text',
		'class'			=> 'fedex_advanced_tab',
		'default'		=> '',
	),

	'encode_uploaded_document'		=> array(
		'title'			=> __( 'Encode Uploaded Document', 'ph-fedex-woocommerce-shipping' ),
		'label'			=> __( 'Enable', 'ph-fedex-woocommerce-shipping' ),
		'type'			=> 'checkbox',
		'class'			=> 'fedex_advanced_tab',
		'default'       => 'yes',
		'description'	=> __( 'Encodes the Image for Company Logo & Digital Signature for the Commercial Invoice', 'ph-fedex-woocommerce-shipping' ),
		'desc_tip'		=> true,
	),
	
	// Help & Support

	'help_and_support'  => array(
		'type'			=> 'help_support_section'
	),
	
);