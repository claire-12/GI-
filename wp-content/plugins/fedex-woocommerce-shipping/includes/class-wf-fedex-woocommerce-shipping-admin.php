<?php

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;

class wf_fedex_woocommerce_shipping_admin{

	//PDS-179
	public $prioritizedSignatureOption 	= array( 5=>'ADULT',4=>'DIRECT',3=>'INDIRECT',2=>'SERVICE_DEFAULT',1=>'NO_SIGNATURE_REQUIRED',0=>'' );
	
	/**
	 * Rateservice Version
	 */
	public $rateservice_version;
	/**
	 * Settings
	 */
	public $settings;
	/**
	 * Custom Services
	 */
	public $custom_services;
	/**
	 * Soap Method
	 */
	public $soap_method;
	/**
	 * Label Type
	 */
	public $label_image_type, $image_type;
	/**
	 * Packing Method
	 */
	public $packing_method;
	/**
	 * Debug
	 */
	public $debug;
	/**
	 * Mode
	 */
	public $production;
	/**
	 * Weight and dimensions
	 */
	public $dimension_unit, $weight_unit, $weight_dimensions_manual;
	/**
	 * Admin Details Variables
	 */
	public $account_number, $meter_number, $api_key, $api_pass;
	/**
	 * Hold At Location
	 */
	public $hal_version, $hold_at_location, $attribute_type, $location_attributes, $request_hash;
	
	/**
	 * General Settings
	 */
	public $saturday_delivery, $freight_enabled, $custom_scaling, $xa_show_all_shipping_methods, $display_fedex_meta_box_on_order, $fedex_tracking, $csb_termsofsale, $ph_button_names, $min_order_amount_for_insurance;
	/**
	 * Home Delivery Variables
	 */
	public $home_delivery_premium, $home_delivery_premium_type;
	/**
	 * Client Side Reset
	 */
	public $client_side_reset;
	/**
	 * ETD Label
	 */
	public $etd_label;
	/**
	 * Origin Address Variables
	 */
	public $origin_country, $origin_state;
	/**
	 * Signature Variables
	 */
	public $signature_temp, $signature;

	/**
	 * Bulk actions
	 */
	public $fedex_bulk_actions;
	
	public function __construct(){
		add_action('init', array($this, 'wf_init'));
		
		if (is_admin()) {
			$this->init_bulk_printing();
			add_action('add_meta_boxes', [$this, 'wf_add_fedex_metabox'], 10, 2);
		}
		if ( isset( $_GET['wf_fedex_generate_packages'] ) ) {
			add_action('init', array($this, 'wf_fedex_generate_packages'));
		}

		if (isset($_GET['wf_fedex_createshipment'])) {
			add_action('init', array($this, 'wf_fedex_createshipment'));
		}
		
		if (isset($_GET['wf_fedex_generate_packages_rates'])) {
			add_action('init', array($this, 'wf_fedex_generate_packages_rates'));
		}
		
		if (isset($_GET['wf_fedex_additional_label'])) {
			add_action('init', array($this, 'wf_fedex_additional_label'));
		}
		
		if (isset($_GET['wf_fedex_viewlabel'])) {
			add_action('init', array($this, 'wf_fedex_viewlabel'));
		}

		if (isset($_GET['wf_fedex_void_shipment'])) {
			add_action('init', array($this, 'wf_fedex_void_shipment'));
		}
		
		if (isset($_GET['wf_clear_history'])) {
			add_action('init', array($this, 'wf_clear_history'));
		}

		if (isset($_GET['ph_client_reset_link'])) {  
			add_action('init', array($this, 'wf_clear_history'));
		}

		if (isset($_GET['wf_create_return_label'])) {
			add_action('init', array($this, 'wf_create_return_label'));
		}
		
		if (isset($_GET['wf_fedex_viewReturnlabel'])) {
			add_action('init', array($this, 'wf_fedex_viewReturnlabel'));
		}

		if (isset($_GET['ph_fedex_view_additional_return_label'])) {
			add_action('init', array($this, 'ph_fedex_view_additional_return_label'));
		}

		add_action( 'wp_ajax_xa_fedex_validate_credential', array($this,'xa_fedex_validate_credentials'), 10, 1 );
		
		add_action('admin_notices',array(new wf_admin_notice, 'throw_notices'));
		add_action('woocommerce_admin_order_actions_end', array(&$this, 'fedex_action_column'),2);
	}

	public function wf_init(){
		global $woocommerce;
		$this->rateservice_version			  = 31;
		$this->settings 			= get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );
		
		$this->weight_dimensions_manual 	= isset($this->settings['manual_wgt_dimensions']) ? $this->settings['manual_wgt_dimensions'] : 'no';
		$this->custom_services 				= isset($this->settings['services']) ? $this->settings['services'] : '';
		$this->image_type 					= isset($this->settings['image_type']) ? $this->settings['image_type'] : '';
		$this->packing_method 				= isset($this->settings[ 'packing_method']) ? $this->settings[ 'packing_method'] : '';
		$this->debug 						= ( isset($this->settings[ 'debug' ]) && ( $bool = $this->settings[ 'debug' ] ) && $bool == 'yes' ) ? true : false;
		$this->xa_show_all_shipping_methods		= isset( $this->settings['xa_show_all_shipping_methods'] ) && $this->settings['xa_show_all_shipping_methods'] == 'yes' ? true : false;
		$this->display_fedex_meta_box_on_order 	= isset($this->settings['display_fedex_meta_box_on_order']) ? $this->settings['display_fedex_meta_box_on_order'] : 'yes';

		$this->production					= ( isset($this->settings['production']) && ( $bool = $this->settings['production'] ) && $bool == 'yes' ) ? true : false;

		if(isset($this->settings['dimension_weight_unit']) && $this->settings['dimension_weight_unit'] == 'LBS_IN'){
			$this->dimension_unit 			= 	'in';
			$this->weight_unit 				= 	'lbs';
		}else{
			$this->dimension_unit 			= 	'cm';
			$this->weight_unit 				= 	'kg';
		}
		
		$this->set_origin_country_state();

		$this->account_number 		= isset($this->settings[ 'account_number' ]) && !empty($this->settings['account_number']) ? $this->settings['account_number'] : '';
		$this->meter_number 		= isset($this->settings[ 'meter_number' ]) && !empty($this->settings['meter_number']) ? $this->settings['meter_number'] : '';
		$this->api_key 				= isset($this->settings[ 'api_key' ]) && !empty($this->settings['api_key']) ? $this->settings['api_key'] : '';
		$this->api_pass 			= isset($this->settings[ 'api_pass' ]) && !empty($this->settings['api_pass']) ? $this->settings['api_pass'] : '';
		$this->hold_at_location 	= isset($this->settings['hold_at_location']) && $this->settings['hold_at_location'] == 'yes' ? true : false;
		$this->saturday_delivery 	= ( isset($this->settings['saturday_delivery']) && !empty($this->settings['saturday_delivery']) && $this->settings['saturday_delivery'] == 'yes' ) ? true : false;
		$this->freight_enabled 		= ( $bool = isset( $this->settings[ 'freight_enabled'] ) ? $this->settings[ 'freight_enabled'] : 'no' ) && $bool == 'yes' ? true : false;
		$this->custom_scaling 		= ( isset($this->settings['label_custom_scaling']) && !empty($this->settings['label_custom_scaling']) ) ? $this->settings['label_custom_scaling'] : '100';
		$this->client_side_reset 	= ( isset($this->settings['client_side_reset']) && !empty($this->settings['client_side_reset']) && $this->settings['client_side_reset'] == 'yes' ) ? true : false;
		$this->etd_label 			= (isset($this->settings['etd_label']) && ($this->settings['etd_label'] == 'yes')) ? true : false;
		$this->home_delivery_premium 		= (isset($this->settings['home_delivery_premium']) && ($this->settings['home_delivery_premium'] == 'yes')) ? true : false;
		$this->home_delivery_premium_type 	=	( isset($this->settings['home_delivery_premium_type']) && !empty($this->settings['home_delivery_premium_type']) ) ? $this->settings['home_delivery_premium_type'] : '';
		$this->fedex_tracking 		= (isset($this->settings['fedex_tracking']) && ($this->settings['fedex_tracking'] == 'yes')) ? true : false;
		$this->label_image_type 	=	( isset($this->settings['image_type']) && !empty($this->settings['image_type']) ) ? $this->settings['image_type'] : '';
		$this->csb_termsofsale 	= ( isset($this->settings['csb_termsofsale']) && !empty($this->settings['csb_termsofsale']) ) ? $this->settings['csb_termsofsale'] : '';

		// Hold At Location
		if( $this->hold_at_location ) {
			add_action( 'woocommerce_admin_order_data_after_shipping_address', array($this, 'ph_editable_hold_at_location'), 15 );
			add_action( 'woocommerce_process_shop_order_meta', array($this, 'ph_save_hold_at_location'), 15 );
		}

		// Edit order page button titles and tooltips
		$this->ph_button_names= [                                            
            'print_label_btn'               => __( 'Print Label', 'ph-fedex-woocommerce-shipping'),
            'void_shipment_btn'             => __( 'Void Shipment', 'ph-fedex-woocommerce-shipping'),
            'generate_return_label_btn'     => __( 'Generate return label', 'ph-fedex-woocommerce-shipping'),
            're_generate_package_btn'       => __( 'Re-Generate Package(s)', 'ph-fedex-woocommerce-shipping'),
            'add_package_btn'               => __( 'Add Package', 'ph-fedex-woocommerce-shipping'),
            'create_shipment_btn'           => __( 'Create Shipment', 'ph-fedex-woocommerce-shipping'), 
            'calculate_cost_btn'            => __( 'Calculate Cost', 'ph-fedex-woocommerce-shipping'),
            'generate_package_btn'          => __( 'Generate Packages', 'ph-fedex-woocommerce-shipping'),
            'clear_data_btn'                => __( 'Clear Data', 'ph-fedex-woocommerce-shipping'),
            'clear_history_btn'             => __( 'Clear History', 'ph-fedex-woocommerce-shipping'),
            'print_return_label_btn'        => __( 'Print Return Label', 'ph-fedex-woocommerce-shipping'),
            'additional_label_btn'          => __( 'Additional Document', 'ph-fedex-woocommerce-shipping'),
            'additional_return_label_btn'   => __( 'Additional Document', 'ph-fedex-woocommerce-shipping'),

			// Tooltips
            're_generate_package_btn_tip'   => __( 'Re-generate all packages', 'ph-fedex-woocommerce-shipping'),
            'calculate_cost_btn_tip'        => __( 'Calculate the Shipping cost', 'ph-fedex-woocommerce-shipping'),
            'create_shipment_btn_tip'       => __( 'Create shipment for the packages', 'ph-fedex-woocommerce-shipping'),
            'print_label_btn_tip'           => __( 'Print Label', 'ph-fedex-woocommerce-shipping'),
            'additional_label_btn_tip'      => __( 'Additional Document', 'ph-fedex-woocommerce-shipping'),
            'void_shipment_btn_tip'         => __( 'Void Shipment', 'ph-fedex-woocommerce-shipping'),
            'generate_return_label_btn_tip' => __( 'Generate return label', 'ph-fedex-woocommerce-shipping'),
            'add_package_btn_tip'           => __( 'Add Package', 'ph-fedex-woocommerce-shipping'),
            'clear_data_btn_tip'            => __( 'Clear Data', 'ph-fedex-woocommerce-shipping'),
            'clear_history_btn_tip'         => __( 'Clear History', 'ph-fedex-woocommerce-shipping'),
            'generate_package_btn_tip'      => __( 'Generate all the packages', 'ph-fedex-woocommerce-shipping'),
            'print_return_label_btn_tip'    => __( 'Print Return Label', 'ph-fedex-woocommerce-shipping'),
            'additional_return_label_btn_tip'   => __( 'Additional Document', 'ph-fedex-woocommerce-shipping'),
        ];

		$this->min_order_amount_for_insurance = isset($this->settings['min_order_amount_for_insurance']) && !empty($this->settings['min_order_amount_for_insurance']) ? $this->settings['min_order_amount_for_insurance'] : 0;

		$this->fedex_bulk_actions = [
			'fedex_print_label',
			'fedex_print_label_as_pdf',
			'fedex_print_shipping_and_additional_label_as_pdf',
			'fedex_print_commercial_invoice_as_pdf',
			'wf_create_shipment'
		];
	}

	private function set_origin_country_state(){
		$origin_country_state 		= isset( $this->settings['origin_country'] ) ? $this->settings['origin_country'] : '';
		if ( strstr( $origin_country_state, ':' ) ) :
			// WF: Following strict php standards.
			$origin_country_state_array 	= explode(':',$origin_country_state);
			$origin_country 		= current($origin_country_state_array);
			$origin_country_state_array 	= explode(':',$origin_country_state);
			$origin_state   		= end($origin_country_state_array);
		else :
			$origin_country = $origin_country_state;
			$origin_state   = '';
		endif;

		$this->origin_country  	= apply_filters( 'woocommerce_fedex_origin_country_code', $origin_country );
		$this->origin_state 	= !empty($origin_state) ? $origin_state : ( isset($this->settings[ 'freight_shipper_state' ]) ? $this->settings[ 'freight_shipper_state' ] : '') ;
	}

	public function ph_editable_hold_at_location( $order ){

		$order_id = $order->get_id();

		$hold_at_location 		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, 'ph_fedex_hold_at_location');
		$selected_location 		= '';
		$all_locations 			= [];
		$request 				= [];
		$response 				= null;
		$shipping_address 		= $order->get_shipping_address_1();
		$shipping_city 			= $order->get_shipping_city();
		$shipping_postalcode 	= $order->get_shipping_postcode();
		$shipping_state 		= $order->get_shipping_state();
		$shipping_country 		= $order->get_shipping_country();
		$hold_at_location_carrier_code 	    = isset ( $this->settings['hold_at_location_carrier_code'] )  && !empty ($this->settings['hold_at_location_carrier_code']) ? $this->settings['hold_at_location_carrier_code'] : ''; 

		$this->attribute_type		= ( isset( $this->settings['attribute_type'] ) && !empty( $this->settings['attribute_type'] ) ) ?  $this->settings['attribute_type'] : 'all';
		$this->location_attributes	= ( isset( $this->settings['location_attributes'] ) && !empty( $this->settings['location_attributes'] ) ) ?  $this->settings['location_attributes'] : '';

		$supported_hold_at_location_type = array(
			"FEDEX_EXPRESS_STATION",
			"FEDEX_FACILITY",
			"FEDEX_FREIGHT_SERVICE_CENTER",
			"FEDEX_GROUND_TERMINAL",
			"FEDEX_HOME_DELIVERY_STATION",
			"FEDEX_OFFICE",
			"FEDEX_SHIPSITE",
			"FEDEX_SMART_POST_HUB",
			"FEDEX_ONSITE"
		);

		if( ! empty($hold_at_location) ) {

			$selected_location 	= (isset($hold_at_location->LocationDetail) && isset($hold_at_location->LocationDetail->LocationId)) ? $hold_at_location->LocationDetail->LocationId : '';
		}

		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
				'Key'       => $this->api_key,
				'Password'  => $this->api_pass,
			)
		);
		$request['ClientDetail']            = array(
			'AccountNumber' => $this->account_number,
			'MeterNumber'   => $this->meter_number,
		);
		$request['Version']                 = array(
			'ServiceId'     => 'locs',
			'Major'         => '12',
			'Intermediate'  => '0',
			'Minor'         => '0'
		);
		$request['LocationsSearchCriterion'] = 'ADDRESS';
		$request['Address']                 = array(
			'PostalCode'            => $shipping_postalcode,
			'City'                  => $shipping_city,
			'StateOrProvinceCode'   => $shipping_state,
			'CountryCode'           => $shipping_country,
		);
		$request['MultipleMatchesAction']   = 'RETURN_ALL';
		$request['SortDetail']              = array(
			'Criterion'     => 'DISTANCE',
			'Order'         => 'LOWEST_TO_HIGHEST',
		);

		$request['Constraints'] =[];
		$request['Constraints'] = array(
            'RadiusDistance' => array(
                'Value'      => '10',
                'Units'      => 'MI',
            ),
            'RequiredLocationCapabilities' => array(
                'TransferOfPossessionType' => 'HOLD_AT_LOCATION',
            )
          );
		
		if( $this->attribute_type == 'custom' && !empty( $this->location_attributes ) ) {
			$request['Constraints']['RequiredLocationAttributes'] = $this->location_attributes;
		}

		if(!empty($hold_at_location_carrier_code)){
			$request['Constraints']['RequiredLocationCapabilities']['CarrierCode'] = $hold_at_location_carrier_code;
		}

		$this->request_hash 	= md5(json_encode($request));
		$transient_data  		= get_transient($this->request_hash);

		if( !empty($transient_data) ) {
			
			$response 	= json_decode(get_transient( $this->request_hash ));

		} else {

			$this->hal_version 	= '12';
			$this->soap_method 	= $this->is_soap_available() ? 'soap' : 'nusoap';

            if(Ph_Fedex_Woocommerce_Shipping_Common::phIsNewRegistration())
            {
                if(!Ph_Fedex_Woocommerce_Shipping_Common::phHasActiveLicense())
                {
                    Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('--------------------------- HOLD AT LOCATION ---------------------------');
                    Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('Please use a valid plugin license to use WooCommerce FedEx Shipping Plugin with Print Label');
                    return;
                } else {
                    
                    if(!class_exists('class-ph-fedex-endpoint-dispatcher.php'))
                    {
                        include_once('class-ph-fedex-endpoint-dispatcher.php');
                    }

                    $apiAccessDetails = Ph_Fedex_Endpoint_Dispatcher::phGetApiAccessDetails();

                    if(!$apiAccessDetails)
                    {
                        return false;
                    }

					$proxyParams = Ph_Fedex_Woocommerce_Shipping_Common::phGetParamsForProxyCall($apiAccessDetails, $request, 'hal');

					$client = $this->wf_create_soap_client( plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/'. ( $this->production ? 'production' : 'test' ) .'/LocationsService_v'.$this->hal_version.'.wsdl', $proxyParams['options'] );
					
					if($this->soap_method == 'nusoap')
					{
						// Updating the NUSOAP location to Proxy server
						$client->setEndpoint($proxyParams['endpoint']);

						// Set custom headers
						$client->setCurlOption(CURLOPT_HTTPHEADER, $proxyParams['headers']);


					} else {
						// Updating the SOAP location to Proxy server
						$client->__setLocation($proxyParams['endpoint']);
					}

					// Get modified request
					$request = $proxyParams['request'];
                }

            } else {

				$client = $this->wf_create_soap_client( plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/'. ( $this->production ? 'production' : 'test' ) .'/LocationsService_v'.$this->hal_version.'.wsdl' );
			}


			if ($this->soap_method == 'nusoap') {

				$response = $client->call('searchLocations', array('SearchLocationsRequest' => $request));
				$response = Ph_Fedex_Woocommerce_Shipping_Common::phConvertArrayToObject($response);
			} else {

				try {

					$response = $client->searchLocations($request);
				} catch (Exception $e) {

					$exception_message = $e->getMessage();
				}
			}
			
			set_transient($this->request_hash,json_encode($response),HOUR_IN_SECONDS);
		}
		
		if( isset($response->AddressToLocationRelationships) && !empty($response->AddressToLocationRelationships) ) {

			if( ! empty($response->AddressToLocationRelationships->DistanceAndLocationDetails) && is_array($response->AddressToLocationRelationships->DistanceAndLocationDetails) ) {

				foreach( $response->AddressToLocationRelationships->DistanceAndLocationDetails as $location ) {
					$all_locations[$location->LocationDetail->LocationId] = $location;
				}

				PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data( $order_id, 'ph_available_fedex_hold_at_location', $all_locations );
			} else if( ! empty($response->AddressToLocationRelationships->DistanceAndLocationDetails) ){
				
				$all_locations[$response->AddressToLocationRelationships->DistanceAndLocationDetails->LocationDetail->LocationId] = $response->AddressToLocationRelationships->DistanceAndLocationDetails;

				PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data( $order_id, 'ph_available_fedex_hold_at_location', $all_locations );
			}
		}

		$locator='<div class="edit_address"><p class="form-field form-field-wide"><label>FedEx Hold At Location</label><select id="shipping_hold_at_location" name="shipping_hold_at_location" class="select first" >';
		$locator .=	"<option value=''>". __('Select Hold At Location', 'ph-fedex-woocommerce-shipping') ."</option>";

		if(!empty($all_locations)) {

			$hold_at_location_types 	=   apply_filters('ph_fedex_supported_hold_at_location_types', $supported_hold_at_location_type);

			foreach ($all_locations as $location_id => $location) {

				if( !empty($location->LocationDetail->LocationType) && in_array($location->LocationDetail->LocationType, $hold_at_location_types) ) {
					
					$address = null;

					// Street Address
					if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->StreetLines) ) {
						$address = $location->LocationDetail->LocationContactAndAddress->Address->StreetLines;
					}
					
					// City
					if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->City) ) {
						$address = ! empty($address) ? $address.', '. $location->LocationDetail->LocationContactAndAddress->Address->City : $location->LocationDetail->LocationContactAndAddress->Address->City;
					}
					
					// State
					if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->StateOrProvinceCode) ) {
						$address = ! empty($address) ? $address.', '. $location->LocationDetail->LocationContactAndAddress->Address->StateOrProvinceCode : $location->LocationDetail->LocationContactAndAddress->Address->StateOrProvinceCode;
					}
					
					// Postal Code
					if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->PostalCode) ) {
						$address = ! empty($address) ? $address.', '. $location->LocationDetail->LocationContactAndAddress->Address->PostalCode : $location->LocationDetail->LocationContactAndAddress->Address->PostalCode;
					}
					
					// Country
					$address = !empty($address) ? $address.', '.$location->LocationDetail->LocationContactAndAddress->Address->CountryCode : $location->LocationDetail->LocationContactAndAddress->Address->CountryCode;
					
					//Distance
					if( ! empty($location->Distance->Value) ) {
						$address = $address.'. ('. $location->Distance->Value.' '. $location->Distance->Units.')';
					}

					if( $selected_location != $location_id) {
						$locator .= "<option value= '".$location_id."'> ". $address ."</option>";
					}else{
						$locator .= "<option value= '".$location_id."' selected> ". $address ."</option>";
					}
				}

			}
		}

		$locator .=	'</select></p></div>';
		$array['#shipping_hold_at_location'] = $locator;
		
		echo $array['#shipping_hold_at_location'];

	}

	function ph_save_hold_at_location( $post_id ){

		$order = wc_get_order($post_id);

		$order_meta_handler = new PH_WC_Fedex_Storage_Handler($order);

		$hold_at_location 		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($post_id, 'ph_available_fedex_hold_at_location');
		$selected_location 		= isset($_POST['shipping_hold_at_location']) ? $_POST['shipping_hold_at_location'] : '';

		if( !empty($selected_location) && !empty($hold_at_location) && array_key_exists($selected_location, $hold_at_location) ) {

			$location 	= $hold_at_location[$selected_location];

			$address = null;

			// Street Address
			if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->StreetLines) ) {
				$address = $location->LocationDetail->LocationContactAndAddress->Address->StreetLines;
			}

			// City
			if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->City) ) {
				$address = ! empty($address) ? $address.', '. $location->LocationDetail->LocationContactAndAddress->Address->City : $location->LocationDetail->LocationContactAndAddress->Address->City;
			}

			// State
			if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->StateOrProvinceCode) ) {
				$address = ! empty($address) ? $address.', '. $location->LocationDetail->LocationContactAndAddress->Address->StateOrProvinceCode : $location->LocationDetail->LocationContactAndAddress->Address->StateOrProvinceCode;
			}

			// Postal Code
			if( ! empty($location->LocationDetail->LocationContactAndAddress->Address->PostalCode) ) {
				$address = ! empty($address) ? $address.', '. $location->LocationDetail->LocationContactAndAddress->Address->PostalCode : $location->LocationDetail->LocationContactAndAddress->Address->PostalCode;
			}

			// Country
			$address = !empty($address) ? $address.', '.$location->LocationDetail->LocationContactAndAddress->Address->CountryCode : $location->LocationDetail->LocationContactAndAddress->Address->CountryCode;

			$selectedAddress    = $address;
			
			//Distance
			if( ! empty($location->Distance->Value) ) {
				$address = $address.'. ('. $location->Distance->Value.' '. $location->Distance->Units.')';
			}

			if( isset($location->LocationDetail->LocationContactAndAddress->Contact) && isset($location->LocationDetail->LocationContactAndAddress->Contact->CompanyName) ) {

				$selectedAddress    = $location->LocationDetail->LocationContactAndAddress->Contact->CompanyName.', '.$selectedAddress;
			}
			
			$order_meta_handler->ph_update_meta_data( $post_id, 'ph_fedex_hold_at_location', $hold_at_location[$selected_location] );
			$order_meta_handler->ph_update_meta_data( $post_id, 'ph_fedex_selected_hold_at_location', $selectedAddress );
		} else {
			$order_meta_handler->ph_update_meta_data( $post_id, 'ph_fedex_hold_at_location', '' );
			$order_meta_handler->ph_update_meta_data( $post_id, 'ph_fedex_selected_hold_at_location', '' );
		}

		$order_meta_handler->ph_save_meta_data();			
	}

	/**
	 * Display the messages in debug mode.
	 * @param $message mixed Message to display.
	 */
	public function print_debug_message( $message ) {
		if ( $this->debug ) {
			echo "<pre>".print_r($message,true)."</pre>";
		}
	}

	function init_bulk_printing(){

		// New Screen (WC HPOS enabled)
		add_filter( 'bulk_actions-woocommerce_page_wc-orders', [ $this, 'ph_fedex_bulk_actions' ] );
		add_action( 'admin_init', [ $this, 'ph_handle_bulk_actions_new_screen' ]);

		// Old Screen
		add_filter( 'bulk_actions-edit-shop_order', [ $this, 'ph_fedex_bulk_actions' ] );
		add_filter( 'handle_bulk_actions-edit-shop_order', [ $this, 'ph_handle_bulk_actions_old_screen' ], 10, 3 );
	}

	/**
	 * Add bulk actions
	 *
	 * @param array $actions
	 * @return array $actions
	 */
	public function ph_fedex_bulk_actions($actions)
	{
		if ($this->label_image_type == 'png') {

			$actions['fedex_print_label'] = __('Print FedEx label', 'ph-fedex-woocommerce-shipping');
		}

		if ($this->label_image_type == 'pdf') {

			$actions['fedex_print_label_as_pdf'] = __('Print FedEx Shipping Label (PDF)', 'ph-fedex-woocommerce-shipping');
			$actions['fedex_print_shipping_and_additional_label_as_pdf'] = __('Print FedEx Shipping + Additional Label (PDF)', 'ph-fedex-woocommerce-shipping');
		}

		$actions['fedex_print_commercial_invoice_as_pdf'] = __('Print FedEx Commercial Invoice (PDF)', 'ph-fedex-woocommerce-shipping');
		$actions['wf_create_shipment'] = __('Create FedEx label', 'ph-fedex-woocommerce-shipping');

		return $actions;
	}

	/**
	 * Handle bulk action for Post Table view
	 *
	 * @param mixed $redirect_to
	 * @param string $action
	 * @param array $post_ids
	 * @return mixed $redirect_to
	 */
	public function ph_handle_bulk_actions_old_screen($redirect_to, $action, $post_ids)
	{
		if (!empty($post_ids) && is_array($post_ids)) {

			$this->ph_perform_bulk_action($action, $post_ids);
		}

		return $redirect_to;
	}

	/**
	 * Handle bulk actions
	 */
	public function ph_handle_bulk_actions_new_screen()
	{
		$action		= isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '';
		$action		= empty($action) ? (isset($_GET['action2']) && !empty($_GET['action2']) ? $_GET['action2'] : '') : $action;
		$order_ids	= isset($_GET['id']) && is_array($_GET['id']) ? $_GET['id'] : [];

		if (!in_array($action, $this->fedex_bulk_actions) || !PH_WC_Fedex_Storage_Handler::ph_check_if_hpo_enabled()) {
			return;
		}

		$this->ph_perform_bulk_action($action, $order_ids);
	}

	/**
	 * Bulk action handler
	 */
	public function ph_perform_bulk_action($action, $order_ids)
	{
		switch ($action) {

			case 'fedex_print_label':
				$this->ph_print_fedex_labels($order_ids);
				break;
			case 'fedex_print_label_as_pdf':
				$this->ph_print_label_as_pdf($order_ids);
				break;
			case 'fedex_print_shipping_and_additional_label_as_pdf':
				$this->ph_print_all_labels($order_ids);
				break;
			case 'fedex_print_commercial_invoice_as_pdf':
				$this->ph_print_commercial_invoice_as_pdf($order_ids);
				break;
			case 'wf_create_shipment':
				$this->ph_create_shipment($order_ids);
				break;
		}
	}

	/**
	 * Print FedEx labels on bulk action - fedex_print_label
	 */
	private function ph_print_fedex_labels($order_ids)
	{
		$shipping_labels = [];

		foreach ($order_ids as $order_id) {

			$shipmentIds = $this->ph_get_unique_shipment_ids($order_id);

			foreach ($shipmentIds as $shipmentId) {

				$shipping_labels[]	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipmentId);
				$label_img_type		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_image_type_' . $shipmentId);

				if ($label_img_type != 'PNG') {
					wf_admin_notice::add_notice(__("Bulk label printing will work with only PNG format, You have selected label format '$label_img_type'", 'ph-fedex-woocommerce-shipping'));
					wp_redirect(admin_url('/edit.php?post_type=shop_order'));
					exit();
				}
			}
		}

		if (empty($shipping_labels)) {

			wf_admin_notice::add_notice(__('No FedEx label found on selected order', 'ph-fedex-woocommerce-shipping'));

			wp_redirect(admin_url('/edit.php?post_type=shop_order'));
			exit();
		}

		echo "<html>
				<body style='margin: 0; display: flex; flex-direction: column; justify-content: center;'>
					<div style='text-align: center;'>";

						foreach ($shipping_labels as $key => $label) {

							echo "<div>
									<img style='max-width: " . $this->custom_scaling . "%;' src='data:image/png;base64," . $label . "'/>
								</div>";
						}

		echo "</div></body></html>";

		exit();
	}

	/**
	 * Print label as PDF
	 *
	 * @param array order_ids
	 */
	private function ph_print_label_as_pdf($order_ids)
	{
		$shipping_labels = [];

		foreach ($order_ids as $order_id) {

			$shipment_ids = $this->ph_get_unique_shipment_ids($order_id);

			foreach ($shipment_ids as $shipment_id) {

				$shipping_labels[$shipment_id]	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipment_id);
				$label_img_type					= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_image_type_' . $shipment_id);

				if ($label_img_type != 'PDF') {
					wf_admin_notice::add_notice(__("Print FedEx Shipping Label (PDF) will work with only PDF format, You have selected label format '$label_img_type'", 'ph-fedex-woocommerce-shipping'));
					wp_redirect(admin_url('/edit.php?post_type=shop_order'));
					exit();
				}
			}
		}

		if (!empty($shipping_labels)) {

			$this->phFedExPrintLabelsInBulk($shipping_labels);
			return;
		}

		wf_admin_notice::add_notice(__('No FedEx label found on selected order', 'ph-fedex-woocommerce-shipping'));
		wp_redirect(admin_url('/edit.php?post_type=shop_order'));
		exit();
	}

	/**
	 * Print all the labels including additional labels
	 *
	 * @param array $order_ids
	 */
	private function ph_print_all_labels($order_ids)
	{
		$shipping_labels = [];

		foreach ($order_ids as $order_id) {

			$shipment_ids = $this->ph_get_unique_shipment_ids($order_id);

			foreach ($shipment_ids as $shipment_id) {

				$shipping_labels[$shipment_id] 	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipment_id);
				$additional_labels 				= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_fedex_additional_label_' . $shipment_id);
				$shipping_label_image_type		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_image_type_' . $shipment_id);
				$additional_labels_image_type	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_fedex_additional_label_image_type_' . $shipment_id);
				$additional_label_image_type	= true;

				if (!empty($additional_labels) && is_array($additional_labels)) {

					foreach ($additional_labels as $key => $value) {

						$shipping_labels[$key . '-' . $shipment_id]	= $value;
					}
				}

				if (!empty($additional_labels_image_type) && is_array($additional_labels_image_type)) {

					foreach ($additional_labels_image_type as $key => $value) {

						if ($value !== 'PDF') {

							$additional_label_image_type	= false;
						}
					}
				}

				if ($shipping_label_image_type != 'PDF' || !$additional_label_image_type) {
					wf_admin_notice::add_notice(__("Print FedEx Shipping + Additional Label (PDF) will work with only PDF format, You have selected label format '$shipping_label_image_type'", 'ph-fedex-woocommerce-shipping'));
					return;
				}
			}
		}

		if (!empty($shipping_labels)) {

			$this->phFedExPrintLabelsInBulk($shipping_labels);
		}

		wf_admin_notice::add_notice(__('No FedEx label found on selected order', 'ph-fedex-woocommerce-shipping'));
		wp_redirect(admin_url('/edit.php?post_type=shop_order'));
		exit();
	}

	/**
	 * Print commercial invoice
	 */
	private function ph_print_commercial_invoice_as_pdf($order_ids)
	{
		$commercial_invoices = [];

		foreach ($order_ids as $order_id) {

			$shipment_ids = $this->ph_get_unique_shipment_ids($order_id);

			foreach ($shipment_ids as $shipment_id) {

				$shipping_labels_1 = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_fedex_additional_label_' . $shipment_id);

				if (!empty($shipping_labels_1) && isset($shipping_labels_1['Commercial Invoice']) && !empty($shipping_labels_1['Commercial Invoice'])) {

					$commercial_invoices['Commercial Invoice-' . $shipment_id]	= $shipping_labels_1['Commercial Invoice'];
				}
			}
		}

		if( !empty($commercial_invoices)  ) {

			$this->phFedExPrintLabelsInBulk($commercial_invoices);
		}
		
		wf_admin_notice::add_notice( __('No Fedex label found on selected order', 'ph-fedex-woocommerce-shipping') );
		wp_redirect( admin_url( '/edit.php?post_type=shop_order') );
		exit();
	}

	/**
	 * Create shipment from bulk action
	 */
	private function ph_create_shipment($order_ids)
	{
		if (Ph_Fedex_Woocommerce_Shipping_Common::phIsNewRegistration()) {
			if (!Ph_Fedex_Woocommerce_Shipping_Common::phHasActiveLicense()) {
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('--------------------------- FEDEX SHIPMENT ---------------------------');
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('Please use a valid plugin license to use WooCommerce FedEx Shipping Plugin with Print Label');

				wf_admin_notice::add_notice(__('Please use a valid license to continue using WooCommerce FedEx Shipping Plugin with Print Label', 'ph-fedex-woocommerce-shipping'));
				wp_redirect(admin_url('/edit.php?post_type=shop_order'));
				exit;
			}
		}

		foreach ($order_ids as $order_id) {

			$order = wc_get_order($order_id);

			if ($order) {
				$package = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order->get_id(), '_wf_fedex_stored_packages');
				if (empty($package)) {
					$this->xa_generate_package($order);
				}

				$shipmentIds = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order->get_id(), 'wf_woo_fedex_shipmentId', false);
				if (!empty($shipmentIds)) {
					wf_admin_notice::add_notice(__("Label already generated for order $order_id", 'ph-fedex-woocommerce-shipping'), 'notice');
					continue;
				}

				$this->wf_create_shipment($order);

				$shipmentIds = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order->get_id(), 'wf_woo_fedex_shipmentId', false);
				if (!empty($shipmentIds)) {
					wf_admin_notice::add_notice(__("Label has been generated for order $order_id", 'ph-fedex-woocommerce-shipping'), 'notice');
				} else {
					wf_admin_notice::add_notice(__("There is some error occured while creating the shipment for order $order_id", 'ph-fedex-woocommerce-shipping'), 'error');
				}
				wp_redirect(admin_url('/edit.php?post_type=shop_order'));
			}
		}
	}

	/**
	 * Get unique shipment ids
	 *
	 * @param int $order_id
	 * @return array $shipmentIds
	 */
	private function ph_get_unique_shipment_ids($order_id)
	{
		$shipmentIds	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipmentId', false);
		$shipment_ids	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_woo_fedex_shipmentIds');

		if (is_array($shipmentIds) && is_array($shipment_ids)) {
			$shipmentIds = array_unique(array_merge($shipmentIds, $shipment_ids));
		}

		return $shipmentIds;
	}

	public function phFedExPrintLabelsInBulk( $labels ) {
		
		require_once( 'PDFMerger/PhPdfMerger.php' );

		$pdf = new PhPdfMerger;
		$path 		= wp_upload_dir();
		$files 		= [];
		
		if( ! empty($labels) ) {

			$failed_labels = [];

			foreach( $labels as $key => $label ) {
				
				$response 		= base64_decode($label);
				$shipment_id 	= $key;
				$file 			= $path['path']."/ShipmentArtifact-$shipment_id.pdf";

				file_put_contents($file, $response);
				$files[] 		= $file;
			}
 
			// Loop through individual files to create a single PDF file
			foreach($files as $file ) {

				$pdf->addPDF($file);
			}

			$pdf->merge('download','FedEx-Shipment-Label-'.date("Y-m-d").'.pdf');
		}
	}

	public function xa_fedex_validate_credentials() {

		$production			= ( isset($_POST['production']) ) ? $_POST['production'] =='true' : false;
		$account_number		= ( isset($_POST['account_number']) ) ? $_POST['account_number'] : '';
		$meter_number		= ( isset($_POST['meter_number']) ) ? $_POST['meter_number'] : '';
		$api_key			= ( isset($_POST['api_key']) ) ? $_POST['api_key'] : '';
		$api_pass			= ( isset($_POST['api_pass']) ) ? $_POST['api_pass'] : '';
		
		if ( empty($account_number) || empty($meter_number) || empty($api_key) || empty($api_pass) ) {

			$result = array(

				'message' 	=> "Please fill the FedEx account details above and try again.",
				'success'	=> 'no',
			);

			wp_die( json_encode($result) );
		}

		$origin_country_state		= ( isset($_POST['origin_country']) ) ? $_POST['origin_country'] : '';
		$origin						= ( isset($_POST['origin']) ) ? $_POST['origin'] : '';
		$origin_country_state_array = explode(':',$origin_country_state);
		$origin_country 			= current($origin_country_state_array);

		$request = array(
			'WebAuthenticationDetail' => array(
				'UserCredential' => array(
					'Key' => $api_key,
					'Password' => $api_pass,
				),
			),
			'ClientDetail' => array(
				'AccountNumber' => $account_number,
				'MeterNumber' => $meter_number,
			),
			'TransactionDetail' => array(
				'CustomerTransactionId' =>  '*** WooCommerce Rate Request ***',
			),
			'Version' => array(
				'ServiceId' => 'crs',
				'Major' => 31,
				'Intermediate' => 0,
				'Minor' => 0,
			),
			'ReturnTransitAndCommit' => 1,
			'RequestedShipment' => array(
				'EditRequestType' => 1,
				'PreferredCurrency' => 'USD',
				'DropoffType' => 'REGULAR_PICKUP',
				'Shipper' => array(
					'Address' => array(
						'PostalCode' => $origin,
						'CountryCode' => $origin_country,
					),
				),
				'Recipient' => array(
					'Address' => array(
						'PostalCode' => '90017',
						'City' => 'LOSE ANGELES',
						'StateOrProvinceCode' => 'CA',
						'CountryCode' => 'US',
					),
				),
				'RequestedPackageLineItems' => array(
					0 => array(
						'SequenceNumber' => 1,
						'GroupNumber' => 1,
						'GroupPackageCount' => 1,
						'Weight' => array(
							'Value' => '5.52',
							'Units' => 'LB',
						),
					),
				),
			),
		);

		$this->soap_method = $this->is_soap_available() ? 'soap' : 'nusoap';
		if( $this->soap_method == 'nusoap' && !class_exists('nusoap_client') ){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/nusoap/lib/nusoap.php';
		}
		$client = $this->wf_create_soap_client( plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/' . ( $production ? 'production' : 'test' ) . '/RateService_v' . $this->rateservice_version. '.wsdl' );

		$log = new WC_Logger();

		try {
				
			if ($this->soap_method == 'nusoap') {

				$response = $client->call('getRates', array('RateRequest' => $request));
				$response = Ph_Fedex_Woocommerce_Shipping_Common::phConvertArrayToObject($response);

				if ($client->fault) {

					$log->add('Fedex Soap Details', " Nusoap Fault : " . print_r($response, true));
				} elseif ($client->getError()) {

					$log->add('Fedex Soap Details', " Nusoap Error : " . print_r($client->getError(), true));
				}

				if (empty($response)) {

					$log->add('Fedex Soap Details', "NuSoap Debug Data : " . print_r(htmlspecialchars($client->debug_str, ENT_QUOTES), true));
				}
			} else {

				$response = $client->getRates($request);

				if (is_soap_fault($response)) {

					$log->add('Fedex Soap Details', " Soap Fault " . print_r($response, true));
				}
			}
		}
		catch(Exception $e) {
			$log->add( 'Fedex Soap Details', 'Exception Occured - '.print_r($e->getMessage(),true));
		}
		
		$result = array();
		if ( $response  ) {
			if( isset($response->HighestSeverity) && $response->HighestSeverity === 'ERROR' ){
				$error_message = '';
				if( isset($response->Notifications->Message) ) {
					$result = array(
						'message' 	=> $response->Notifications->Message,
						'success'	=> 'no',
					);
				}elseif( isset($response->Notifications[0]->Message) ) {
					$result = array(
						'message' 	=> $response->Notifications[0]->Message,
						'success'	=> 'no',
					);
				}
			}else{
				$result = array(
					'message' 	=> "Successfully authenticated, The credentials are valid. Soapmethod : $this->soap_method",
					'success'	=> 'yes',
				);
			}
		}else{
			$result = array(
				'message' 	=> "An unexpected error occurred. No response from soap client. Unable to authenticate. Soapmethod : $this->soap_method",
				'success'	=> 'no',
			);
		}
		wp_die( json_encode($result) );
	}

	public function ph_fedex_view_additional_return_label() {

		$shipmentDetails = explode('|', base64_decode($_GET['ph_fedex_view_additional_return_label']));

		if (count($shipmentDetails) != 3) {
			exit;
		}

		$shipmentId					= $shipmentDetails[0]; 
		$order_id					= $shipmentDetails[1]; 
		$add_key 					= $shipmentDetails[2];		
		
		$additional_return_labels 			= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_additional_return_label_' . $shipmentId);
		$additional_return_label_image_type = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_additional_return_label_image_type_' . $shipmentId);
		
		if ( !empty($additional_return_label_image_type[$add_key])) {

			$image_type = $additional_return_label_image_type[$add_key];
		} else {

			$image_type = $this->image_type;
		}

		if ( !empty($additional_return_labels) && isset($additional_return_labels[$add_key]) ) {

			header('Content-Type: application/'.$image_type);
			$label_name = apply_filters( 'ph_fedex_label_name', 'Return-addition-doc-'. $add_key .'-'.$shipmentId, $add_key.'-'.$shipmentId, $order_id, 'return_additional_label' );
			header('Content-disposition: attachment; filename="' . $label_name . '.'.$image_type.'"');
			print(base64_decode($additional_return_labels[$add_key])); 
		}

		exit;
	}

	public function wf_fedex_viewReturnlabel()
	{
		$settings 				= get_option('woocommerce_' . WF_Fedex_ID . '_settings', null);
		$show_label_in_browser  = isset($settings['show_label_in_browser']) ? $settings['show_label_in_browser'] : 'no';
		$shipmentDetails 		= explode('|', base64_decode($_GET['wf_fedex_viewReturnlabel']));

		if (count($shipmentDetails) != 2) {
			exit;
		}

		$shipmentId					= $shipmentDetails[0];
		$order_id					= $shipmentDetails[1];
		$shipping_label				= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_returnLabel_' . $shipmentId);
		$shipping_label_image_type	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_returnLabel_image_type_' . $shipmentId);

		if (empty($shipping_label_image_type)) {
			$shipping_label_image_type = $this->image_type;
		}

		if ($show_label_in_browser == "yes" && $shipping_label_image_type == "PNG" && $this->image_type == 'png') {

			$final_image 		= base64_decode(chunk_split($shipping_label));;
			$final_image 		= imagecreatefromstring($final_image);
			$html_before_image 	= "<html><body style='margin: 0; display: flex; flex-direction: column; justify-content: center;'><div style='text-align: center;'>";
			$html_after_image 	= "</div></body></html>";
			$image_style 		= "style='max-width: 100%;'";

			ob_start();
			imagepng($final_image);
			$contents =  ob_get_contents();
			ob_end_clean();
			echo $html_before_image . "<img " . $image_style . " src='data:image/gif;base64," . base64_encode($contents) . "'/>" . $html_after_image;
		} else {

			header('Content-Type: application/' . $shipping_label_image_type);
			$label_name = apply_filters('ph_fedex_label_name', 'ShipmentArtifact-' . $shipmentId, $shipmentId, $order_id, 'return_label');
			header('Content-disposition: attachment; filename="' . $label_name . '.' . $shipping_label_image_type . '"');
			print(base64_decode($shipping_label));
		}

		exit;
	}

	private function is_soap_available(){
		if( extension_loaded( 'soap' ) ){
			return true;
		}
		return false;
	}

	private function wf_create_soap_client( $wsdl, $options = ['trace' => true] ){
		if( $this->soap_method=='nusoap' ){
			$soapclient = new nusoap_client( $wsdl, 'wsdl' );
		}else{
			$soapclient = new SoapClient( $wsdl, $options);
		}
		return $soapclient;
	}

	public function wf_create_return_label(){

		if (!$this->ph_user_permission()) {
			return;
		}

		$return_params = explode('|', base64_decode($_GET['wf_create_return_label']));
		
		if(empty($return_params) || !is_array($return_params) || count($return_params) != 2) {
			return;
		}
		
		$shipment_id = $return_params[0]; 
		$order_id =  $return_params[1];

		$this->wf_create_return_shipment( $shipment_id, $order_id );

		if ( $this->debug ) {
			//dont redirect when debug is printed
			die();
		}
		else{
		  wp_redirect(admin_url('/post.php?post='.$order_id.'&action=edit'));
		  exit;
		}

	}
	
	/**
	 * Clear history
	 */
	public function wf_clear_history()
	{
		if (isset($_GET['ph_client_reset_link'])) {

			$order_id 		= base64_decode($_GET['ph_client_reset_link']);
			$void_shipments = $this->ph_get_unique_shipment_ids($order_id);
		} else {

			$order_id 			= base64_decode($_GET['wf_clear_history']);
			$void_shipments 	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipment_void', false);
		}

		if (empty($order_id))
			return;

		if (!$this->ph_user_permission()) {

			wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit'));
			exit;
		}

		if (empty($void_shipments)) {
			wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit'));
			exit;
		}

		$order = wc_get_order($order_id);

		$wc_order_meta_handler = new PH_WC_Fedex_Storage_Handler($order);

		foreach ($void_shipments as $void_shipment_id) {

			$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_packageDetails_' . $void_shipment_id);
			$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_shippingLabel_' . $void_shipment_id);
			$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_service_code' . $void_shipment_id);
			$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_shippingLabel_image_type_' . $void_shipment_id);
			$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_shipmentId');
			$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_shipment_void');
			$wc_order_meta_handler->ph_delete_meta_data('wf_fedex_additional_label_' . $void_shipment_id);
			$wc_order_meta_handler->ph_delete_meta_data('wf_fedex_additional_label_image_type_' . $void_shipment_id);
		}

		$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_shipment_void_errormessage');
		$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_service_code');
		$wc_order_meta_handler->ph_delete_meta_data('wf_woo_fedex_shipmentErrorMessage');
		$wc_order_meta_handler->ph_delete_meta_data('ph_woo_fedex_shipmentIds'); //New added meta key	

		$wc_order_meta_handler->ph_save_meta_data();

		wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit'));
		exit;
	}

	public function wf_fedex_void_shipment()
	{
		if (!$this->ph_user_permission()) {
			return;
		}

		$void_params = explode('||', base64_decode($_GET['wf_fedex_void_shipment']));

		if (empty($void_params) || !is_array($void_params) || count($void_params) != 2) {
			return;
		}

		$shipment_id = $void_params[0];
		$order_id = $void_params[1];

		if (!class_exists('wf_fedex_woocommerce_shipping_admin_helper'))
		include_once 'class-wf-fedex-woocommerce-shipping-admin-helper.php';

		$woofedexwrapper = new wf_fedex_woocommerce_shipping_admin_helper();

		$tracking_completedata = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_tracking_full_details_' . $shipment_id);

		if (!empty($tracking_completedata)) {
			$woofedexwrapper->void_shipment($order_id, $shipment_id, $tracking_completedata);
		}

		if ($this->debug) {
			//dont redirect when debug is printed
			die();
		} else {
			wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit'));
			exit;
		}
	}

	/**
	 * Check if current WordPress user has permission to generate orders
	 */
	private function ph_user_permission($auto_generate = null)
	{
		// Check if user has rights to generate invoices
		$current_minute = (int)date('i');

		if (!empty($auto_generate) && ($auto_generate == md5($current_minute) || $auto_generate == md5($current_minute + 1))) {
			return true;
		}

		$current_user = wp_get_current_user();
		$user_ok = false;
		$wf_roles = apply_filters('ph_user_permission_roles', array('administrator', 'shop_manager'));

		if ($current_user instanceof WP_User) {
			$role_ok = array_intersect($wf_roles, $current_user->roles);
			if (!empty($role_ok)) {
				$user_ok = true;
			}
		}
		return $user_ok;
	}

	/**
	 * Create Shipment
	 */
	public function wf_fedex_createshipment()
	{
		$order_id 		= $_GET['wf_fedex_createshipment'];
		$auto_generate	= isset($_GET['auto_generate']) ? $_GET['auto_generate'] : null;

		if (!$this->ph_user_permission($auto_generate)) {
			return;
		}

		$order = wc_get_order($order_id);

		if (!$order instanceof WC_Order) {
			return;
		}

		$shipment_ids = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipmentId');

		if (empty($shipment_ids)) {

			$this->wf_create_shipment($order);
		} else {

			if ($this->debug) {
				_e('Fedex label generation Suspended. Label has been already generated.', 'ph-fedex-woocommerce-shipping');
			}

			if (class_exists('WC_Admin_Meta_Boxes')) {
				WC_Admin_Meta_Boxes::add_error('Fedex label generation Suspended. Label has been already generated.', 'ph-fedex-woocommerce-shipping');
			}
		}

		if ($this->debug) {
			//dont redirect when debug is printed
			die();
		}

		wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit'));
		exit;
	}
	
	public function wf_fedex_viewlabel(){
		$settings 					= get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );
		$show_label_in_browser      = isset( $settings['show_label_in_browser'] ) ? $settings['show_label_in_browser'] : 'no';
		$shipmentDetails 			= explode('|', base64_decode($_GET['wf_fedex_viewlabel']));

		if (count($shipmentDetails) != 2) {
			exit;
		}
		
		$shipmentId 				= $shipmentDetails[0]; 
		$order_id 					= $shipmentDetails[1]; 

		$shipping_label				= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipmentId);
		$shipping_label_image_type	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_image_type_' . $shipmentId);
		
		if( empty($shipping_label_image_type) ){
			$shipping_label_image_type = $this->image_type;
		}

		if( $show_label_in_browser == "yes" && $shipping_label_image_type == "PNG" && $this->image_type == 'png' ) {

			$final_image 		= base64_decode(chunk_split($shipping_label));;
			$final_image 		= imagecreatefromstring($final_image);
			$html_before_image 	= "<html><body style='margin: 0; display: flex; flex-direction: column; justify-content: center;'><div style='text-align: center;'>";
			$html_after_image 	= "</div></body></html>";
			$image_style 		= "style='max-width: 100%;'";

			ob_start();
			imagepng($final_image);
			$contents =  ob_get_contents();
			ob_end_clean();
			echo $html_before_image."<img ".$image_style." src='data:image/gif;base64,".base64_encode($contents)."'/>".$html_after_image;

		}else{

			header('Content-Type: application/'.$shipping_label_image_type);
			$label_name = apply_filters( 'ph_fedex_label_name', 'ShipmentArtifact-'.$shipmentId, $shipmentId, $order_id, 'normal_label' );
			header('Content-disposition: attachment; filename="' . $label_name . '.'.$shipping_label_image_type.'"');
			print(base64_decode($shipping_label)); 
		}
		exit;
	}

	public function wf_fedex_additional_label()
	{
		$shipmentDetails = explode('|', base64_decode($_GET['wf_fedex_additional_label']));

		if (count($shipmentDetails) != 3) {
			exit;
		}

		$shipmentId = $shipmentDetails[0];
		$post_id = $shipmentDetails[1];
		$add_key = $shipmentDetails[2];
		$additional_labels = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($post_id, 'wf_fedex_additional_label_' . $shipmentId);
		$additional_label_image_type = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($post_id, 'wf_fedex_additional_label_image_type_' . $shipmentId);

		if (!empty($additional_label_image_type[$add_key])) {
			$image_type = $additional_label_image_type[$add_key];
		} else {
			$image_type = $this->image_type;
		}

		if (!empty($additional_labels) && isset($additional_labels[$add_key])) {
			header('Content-Type: application/' . $image_type);
			$label_name = apply_filters('ph_fedex_label_name', 'Addition-doc-' . $add_key . '-' . $shipmentId, $add_key . '-' . $shipmentId, $post_id, 'additional_label');
			header('Content-disposition: attachment; filename="' . $label_name . '.' . $image_type . '"');
			print(base64_decode($additional_labels[$add_key]));
		}

		exit;
	}
	
	private function wf_is_service_valid_for_country($order, $service_code, $dest_country='')
	{
		$uk_domestic_services = array('FEDEX_DISTANCE_DEFERRED', 'FEDEX_NEXT_DAY_EARLY_MORNING', 'FEDEX_NEXT_DAY_MID_MORNING', 'FEDEX_NEXT_DAY_AFTERNOON', 'FEDEX_NEXT_DAY_END_OF_DAY', 'FEDEX_NEXT_DAY_FREIGHT' );
		
		$shipper_country = $this->origin_country;
		$shipping_country = !empty($dest_country) ? $dest_country : $order->get_shipping_country();
		
		if( 'GB'==$shipper_country && 'GB'==$shipping_country && in_array($service_code,$uk_domestic_services) ){
			return true;
		}
		$exception_list = array('FEDEX_GROUND','FEDEX_FREIGHT_ECONOMY','FEDEX_FREIGHT_PRIORITY');
		$exception_country = array('US','CA');
		if(in_array($shipping_country,$exception_country) && in_array($service_code,$exception_list)){
			return true;
		}
		
		if( $shipping_country == $this->origin_country ){
			return strpos($service_code, 'INTERNATIONAL_') === false;
		}
		else{
			return  strpos($service_code, 'INTERNATIONAL_') !== false;
		}
		return false; 
	}

	private function is_domestic($order){

		if ( !isset($this->origin_country) ) {

			$settings 					= get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );
			$origin_country_state 		= isset( $settings['origin_country'] ) ? $settings['origin_country'] : '';

			if ( strstr( $origin_country_state, ':' ) ) :
				$origin_country_state_array 	= explode(':',$origin_country_state);
				$origin_country 		= current($origin_country_state_array);
			else :
				$origin_country = $origin_country_state;
			endif;

			$this->origin_country  	= apply_filters( 'woocommerce_fedex_origin_country_code', $origin_country );
		}
		
		return $this->origin_country == $order->get_shipping_country();
	}

	private function wf_get_shipping_service($order, $retrive_from_order = false, $shipment_id = false, $package_group_key = false)
	{
		$order_id = $order->get_id();

		if ($retrive_from_order) {

			$service_code = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_service_code' . $shipment_id);

			if (!empty($service_code)) {
				return $service_code;
			}
		}

		if (!empty($_GET['service']))
		{
			$service_arr = json_decode(
				stripslashes(
					html_entity_decode($_GET["service"])
				)
			);
			
			// If all the generated packages has been removed from order then services will be empty
			if (!empty($service_arr[0])) {
				return $service_arr[0];
			} elseif (!$this->debug) {

				if (class_exists('WC_Admin_Meta_Boxes')) {
					WC_Admin_Meta_Boxes::add_error(__("FedEx services missing. Label generation has been terminated.", "ph-fedex-woocommerce-shipping"));
				}
				wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit'));
				exit();
			} else {
				$this->print_debug_message(__("FedEx services missing. Label generation has been terminated.", "ph-fedex-woocommerce-shipping"));
				exit();
			}
		}

		//TODO: Take the first shipping method. It doesnt work if you have item wise shipping method
		$shipping_methods = $order->get_shipping_methods();

		if (!empty($shipping_methods)) {
			
			$shipping_method 		= array_shift($shipping_methods);
			$shipping_method_meta 	= $shipping_method->get_meta('_xa_fedex_method');
			$shipping_method_id 	= !empty($shipping_method_meta) ? $shipping_method_meta['id'] : $shipping_method['method_id'];

			if (strstr($shipping_method_id, WF_Fedex_ID)) {
				return apply_filters('ph_modify_shipping_method_service', str_replace(WF_Fedex_ID . ':', '', $shipping_method_id), $order, $package_group_key);
			}
		}

		if ($this->is_domestic($order)) {
			if (!empty($this->settings['default_dom_service'])) {
				return $this->settings['default_dom_service'];
			}
		} else {
			if (!empty($this->settings['default_int_service'])) {
				return $this->settings['default_int_service'];
			}
		}
	}
	
	public function wf_create_shipment( $order, $service_arr = array() )
	{
		$order_id = $order->get_id();

		if(Ph_Fedex_Woocommerce_Shipping_Common::phIsNewRegistration())
		{
			if(!Ph_Fedex_Woocommerce_Shipping_Common::phHasActiveLicense())
			{
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('--------------------------- FEDEX SHIPMENT ---------------------------');
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('Please use a valid plugin license to use WooCommerce FedEx Shipping Plugin with Print Label');

				wf_admin_notice::add_notice( __('Please use a valid license to continue using WooCommerce FedEx Shipping Plugin with Print Label', 'ph-fedex-woocommerce-shipping') );
				wp_redirect(admin_url('/post.php?post='.$order_id.'&action=edit'));
		  		exit;
			}
		}

		if ( ! class_exists( 'wf_fedex_woocommerce_shipping_admin_helper' ) )
			include_once 'class-wf-fedex-woocommerce-shipping-admin-helper.php';
		
		$woofedexwrapper = new wf_fedex_woocommerce_shipping_admin_helper();

		if( !empty($service_arr) && !empty($service_arr[0]) ){
			$serviceCode 	= $service_arr[0];
		}else{
			$serviceCode 	= $this->wf_get_shipping_service($order,false);
		}

		if( empty($serviceCode) ){
			wf_admin_notice::add_notice( __("Not found any Service Code for the Order #".$order_id, 'ph-fedex-woocommerce-shipping') );
			return false;
		}

		$woofedexwrapper->print_label($order,$serviceCode,$order_id);
	}

	public function wf_create_return_shipment($shipment_id, $order_id)
	{
		if (!class_exists('wf_fedex_woocommerce_shipping_admin_helper'))
		include_once 'class-wf-fedex-woocommerce-shipping-admin-helper.php';

		$order = wc_get_order($order_id);

		$woofedexwrapper = new wf_fedex_woocommerce_shipping_admin_helper();
		$serviceCode = $this->wf_get_shipping_service($order, false);
		$woofedexwrapper->print_return_label($shipment_id, $order_id, $serviceCode);
	}

	public function wf_add_fedex_metabox($screen_id, $order)
	{
		// Check whether to show meta box on order is enabled or not
		if ($this->display_fedex_meta_box_on_order == 'no') {
			return;
		}

		$is_hpo_enabled = class_exists('\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController') ? wc_get_container()->get(CustomOrdersTableController::class)->custom_orders_table_usage_is_enabled() : false;
		$screen_type 	= $is_hpo_enabled ? wc_get_page_screen_id('shop-order') : 'shop_order';

		add_meta_box(
			'PH_Fedex_Metabox',
			__('FedEx Shipment Label', 'ph-fedex-woocommerce-shipping'),
			[$this, 'wf_fedex_metabox_content'],
			$screen_type,
			'advanced',
			'high'
		);

		if ($this->fedex_tracking) {

			add_meta_box(
				'ph_fedex_tracking_metabox',
				__('View FedEx Live Tracking Details', 'ph-fedex-woocommerce-shipping'),
				[$this, 'ph_fedex_tracking_metabox_content'],
				$screen_type,
				'advanced',
				'default'
			);
		}
	}

	public function wf_fedex_generate_packages()
	{
		if (!$this->ph_user_permission(isset($_GET['auto_generate']) ? $_GET['auto_generate'] : null)) {

			echo "You don't have admin privileges to view this page.";
			exit;
		}

		$order_id 	= base64_decode($_GET['wf_fedex_generate_packages']);
		$order		= wc_get_order($order_id);

		if (!$order instanceof WC_Order) {
			return;
		}

		if (!$this->debug && $this->settings['automate_label_generation'] == 'yes' && !isset($_GET['wf_fedex_generate_packages'])) {

			// Add transient to check for duplicate label generation
			$transient			= 'ph_fedex_auto_shipment' . md5($order->get_id());
			$processed_order	= get_transient($transient);

			// If requested order is already processed, return.
			if ($processed_order) {
				return;
			}

			// Set transient for 1 min to avoid duplicate label generation
			set_transient($transient, $order->get_id(), 60);
		}

		$this->xa_generate_package($order);

		if ((!$this->debug) ||  (isset($_GET['wf_fedex_generate_packages'])) || ($this->settings['automate_label_generation'] == 'no')) {
			wp_redirect(admin_url('/post.php?post=' . $order_id . '&action=edit#PH_Fedex_Metabox'));
		}

		exit;
	}

	private function xa_generate_package($order)
	{
		if (!class_exists('wf_fedex_woocommerce_shipping_admin_helper')) {
			include_once 'class-wf-fedex-woocommerce-shipping-admin-helper.php';
		}

		$order_id 			= $order->get_id();
		$woofedexwrapper	= new wf_fedex_woocommerce_shipping_admin_helper();
		$packages			= $woofedexwrapper->wf_get_package_from_order($order);
		$min_order_amount_for_insurance = 0;

		if (property_exists($woofedexwrapper, 'min_order_amount_for_insurance')) {

			$min_order_amount_for_insurance = $woofedexwrapper->min_order_amount_for_insurance;
		}

		if (isset($packages['error'])) {
			wf_admin_notice::add_notice(sprintf(__($packages['error'], 'ph-fedex-woocommerce-shipping')), 'error');
			return;
		}

		foreach ($packages as $package) {
			$package = apply_filters('wf_customize_package_on_generate_label', $package, $order->get_id());		//Filter to customize the package
			$package_data[] = $woofedexwrapper->get_fedex_packages($package);
		}

		$order_subtotal = is_object($order) ? $order->get_subtotal() : 0;

		if (isset($package_data) && !empty($package_data)) {

			foreach ($package_data as $package_group_key =>	$package_group) {

				if (!empty($package_group) && is_array($package_group)) {

					foreach ($package_group as $stored_package_key => $stored_package) {

						if (isset($stored_package['InsuredValue']) &&  $min_order_amount_for_insurance > $order_subtotal) {

							unset($package_data[$package_group_key][$stored_package_key]['InsuredValue']);
						}
						
						// To be improved by introducing static function for deleting meta
						$order_meta_handler = new PH_WC_Fedex_Storage_Handler($order);
						$order_meta_handler->ph_delete_meta_data($order_id, 'ph_get_no_of_packages' . $stored_package['GroupNumber']);
						$order_meta_handler->ph_save_meta_data();
					}
				}
			}
		}

		PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data($order_id, '_wf_fedex_stored_packages', $package_data);

		if (!isset($_GET['wf_fedex_generate_packages'])) {
			//For automatic label generation 
			do_action('wf_after_package_generation', $order_id, $package_data);
		}
	}

	public function ph_fedex_tracking_metabox_content($post_or_order_object)
	{
		$order = ($post_or_order_object instanceof WP_Post) ? wc_get_order($post_or_order_object->ID) : $post_or_order_object;

		if (!$order instanceof WC_Order) {
			return;
		}

		$order_id = $order->get_id();
		$shipmentIds = $this->ph_get_unique_shipment_ids($order_id);

		echo '<input type="hidden" class="order_id" id="order_id" value="'.$order_id.'"/>';

		echo '</br><strong>'.__( 'Click to track your shipments  ', '').'</strong><br>';
		echo '<span class=" button-primary ph-button-tracking" id="ph_track_fedex" style="margin: 10px 2px 2px 2px;">Start Tracking</span></br></br>';
		
		if( ! empty($shipmentIds) ) {

			foreach ( $shipmentIds as $shipmentId ) {

				$tracking_status = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_ph_fedex_tracking_status'.$shipmentId);
				$tracking_error  = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_ph_fedex_tracking_status_error'.$shipmentId);

				if ( !empty($tracking_status)) {

					$tracking_link = 'https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber='.$shipmentId;
					echo "<strong style='margin: 6px 5px 5px 4px;'>".__( 'Tracking ID : ', '')."</strong><a href='".$tracking_link."' target='_blank'>".$shipmentId."</a><br/><br/>";
					
					echo "<table class='ph_fedex_tracking_status_history_table'>";

					echo "<tr>";
					echo "<th>". __( 'Location', 'ph-fedex-woocommerce-shipping'). "</th>";
					echo "<th>". __( 'Date', 'ph-fedex-woocommerce-shipping') ."</th>";
					echo "<th>". __( 'Activity', 'ph-fedex-woocommerce-shipping'). "</th>";
					echo "</tr>";

					foreach( $tracking_status as $key => $tracking_infos ) {

						if ( $key == 'shipment_tracking' ) {

							foreach ( $tracking_infos as $tracking_info ) {

								$address = $tracking_info['location'];
								$date 	 = $tracking_info['date'];
								$status  = $tracking_info['status'];

								echo "<tr>";
								echo "<td>". $address. "</td>";
								echo "<td>". $date. "</td>";
								echo "<td>". $status. "</td>";
								echo "</tr>";
							}
						}
					}
					echo "</table><br/>";

				} else if( isset($tracking_error) && !empty($tracking_error) ){

					$tracking_link = 'https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber='.$shipmentId;
					echo "<strong style='margin: 6px 5px 5px 4px;'>".__( 'Tracking ID : ', '')."</strong><a href='".$tracking_link."' target='_blank'>".$shipmentId."</a><br/><br/>";

					echo "<div><strong>".$tracking_error."</strong></div></br>";
				}
			}
		} 
	}

	public function wf_fedex_metabox_content($post_or_order_object){
		
		$order = ($post_or_order_object instanceof WP_Post) ? wc_get_order($post_or_order_object->ID) : $post_or_order_object;

		if (!$order instanceof WC_Order) {
			return;
		}

		$order_id = $order->get_id();

		$shipmentIds = $this->ph_get_unique_shipment_ids($order_id);

		$shipment_void_ids = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipment_void', false);

		$shipmentErrorMessage = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipmentErrorMessage');
		$shipment_void_error_message = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipment_void_errormessage');

		$this->ph_button_names = apply_filters( 'ph_modify_edit_order_page_button_names', $this->ph_button_names, $order_id );   //filter to modify edit order page button titles
		
		//Only Display error message if the process is not complete. If the Invoice link available then Error Message is unnecessary
		if(!empty($shipmentErrorMessage))
		{
			echo '<div class="error"><p>' . sprintf( __( 'FedEx Create Shipment Error:<br/>%s', 'ph-fedex-woocommerce-shipping' ), $shipmentErrorMessage) . '</p></div>';
		}

		if(!empty($shipment_void_error_message)){
			echo '<div class="error"><p>' . sprintf( __( 'Void Shipment Error:%s', 'ph-fedex-woocommerce-shipping' ), $shipment_void_error_message) . '</p></div>';
		}	

		echo '<ul>';

		if (!empty($shipmentIds)) {
			foreach($shipmentIds as $shipmentId) {
				$selected_sevice = $this->wf_get_shipping_service($order,true,$shipmentId);	
				if(!empty($selected_sevice))
					echo "<li>Shipping Service: <strong>$selected_sevice</strong></li>";		
				
				?><li><strong><?php _e( 'Shipment Tracking ID: ' ); ?></strong><a href="https://www.fedex.com/fedextrack/no-results-found?trknbr=<?php echo $shipmentId ?>" target="_blank"><?php echo $shipmentId ?></a><?php

				$usps_trackingid = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_usps_trackingid_'.$shipmentId);
				if(!empty($usps_trackingid)){
					echo "<br><strong>USPS Tracking #:</strong> ".$usps_trackingid;
				}
				
				if((is_array($shipment_void_ids) && in_array($shipmentId,$shipment_void_ids))){
					echo "<br> This shipment $shipmentId is terminated.";
				}

				$additional_labels = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_fedex_additional_label_'.$shipmentId);

				if( ! empty($additional_labels) ) {
					$additional_label_tracking_number = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, '_ph_woo_fedex_additional_tracking_number_'.$shipmentId, true );
					if( ! empty($additional_label_tracking_number ) )	echo "<li> Additional Tracking Number #: $additional_label_tracking_number</li>";
				}

				echo '<hr>';
				$packageDetailForTheshipment = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_packageDetails_'.$shipmentId);
				if(!empty($packageDetailForTheshipment)){
					foreach($packageDetailForTheshipment as $dimentionKey => $dimentionValue){
						if($dimentionValue){
							echo '<strong>' . $dimentionKey . ': ' . '</strong>' . $dimentionValue ;
							echo '<br />';
						}
					}
					echo '<hr>';
				}

				$shipping_label = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipmentId);

				if(!empty($shipping_label)){
					$download_url = admin_url('/post.php?wf_fedex_viewlabel='.base64_encode($shipmentId.'|'.$order_id));?>
					<a class="button tips" href="<?php echo $download_url; ?>" target="_blank" data-tip="<?php _e( $this->ph_button_names['print_label_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['print_label_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
					<?php 
				}
				
				if(!empty($additional_labels) && is_array($additional_labels)){
					foreach($additional_labels as $additional_key => $additional_label){
						$download_add_label_url = admin_url('/post.php?wf_fedex_additional_label='.base64_encode($shipmentId.'|'.$order_id.'|'.$additional_key));?>
						<a class="button tips" href="<?php echo $download_add_label_url; ?>" data-tip="<?php _e( $this->ph_button_names['additional_label_btn_tip'], 'ph-fedex-woocommerce-shipping'); ?>"><?php _e( $this->ph_button_names['additional_label_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
						<?php
					}
				}
				if((!is_array($shipment_void_ids) || !in_array($shipmentId,$shipment_void_ids))){
					$void_shipment_link = admin_url('/post.php?wf_fedex_void_shipment=' . base64_encode($shipmentId.'||'.$order_id));?>				
					<a class="button tips ph-disable-on-click" href="<?php echo $void_shipment_link; ?>" data-tip="<?php _e( $this->ph_button_names['void_shipment_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['void_shipment_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
					<?php 
				}

				$shipping_return_label = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_returnLabel_'.$shipmentId);
				$return_shipment_id = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_returnShipmetId');

				echo '<hr>';
				if(!empty($shipping_return_label)){
					$download_url = admin_url('/post.php?wf_fedex_viewReturnlabel='.base64_encode($shipmentId.'|'.$order_id) );
					
					?><li><strong><?php _e( 'Return Shipment Tracking ID: ' ); ?></strong><a href="https://www.fedex.com/fedextrack/no-results-found?trknbr=<?php echo $return_shipment_id ?>" target="_blank"><?php echo $return_shipment_id ?></a>

					<li><a class="button tips" href="<?php echo $download_url; ?>" target="_blank" data-tip="<?php _e( $this->ph_button_names['print_return_label_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['print_return_label_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
					<?php 

					$additional_return_labels = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_additional_return_label_' . $shipmentId);

					if (!empty($additional_return_labels) && is_array($additional_return_labels)) {

						foreach ($additional_return_labels as $additional_key => $additional_label) {

							$download_add_return_label_url = admin_url('/post.php?ph_fedex_view_additional_return_label='.base64_encode($shipmentId.'|'.$order_id.'|'.$additional_key));?>
							<a class="button tips" href="<?php echo $download_add_return_label_url; ?>" data-tip="<?php _e( $this->ph_button_names['additional_return_label_btn_tip'], 'ph-fedex-woocommerce-shipping'); ?>"><?php _e( $this->ph_button_names['additional_return_label_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
							<?php
						}		
					}

					?> </li> <?php
				}else{
					$selected_sevice = $this->wf_get_shipping_service($order);	
					echo '<select class="fedex_return_service select">';
					foreach($this->custom_services as $service_code => $service){
						if($service['enabled'] == true ){
							echo '<option value="'.$service_code.'" ' . selected($selected_sevice,$service_code) . ' >'.$service_code.'</option>';
						}	
					}
					echo'</select>'?>
					<a class="button button-primary fedex_create_return_shipment tips" href="<?php echo admin_url( '/post.php?wf_create_return_label='.base64_encode($shipmentId.'|'.$order_id) ); ?>" data-tip="<?php _e( $this->ph_button_names['generate_return_label_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['generate_return_label_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a><?php
				}
				echo '<hr style="border-color:#0074a2"></li>';
			} 

			if($shipment_void_error_message){
				$client_reset_link  = admin_url('/post.php?ph_client_reset_link=' . base64_encode($order_id));
			    $void_shipments 	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_woo_fedex_shipment_client_reset', false);

				if($this->client_side_reset && $void_shipments ) { 

					echo '<p>If you have already cancelled this shipment by calling FedEx customer care, and you would like to create shipment again then click.</p>';?>				
					<a class="button button-primary tips" id="fedex_client_side_reset" href="<?php echo $client_reset_link; ?>" data-tip="<?php _e( $this->ph_button_names['clear_data_btn_tip'], 'ph-fedex-woocommerce-shipping'); ?>" OnClick="return confirm('The shipping labels and the tracking details for all the packages will be removed from the Order page.                 Are you sure you want to continue?')";  ><?php _e( $this->ph_button_names['clear_data_btn'], 'ph-fedex-woocommerce-shipping'); ?></a><?php 
					echo '<p style="color:red"><strong>Note: </strong>Previous shipment details and label will be removed from Order page.</p>';	

				} 	  
			}else if( (count($shipmentIds) == count($shipment_void_ids ) )){

				$clear_history_link = admin_url('/post.php?wf_clear_history=' . base64_encode($order_id));?>				
					<a class="button button-primary tips"; href="<?php echo $clear_history_link; ?>"  data-tip="<?php _e( $this->ph_button_names['clear_history_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['clear_history_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a><?php 
			}				
		}
		else {
			$stored_packages = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_wf_fedex_stored_packages');
			if(empty($stored_packages)	&&	!is_array($stored_packages)){
				echo '<strong>'.__( 'Auto generate packages.', 'ph-fedex-woocommerce-shipping' ).'</strong></br>';
				?>
				<a class="button button-primary tips fedex_generate_packages" href="<?php echo admin_url( '/post.php?wf_fedex_generate_packages='.base64_encode($order_id) ); ?>" data-tip="<?php _e( $this->ph_button_names['generate_package_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['generate_package_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a><hr style="border-color:#0074a2">
				<?php
			}else{
				$generate_url = admin_url('/post.php?wf_fedex_createshipment='.$order_id);

				$insurance_style = ( $this->settings['insure_contents'] == 'yes' ) ? null : 'style="visibility:hidden"';
 
				echo '<li>';
					echo '<h4>'.__( 'Package(s)' , 'ph-fedex-woocommerce-shipping').': </h4>';
					echo '<table style="width: 100%;" id="wf_fedex_package_list" class="wf-shipment-package-table">';					
						echo '<tr>';
						   echo '<th style="width: 10%;padding:8px" id="ph_fedex_packages_no" class="ph_fedex_packages_no">'.__('No. of Packages</br>(Max. 25)', 'ph-fedex-woocommerce-shipping').'</th>';
						if (isset($stored_packages[0]) && isset($stored_packages[0][0]) && isset($stored_packages[0][0]['boxName'])) {
							echo '<th style="width: 30%; padding:8px" id="ph_fedex_manual_box_name" class="ph_fedex_manual_box_name">'.__('Box Name', 'ph-fedex-woocommerce-shipping').'</th>';
						}
							echo '<th style="width: 15%;">'.__('Wt.', 'ph-fedex-woocommerce-shipping').'</br>('.$this->weight_unit.')</th>';
							echo '<th style="width: 15%;">'.__('L', 'ph-fedex-woocommerce-shipping').'</br>('.$this->dimension_unit.')</th>';
							echo '<th style="width: 15%;">'.__('W', 'ph-fedex-woocommerce-shipping').'</br>('.$this->dimension_unit.')</th>';
							echo '<th style="width: 15%;">'.__('H', 'ph-fedex-woocommerce-shipping').'</br>('.$this->dimension_unit.')</th>';
							echo '<th style="width: 15%;">'.__('Insur.', 'ph-fedex-woocommerce-shipping').'</th>';
							echo '<th style="width: 15%;">';
								echo __('Select Service', 'ph-fedex-woocommerce-shipping');
								echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Select the FedEx service.', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
							echo '</th>';
							echo '<th style="width: 15%;">';
								_e('Remove', 'ph-fedex-woocommerce-shipping');
								echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Remove FedEx generated packages (Beta Version).', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" />';
							echo '</th>';
						echo '</tr>';
						
						//case of multiple shipping address
						$multiship = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, '_multiple_shipping');
						if( $multiship ){
							$multi_ship_packages  = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_wcms_packages');
						}
						
						foreach($stored_packages as $package_group_key	=>	$package_group){
							if( !is_array($package_group) ){
								$package_group = array();
							}
							foreach($package_group as $stored_package_key	=>	$stored_package){

								$order_no = $order_id;
								$nos_of_packages  = 1;

								if ( !empty( $order_no )) {
									
									$nos_of_packages  = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_no, 'ph_get_no_of_packages' . $stored_package['GroupNumber']);
								}

								//PDS-179
								$temp_signature 		= isset($stored_package['signature_option']) && !empty($stored_package['signature_option']) ?
								$stored_package['signature_option'] : 0;
								$this->signature_temp 	= (isset($this->signature_temp) && !empty($this->signature_temp)) && $this->signature_temp > $temp_signature ? $this->signature_temp : $temp_signature;
								$dimensions	=	$this->get_dimension_from_package($stored_package);
								$insurance_amount = ! empty($stored_package['InsuredValue']['Amount']) ? $stored_package['InsuredValue']['Amount'] : null;
								
								if(is_array($dimensions)){
									?>
									<tr>
										<td><select id="fedex_packages_no" name="fedex_packages_no[]" class="ph_fedex_packages_no" />
											<?php 
											$allowed_no_packages 	= array(
												'1'	   				=> __( '1', 'ph-fedex-woocommerce-shipping' ),
												'2'	   				=> __( '2', 'ph-fedex-woocommerce-shipping' ),
												'3'	   				=> __( '3', 'ph-fedex-woocommerce-shipping' ),
												'4'	   				=> __( '4', 'ph-fedex-woocommerce-shipping' ),
												'5'	   				=> __( '5', 'ph-fedex-woocommerce-shipping' ),
												'6'	   				=> __( '6', 'ph-fedex-woocommerce-shipping' ),
												'7'	   				=> __( '7', 'ph-fedex-woocommerce-shipping' ),
												'8'	   				=> __( '8', 'ph-fedex-woocommerce-shipping' ),
												'9'	   				=> __( '9', 'ph-fedex-woocommerce-shipping' ),
												'10'	   			=> __( '10', 'ph-fedex-woocommerce-shipping' ),
												'11'	   			=> __( '11', 'ph-fedex-woocommerce-shipping' ),
												'12'	   			=> __( '12', 'ph-fedex-woocommerce-shipping' ),
												'13'	   			=> __( '13', 'ph-fedex-woocommerce-shipping' ),
												'14'	   			=> __( '14', 'ph-fedex-woocommerce-shipping' ),
												'15'	   			=> __( '15', 'ph-fedex-woocommerce-shipping' ),
												'16'	   			=> __( '16', 'ph-fedex-woocommerce-shipping' ),
												'17'	   			=> __( '17', 'ph-fedex-woocommerce-shipping' ),
												'18'	   			=> __( '18', 'ph-fedex-woocommerce-shipping' ),
												'19'	   			=> __( '19', 'ph-fedex-woocommerce-shipping' ),
												'20'	   			=> __( '20', 'ph-fedex-woocommerce-shipping' ),
												'21'	   			=> __( '21', 'ph-fedex-woocommerce-shipping' ),
												'22'	   			=> __( '22', 'ph-fedex-woocommerce-shipping' ),
												'23'	   			=> __( '23', 'ph-fedex-woocommerce-shipping' ),
												'24'	   			=> __( '24', 'ph-fedex-woocommerce-shipping' ),
												'25'	   			=> __( '25', 'ph-fedex-woocommerce-shipping' ),
											);
											foreach ($allowed_no_packages as $key => $value) {
												if($key == $nos_of_packages){
													echo "<option value='".$key."' selected >".$value."</option>";
												} else {
													echo "<option value='".$key."'>".$value."</option>";
												}
											}
											?>
										</select></td>
									<?php
									   if (isset($stored_package['boxName'])) {

										$box_name 	= isset($stored_package['boxName']) && !empty($stored_package['boxName'])? $stored_package['boxName']: "Unpacked Product";																 
									?>
										<td><input type="text"  style="width: 200px;" id="phFedexManualBoxName" name="fedex_manual_box_name[]" class="ph_fedex_manual_box_name" size="10" value="<?php echo $box_name;?>" readonly /></td>

									<?php } ?>
										<td><input type="text" style="margin:7px;" id="fedex_manual_weight" name="fedex_manual_weight[]" size="2" value="<?php echo $dimensions['Weight'];?>" /></td>	 
										<td><input type="text" id="fedex_manual_length" name="fedex_manual_length[]" size="2" value="<?php echo $dimensions['Length'];?>" /></td>
										<td><input type="text" id="fedex_manual_width" name="fedex_manual_width[]" size="2" value="<?php echo $dimensions['Width'];?>" /></td>
										<td><input type="text" id="fedex_manual_height" name="fedex_manual_height[]" size="2" value="<?php echo $dimensions['Height'];?>" /></td>
										<td><input <?php echo $insurance_style; ?> type="text" id="fedex_manual_insurance" name="fedex_manual_insurance[]" size="2" value="<?php echo $insurance_amount;?>" /></td>
										<td><?php
											$package_dest_country = ( isset( $multi_ship_packages[$package_group_key]['destination']['country'] ) ) ? $multi_ship_packages[$package_group_key]['destination']['country'] : false;
											// $stored_package['service'] is setted by Multivendor Plugin
											if( ! empty($stored_package['service']) ) {
												$selected_sevice = $stored_package['service'];
											}
											elseif( isset( $multi_ship_packages[$package_group_key] ) ) {
												$selected_sevice = $this->wf_get_shipping_service($order,false, false, $package_group_key);
											}
											else{
												$selected_sevice = $this->wf_get_shipping_service($order);
											}
											echo '<select class="fedex_manual_service select">';

											$destinationCountryCode = $order->get_address('shipping');
											$destinationCountryCode = $destinationCountryCode['country'];

											// Show services based on origin country
											$services 				= include('data-wf-service-codes.php');
											$countryServiceMapper	= include('data-wf-country-service-mapper.php');
											$mappedCountry			= array_key_exists( $this->origin_country, $countryServiceMapper ) ? $countryServiceMapper[$this->origin_country] : '';
											$services				= array_key_exists( $mappedCountry, $services ) ? $services[$mappedCountry] : $services['US'];

											if( ( $this->origin_country == 'PR' || $destinationCountryCode == 'PR' ) && !isset( $this->custom_services['INTERNATIONAL_PRIORITY'] ) ) {
													
												$this->custom_services['INTERNATIONAL_PRIORITY'] = [
													'name' => 'FedEx International Priority®',
													'order' => '',
													'enabled' => 1,
													'adjustment' => '',
													'adjustment_percent' => ''
												];		
												
												if( isset( $this->custom_services['FEDEX_INTERNATIONAL_PRIORITY'] ) ) {
													unset( $this->custom_services['FEDEX_INTERNATIONAL_PRIORITY'] );
												}
											}

											if ( $this->xa_show_all_shipping_methods ) {
												
												foreach ( $this->custom_services as $service_code => $service ) {

													$service_name = isset($service['name']) && !empty($service['name']) ? $service['name'] : $services[$service_code];
													echo '<option value="'.$service_code.'" ' . selected($selected_sevice,$service_code) . ' >'.$service_name.'</option>';
												}
											}
											else
											{
												foreach($this->custom_services as $service_code => $service)
												{
													if($service['enabled'] == true && $this->wf_is_service_valid_for_country($order,$service_code, $package_dest_country) == true)
													{
														$service_name = isset($service['name']) && !empty($service['name']) ? $service['name'] : $services[$service_code];
														echo '<option value="'.$service_code.'" ' . selected($selected_sevice,$service_code) . ' >'.$service_name.'</option>';
													}
												}
											}?>
										</td>
										<td><a class="wf_fedex_package_line_remove" id="<?php echo $package_group_key.'_'.$stored_package_key; ?>">&#x26D4;</a></td>
										<td>&nbsp;</td>
									</tr>
									<?php
								}
							}
						}
					echo '</table>';
				?>
				<a style="font-size: 12px; margin-left: 4px; margin-right: 5px; margin-top: 15px;" class="button tips wf-action-button wf-add-button" id="wf_fedex_add_package" data-tip="<?php _e( $this->ph_button_names['add_package_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['add_package_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
				<a style="margin: 4px; margin-right: 5px; margin-top: 15px;" class="button tips fedex_generate_packages" href="<?php echo admin_url( '/post.php?wf_fedex_generate_packages='.base64_encode($order_id) ); ?>" data-tip="<?php _e( $this->ph_button_names['re_generate_package_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['re_generate_package_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a><li/>
				<script type="text/javascript">
					jQuery(document).ready(function(){
						
						
						jQuery('#wf_fedex_add_package').on("click", function(){
							var new_row = '<tr>';
							new_row		+= '<td>';
						    new_row		+= '	<select id="fedex_packages_no" name="fedex_packages_no[]" class="ph_fedex_packages_no">';
						    <?php 
						    $allowed_no_packages 	= array(
							'1'	   				=> __( '1', 'ph-fedex-woocommerce-shipping' ),
							'2'	   				=> __( '2', 'ph-fedex-woocommerce-shipping' ),
							'3'	   				=> __( '3', 'ph-fedex-woocommerce-shipping' ),
							'4'	   				=> __( '4', 'ph-fedex-woocommerce-shipping' ),
							'5'	   				=> __( '5', 'ph-fedex-woocommerce-shipping' ),
							'6'	   				=> __( '6', 'ph-fedex-woocommerce-shipping' ),
							'7'	   				=> __( '7', 'ph-fedex-woocommerce-shipping' ),
							'8'	   				=> __( '8', 'ph-fedex-woocommerce-shipping' ),
							'9'	   				=> __( '9', 'ph-fedex-woocommerce-shipping' ),
							'10'	   			=> __( '10', 'ph-fedex-woocommerce-shipping' ),
							'11'	   			=> __( '11', 'ph-fedex-woocommerce-shipping' ),
							'12'	   			=> __( '12', 'ph-fedex-woocommerce-shipping' ),
							'13'	   			=> __( '13', 'ph-fedex-woocommerce-shipping' ),
							'14'	   			=> __( '14', 'ph-fedex-woocommerce-shipping' ),
							'15'	   			=> __( '15', 'ph-fedex-woocommerce-shipping' ),
							'16'	   			=> __( '16', 'ph-fedex-woocommerce-shipping' ),
							'17'	   			=> __( '17', 'ph-fedex-woocommerce-shipping' ),
							'18'	   			=> __( '18', 'ph-fedex-woocommerce-shipping' ),
							'19'	   			=> __( '19', 'ph-fedex-woocommerce-shipping' ),
							'20'	   			=> __( '20', 'ph-fedex-woocommerce-shipping' ),
							'21'	   			=> __( '21', 'ph-fedex-woocommerce-shipping' ),
							'22'	   			=> __( '22', 'ph-fedex-woocommerce-shipping' ),
							'23'	   			=> __( '23', 'ph-fedex-woocommerce-shipping' ),
							'24'	   			=> __( '24', 'ph-fedex-woocommerce-shipping' ),
							'25'	   			=> __( '25', 'ph-fedex-woocommerce-shipping' ),
						    );
						    foreach($allowed_no_packages as $key => $value)
							{?>
								new_row	+=  '<option value="<?php echo $key ?>"><?php echo $value ?></option>';
								<?php
							}
							?>
						    new_row		+= '</td>';

                            if( jQuery('#wf_fedex_package_list .ph_fedex_manual_box_name').length > 0 ) {
	                            new_row 	+= '<td><input type="text"  style="margin:7px;"id="phFedexManualBoxName" class="ph_fedex_manual_box_name" size="10" value="Manual Box" readonly /></td>';
                            }
								new_row 	+= '<td><input type="text" id="fedex_manual_weight" name="fedex_manual_weight[]" size="2" value="0"></td>';
								new_row 	+= '<td><input type="text" id="fedex_manual_length" name="fedex_manual_length[]" size="2" value="0"></td>';								
								new_row 	+= '<td><input type="text" id="fedex_manual_width" name="fedex_manual_width[]" size="2" value="0"></td>';
								new_row 	+= '<td><input type="text" id="fedex_manual_height" name="fedex_manual_height[]" size="2" value="0"></td>';
								new_row 	+= '<td><input <?php echo $insurance_style; ?> type="text" id="fedex_manual_insurance" name="fedex_manual_insurance[]" size="2" value=""></td>';
								new_row		+= '<td>';
								new_row		+= '	<select class="fedex_manual_service select">';
								<?php

								// Show services based on origin country
								$services 				= include('data-wf-service-codes.php');
								$countryServiceMapper	= include('data-wf-country-service-mapper.php');
								$mappedCountry			= array_key_exists( $this->origin_country, $countryServiceMapper ) ? $countryServiceMapper[$this->origin_country] : '';
								$services				= array_key_exists( $mappedCountry, $services ) ? $services[$mappedCountry] : $services['US'];
								
								if($this->xa_show_all_shipping_methods==true)
											{
												
												// Add INTERNATIONAL_PRIORITY instead of FEDEX_INTERNATIONAL_PRIORITY if origin or destination country code is PR
												if( ( $this->origin_country == 'PR' || $destinationCountryCode == 'PR' ) && !isset( $services['INTERNATIONAL_PRIORITY'] ) ) {
													
													$services['INTERNATIONAL_PRIORITY'] = 'FedEx International Priority®';
													
													if( isset( $services['FEDEX_INTERNATIONAL_PRIORITY'] ) ) {
														unset( $services['FEDEX_INTERNATIONAL_PRIORITY'] );
													}
												}

												foreach ( $this->custom_services as $service_code => $service ) {

													$service_name = isset($service['name']) && !empty($service['name']) ? $service['name'] : $services[$service_code];
													?>
														new_row	+=  "<option value='<?php echo $service_code ?>'<?php selected($selected_sevice,$service_code) ?>><?php echo $service_name ?></option>";
													<?php
												}
											}
											else
											{
												if( ! isset($package_dest_country) ) {
													$package_dest_country = '';
												}

												// Add INTERNATIONAL_PRIORITY instead of FEDEX_INTERNATIONAL_PRIORITY if origin or destination country code is PR
												if( ( $this->origin_country == 'PR' || $destinationCountryCode == 'PR' ) && !isset( $this->custom_services['INTERNATIONAL_PRIORITY'] ) ) {
													
													$this->custom_services['INTERNATIONAL_PRIORITY'] = [
														'name' => 'FedEx International Priority®',
														'order' => '',
														'enabled' => 1,
														'adjustment' => '',
														'adjustment_percent' => ''
													];			

													if( isset( $this->custom_services['FEDEX_INTERNATIONAL_PRIORITY'] ) ) {
														unset( $this->custom_services['FEDEX_INTERNATIONAL_PRIORITY'] );
													}
												}

											   foreach($this->custom_services as $service_code => $service)
												   {
													if($service['enabled'] == true && $this->wf_is_service_valid_for_country($order,$service_code, $package_dest_country) == true)
													{

														$service_name = isset($service['name']) && !empty($service['name']) ? $service['name'] : $services[$service_code];
														?>
															new_row		+= "<option value='<?php echo $service_code?>'<?php selected($selected_sevice,$service_code) ?>><?php echo $service_name ?></option>";
														<?php
													}
												}
											} ?>
								new_row		+= '</td>';
								new_row 	+= '<td><a class="wf_fedex_package_line_remove">&#x26D4;</a></td>';
							new_row 	+= '</tr>';
							
							jQuery('#wf_fedex_package_list tr:last').after(new_row);
						});
						
						jQuery(document).on('click', '.wf_fedex_package_line_remove', function(){
							jQuery(this).closest('tr').remove();
						});
					});
				</script><?php
				
				// Rates on order page
				$generate_packages_rates = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_fedex_generate_packages_rates_response',);

				echo '<li><table id="wf_fedex_service_select" class="wf-shipment-calculate-cost-table" style="margin-bottom: 10px;margin-top: 15px;box-shadow:.5px .5px 5px lightgrey;">';

					echo '<tr>';
						echo '<th>Select Service</th>';
						echo '<th style="text-align:left;padding:5px; font-size:13px;">'.__('Service Name', 'ph-fedex-woocommerce-shipping').'</th>';
						echo '<th style="text-align:left; font-size:13px;">'.__('Delivery Time', 'ph-fedex-woocommerce-shipping').' </th>';
						echo '<th style="text-align:left;font-size:13px;">'.__('Cost (', 'ph-fedex-woocommerce-shipping').get_woocommerce_currency_symbol().__(')', 'ph-fedex-woocommerce-shipping').' </th>';
					echo '</tr>';
					
					echo '<tr>';
						echo "<td style = 'padding-bottom: 10px; padding-left: 15px; '><input name='wf_fedex_service_choosing_radio' id='wf_fedex_service_choosing_radio' value='wf_fedex_individual_service' type='radio' checked='true'></td>";
						echo "<td colspan = '3' style= 'padding-bottom: 10px; text-align:left;'><b>".__('Choose Shipping Methods</b> - Select this option to choose FedEx services for each package (Shipping rates will be applied accordingly).', 'ph-fedex-woocommerce-shipping')."</td>";
					echo "</tr>";
					
					if( ! empty($generate_packages_rates) ) {
						$wp_date_format = get_option('date_format');
						foreach( $generate_packages_rates as $key => $rates ) {
							$fedex_service = explode( ':', $rates['id']);
							$est_date_style = empty($rates['meta_data']['fedex_delivery_time']) ? "style=visibility:hidden;" : null;
							echo '<tr style="padding:10px;">';
								echo "<td style = 'padding-left: 15px;'><input name='wf_fedex_service_choosing_radio' id='wf_fedex_service_choosing_radio' value='".end($fedex_service)."' type='radio' ></td>";
								echo "<td>".$rates['label']."</td>";
								echo "<td $est_date_style>".date( $wp_date_format, strtotime($rates['meta_data']['fedex_delivery_time']) )."</td>";
								echo "<td>".( ! empty($this->settings['conversion_rate']) ? $this->settings['conversion_rate'] * $rates['cost'] : $rates['cost'] )."</td>";
							echo "</tr>";
						}
					}

				echo '</table></li>';
				//End of Rates on order page
				?>
				<a style="margin-left: 4px; margin-top: 10px; margin-bottom: 10px" class="button tips wf_fedex_generate_packages_rates button-secondary" href="<?php echo admin_url( '/post.php?wf_fedex_generate_packages_rates='.base64_encode($order_id) ); ?>" data-tip="<?php _e( $this->ph_button_names['calculate_cost_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e( $this->ph_button_names['calculate_cost_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a>
				<?php

				// If payment method is COD, check COD by default.
				$order_data 			= new WC_Order($order_id);
				$order_payment_method 	= $order_data->get_payment_method();
				$cod_checked 			= $order_payment_method == 'cod' ? 'checked': '';
				$items_cost 			= $order_data->get_subtotal();
				$order_currency 		= $order_data->get_currency();
				$b13_post_currency 		= "CAD";
				$woocommerce_currency_conversion_rate = get_option('woocommerce_multicurrency_rates');
				$shipping_country 		= $order_data->get_shipping_country();

				$ph_sat_checked 		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_sat_delivery_option_meta');

				if ( !empty($ph_sat_checked) ) {

					$sat_checked 			=  $ph_sat_checked == 'yes' ? 'checked' : '';
				} else {

					$sat_checked 			= isset($this->settings['saturday_delivery_label']) && !empty($this->settings['saturday_delivery_label']) && $this->settings['saturday_delivery_label'] == 'yes' ? 'checked': '';
				}

				$ph_booking_conf_num 		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_booking_confirmation_num_meta');

				echo '<li><table id="ph_fedex_order_edit_page_options" class="ph-order-edit-options-table" style="margin-bottom: 10px;margin-top: 10px;box-shadow:.5px .5px 5px lightgrey;">';
				echo '<tr><th colspan="2"; style="text-align:center;padding:5px; font-size:13px; ">'.__('FedEx Special Services', 'ph-fedex-woocommerce-shipping').'</th>';

				echo '<tr><td>'. __('Collect On Delivery', 'ph-fedex-woocommerce-shipping') . '</td>';
				echo '<td><label for="wf_fedex_cod"><input type="checkbox" style="" id="wf_fedex_cod" '.$cod_checked.' name="wf_fedex_cod"></label></td></tr>';

				echo '<tr><td>'. __('Saturday Delivery', 'ph-fedex-woocommerce-shipping');
				echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'This option will enable Saturday Delivery Shipping Services.', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></td>';
				echo '<td><label for="wf_fedex_sat_delivery"><input type="checkbox" style="" id="wf_fedex_sat_delivery" '.$sat_checked.' name="wf_fedex_sat_delivery"></label></td></tr>';

				if ( $this->origin_country != $shipping_country ) {

					$etd_checked 	= $this->etd_label ? 'checked': '';
					echo '<tr><td>'. __('ETD - Electronic Trade Documents', 'ph-fedex-woocommerce-shipping');
					echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'On enabling this option the shipment details will be sent electronically and ETD will be printed in the Shipping Label', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></td>';
					echo '<td><label for="ph_fedex_etd"><input type="checkbox" '.$etd_checked.' id="ph_fedex_etd" name="ph_fedex_etd"></label></td></tr>';
				
					
					// Terms of Sale
					$termsOfSale = [
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
					];
					echo '<tr><td>'. __('Terms of Sale ', 'ph-fedex-woocommerce-shipping');
					echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'Terms of Sale.', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></td>';
					echo '<td><select id="ph_fedex_terms_of_sale" class="ph_fedex_terms_of_sale wc-enhanced-select" style= "width:50%">';

					$selectedTos = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_ph_fedex_terms_of_sale');
					$selectedTos = !empty( $selectedTos ) ? $selectedTos : $this->csb_termsofsale;

					foreach ($termsOfSale as $key => $value) {

						if($key == $selectedTos){
							echo "<option value='".$key."' selected>".$value."</option>";
						} else {
							echo "<option value='".$key."'>".$value."</option>";
						}
					}
					echo '</select></td></tr>';

				}

				//PDS-179
				$signature_meta 		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_signature_option_meta', false);
                $this->signature_temp 	= isset($this->signature_temp) && !empty($this->signature_temp) ? $this->signature_temp : 0;
				$this->signature_temp 	= isset($signature_meta[0]) ? $signature_meta[0] : $this->signature_temp;
				$this->signature 		= isset($this->prioritizedSignatureOption[$this->signature_temp]) && !empty($this->prioritizedSignatureOption[$this->signature_temp]) ? $this->prioritizedSignatureOption[$this->signature_temp] : '';
				$signature_options 		= array(
					''        				=> __( 'Select Anyone', 'ph-fedex-woocommerce-shipping' ),
					'ADULT'	   				=> __( 'Adult', 'ph-fedex-woocommerce-shipping' ),
					'DIRECT'	  			=> __( 'Direct', 'ph-fedex-woocommerce-shipping' ),
					'INDIRECT'	  			=> __( 'Indirect', 'ph-fedex-woocommerce-shipping' ),
					'SERVICE_DEFAULT'	  	=> __( 'Service Default', 'ph-fedex-woocommerce-shipping' ),
					'NO_SIGNATURE_REQUIRED' => __( 'No Signature Required', 'ph-fedex-woocommerce-shipping' ),
					
				);

				_e('<tr><td> Delivery Signature ', 'ph-fedex-woocommerce-shipping');
				echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'FedEx Freight services are not eligible for Signature Service. Hence, Signature option will be ignored for Freight Shipments.', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16" /></td>';

				echo '<td><select id="ph_fedex_signature_option" class="ph_fedex_signature_option" style= "width:50%">';

				foreach ($signature_options as $key => $value) {

					if($key == $this->signature){
						echo "<option value='".$key."' selected>".$value."</option>";
					} else {
						echo "<option value='".$key."'>".$value."</option>";
					}
				}
				echo '</select></td></tr>';
		
				if($order_currency != $b13_post_currency && !empty($woocommerce_currency_conversion_rate)){

					$b13_currency_rate = isset($woocommerce_currency_conversion_rate[$b13_post_currency]) ? $woocommerce_currency_conversion_rate[$b13_post_currency] : 0;

					$order_currency_rate = $woocommerce_currency_conversion_rate[$order_currency];

					$conversion_rate = $b13_currency_rate / $order_currency_rate;
					$items_cost *= $conversion_rate;
				}
				
				if( $this->origin_country === 'CA' && ( $items_cost >= 2000 &&	 ($shipping_country != 'US' && $shipping_country != 'CA' &&$shipping_country != 'PR' && $shipping_country != 'VI'))) {
					$export_declaration_required = 1;
					$export_compliance = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_wf_fedex_export_compliance');
				?>
						<tr><td><?php echo __( 'B13A Authentication Code Number', 'ph-fedex-woocommerce-shipping' ); echo '<img class="help_tip" style="float:none;" data-tip="'.__( 'B13A Export compliance for shippment from Canada', 'ph-fedex-woocommerce-shipping' ).'" src="'.WC()->plugin_url().'/assets/images/help.png" height="16" width="16"/>';?> </td>
						<td><input type="text" name="wf_fedex_compliance" value="<?php echo $export_compliance;?>" id="wf_fedex_compliance" style="width:50%"></li></td></tr>
				<?php
				}
				?><?php

				$ph_booking_conf_num 		= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_fedex_booking_confirmation_num_meta');
				
				echo '<tr><td>'. __('Booking Confirmation Number', 'ph-fedex-woocommerce-shipping') . '</td>';
				echo '<td><label for="ph_fedex_booking_conf_num"><input type="text" style="width:50%" id="ph_fedex_booking_conf_num" value = "'.$ph_booking_conf_num.'" name="ph_fedex_booking_conf_num"></label></td></tr>';

				////Home delivery premium
				if ( $this->home_delivery_premium && $this->home_delivery_premium_type === 'DATE_CERTAIN') {

					$date = date("Y-m-d", strtotime("+2 days"));

					echo '<tr><td>'. __('Home Delivery Premium - Date Certain', 'ph-fedex-woocommerce-shipping').'</td>';
					echo "<td><input type='date' min='".$date."' id='ph_fedex_home_delivery_premium_date' name='ph_fedex_home_delivery_premium_date' class='ph_fedex_home_delivery_premium_date' size='16' style='width:50%' value='' /></td></tr>";
				}
				echo '</table></li>';
				?>
				
				<li>
					<a style="margin: 4px; margin-top: 10px; margin-bottom: 10px" class="button button-primary tips onclickdisable ph-disable-on-click fedex_create_shipment" href="<?php echo $generate_url; ?>" data-tip="<?php _e( $this->ph_button_names['create_shipment_btn_tip'], 'ph-fedex-woocommerce-shipping' ); ?>"><?php _e($this->ph_button_names['create_shipment_btn'], 'ph-fedex-woocommerce-shipping' ); ?></a><hr style="border-color:#0074a2">
				</li>
				<?php
			}
			?>
			
			<script type="text/javascript">
				jQuery("a.fedex_generate_packages").on("click", function() {
					location.href = this.href;
				});
				
				// To get rates on order page
				jQuery("a.wf_fedex_generate_packages_rates").one("click", function() {

					jQuery(this).click(function () { return false; });

					var manual_weight_arr 		= jQuery("input[id='fedex_manual_weight']").map(function(){return jQuery(this).val();}).get();
					var manual_height_arr 		= jQuery("input[id='fedex_manual_height']").map(function(){return jQuery(this).val();}).get();
					var manual_width_arr 		= jQuery("input[id='fedex_manual_width']").map(function(){return jQuery(this).val();}).get();
					var manual_length_arr 		= jQuery("input[id='fedex_manual_length']").map(function(){return jQuery(this).val();}).get();
					var manual_insurance_arr 	= jQuery("input[id='fedex_manual_insurance']").map(function(){return jQuery(this).val();}).get();
					var manual_packages_no_arr 	= jQuery("[id='fedex_packages_no']").map(function(){return jQuery(this).val();}).get();
					var manual_packages_no 		= JSON.stringify(manual_packages_no_arr);
					var manual_signature_option	= jQuery('#ph_fedex_signature_option').map(function(){return jQuery(this).val();}).get();
					var order_id                = <?php $order_id = isset($order_id) && !empty($order_id) ? $order_id : $post->ID; echo $order_id  ?>;

					let package_key_arr = [];

					jQuery('.wf_fedex_package_line_remove').each(function () {
						package_key_arr.push(this.id);
					});

					let package_key = JSON.stringify(package_key_arr);

					let sat_delivery = 'no';

					if ( jQuery('#wf_fedex_sat_delivery').is(':checked') ) {

						sat_delivery = 'yes';
					}

					location.href = this.href + '&weight=' + manual_weight_arr 
					+ '&length=' + manual_length_arr
					+ '&width=' + manual_width_arr
					+ '&height=' + manual_height_arr
					+ '&insurance=' + manual_insurance_arr
					+ '&package_key=' + package_key
					+ '&signature_option=' + manual_signature_option
					+ '&num_of_packages=' + manual_packages_no
					+ '&oid=' + order_id
					+ '&sat_delivery=' + sat_delivery;

					return false;
				});

				jQuery(document).ready( function() {
					jQuery(document).on("change", "#wf_fedex_service_choosing_radio", function(){
						if (jQuery("#wf_fedex_service_choosing_radio:checked").val() == 'wf_fedex_individual_service') {
						jQuery(".fedex_manual_service").prop("disabled", false);
					} else {
						jQuery(".fedex_manual_service").val(jQuery("#wf_fedex_service_choosing_radio:checked").val()).change();
						jQuery(".fedex_manual_service").prop("disabled", true);  
					}
				});
				});
			</script>
			<?php
		}
		echo '</ul>';?>
		<script>
		jQuery("a.fedex_create_return_shipment").one("click", function(e) {
			e.preventDefault();
			service = jQuery(this).prev("select").val();
			var manual_service 		=	'[' + JSON.stringify( service ) + ']';
			location.href = this.href + '&service=' +  manual_service;
		});

		jQuery("a.fedex_create_shipment").on("click", function() {

			if ( jQuery(this).hasClass("ph-empty-BCN") ) {

				jQuery('.fedex_create_shipment').attr('disabled', 'disabled');

				jQuery('.fedex_create_shipment').before('<p class="ph-empty-BCN-erroe-message" style="color:red"><strong>Note: </strong>FedEx Booking Confirmation Number is empty. Please enter the correct number and try again.<br>This is mandatory for FedEx International Economy Freight & International Priority Freight.</p>');
				return false;
			}

			jQuery(".error_home_delivery_date").remove();

			if ( jQuery('#ph_fedex_home_delivery_premium_date').is(':visible')){

				var home_delivery_date = jQuery('#ph_fedex_home_delivery_premium_date').val();

				if ( home_delivery_date === "" ) {

					var error_message = '<p class="error_home_delivery_date" style="color:red"><strong>Note: </strong>Please select the date while using Home Delivery Premium - Date Certain option and try again.</p>';
					jQuery('.fedex_create_shipment').before(error_message);
					return false;
				}
			}
			
			// Preventing Multiple Clicks 
			jQuery('.fedex_create_shipment').attr('disabled', 'disabled');
			
			jQuery(this).click(function () { return false; });
			    var manual_packages_no_arr 	= 	jQuery("[id='fedex_packages_no']").map(function(){return jQuery(this).val();}).get();
				var manual_packages_no 		=	JSON.stringify(manual_packages_no_arr);

				var manual_weight_arr 	= 	jQuery("input[id='fedex_manual_weight']").map(function(){return jQuery(this).val();}).get();
				var manual_weight 		=	JSON.stringify(manual_weight_arr);
				
				var manual_height_arr 	= 	jQuery("input[id='fedex_manual_height']").map(function(){return jQuery(this).val();}).get();
				var manual_height 		=	JSON.stringify(manual_height_arr);
				
				var manual_width_arr 	= 	jQuery("input[id='fedex_manual_width']").map(function(){return jQuery(this).val();}).get();
				var manual_width 		=	JSON.stringify(manual_width_arr);
				
				var manual_length_arr 	= 	jQuery("input[id='fedex_manual_length']").map(function(){return jQuery(this).val();}).get();
				var manual_length 		=	JSON.stringify(manual_length_arr);
				
				var manual_insurance_arr 	= 	jQuery("input[id='fedex_manual_insurance']").map(function(){return jQuery(this).val();}).get();
				var manual_insurance 		=	JSON.stringify(manual_insurance_arr);

				var export_compliance_arr = jQuery("input[id='wf_fedex_compliance']").map(function(){return jQuery(this).val();}).get();
				var export_compliance  = JSON.stringify(export_compliance_arr);

				var manual_service_arr		= [];
				var manual_single_service_arr	= [];
				jQuery('.fedex_manual_service').each(function(){
					manual_service_arr.push( jQuery(this).val() );
					manual_single_service_arr.push(jQuery("input[id='wf_fedex_service_choosing_radio']:checked").val());
				});
				var manual_service 		=	JSON.stringify(manual_service_arr);

				if( jQuery("input[id='wf_fedex_service_choosing_radio']:checked").val() != 'wf_fedex_individual_service' ){
					manual_service	= JSON.stringify(manual_single_service_arr);
				}

				let package_key_arr = [];
				jQuery('.wf_fedex_package_line_remove').each(function () {
					package_key_arr.push(this.id);
				});
				let package_key = JSON.stringify(package_key_arr);

				let sat_delivery = 'no';

				if ( jQuery('#wf_fedex_sat_delivery').is(':checked') ) {

					sat_delivery = 'yes';
				}

			   location.href = this.href + '&weight=' + manual_weight +
				'&length=' + manual_length
				+ '&width=' + manual_width
				+ '&height=' + manual_height
				+ '&num_of_packages=' + manual_packages_no
				+ '&cod=' + jQuery('#wf_fedex_cod').is(':checked')
				+ '&sat_delivery=' + sat_delivery
				+ '&signature_option=' + jQuery('#ph_fedex_signature_option').val()
				+ '&home_delivery_date=' + jQuery('#ph_fedex_home_delivery_premium_date').val()
				+ '&etd=' + jQuery('#ph_fedex_etd').is(':checked')
				+ '&insurance=' + manual_insurance
				+ '&service=' + manual_service
				+ '&package_key=' + package_key
				+ '&export_compliance=' + export_compliance
				+ '&tos=' + jQuery('#ph_fedex_terms_of_sale').val()
				+ '&bcn=' + jQuery('#ph_fedex_booking_conf_num').val();
			return false;			
		});
		</script>
		<?php
	}	
	public function get_dimension_from_package($package){
		$dimensions	=	array(
			'Length'	=>	'',
			'Width'		=>	'',
			'Height'	=>	'',
			'Weight'	=>	'',
		);
		
		if(!is_array($package)){ // Package is not valid
			return $dimensions;
		}
		if(isset($package['Dimensions'])){
			$dimensions['Length']	=	$package['Dimensions']['Length'];
			$dimensions['Width']	=	$package['Dimensions']['Width'];
			$dimensions['Height']	=	$package['Dimensions']['Height'];
			$dimensions['dim_unit']	=	$package['Dimensions']['Units'];
		}
		
		$dimensions['Weight']	=	$package['Weight']['Value'];
		$dimensions['weight_unit']	=	$package['Weight']['Units'];
		return $dimensions;
	}

	/**
	 * To calculate the shipping cost on order page.
	 */
	public function wf_fedex_generate_packages_rates() {

		if( ! $this->ph_user_permission() ) {
			echo "You don't have admin privileges to view this page.";
			exit;
		}
		
		$order_id				= base64_decode($_GET['wf_fedex_generate_packages_rates']);
		$length_arr				= explode(',',$_GET['length']);
		$width_arr				= explode(',',$_GET['width']);
		$height_arr				= explode(',',$_GET['height']);
		$weight_arr				= explode(',',$_GET['weight']);
		$insurance_arr			= explode(',',$_GET['insurance']);
		$get_stored_packages	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, '_wf_fedex_stored_packages');

		// Check if new registration method
		if(Ph_Fedex_Woocommerce_Shipping_Common::phIsNewRegistration())
		{
			// Check for active plugin license
			if(!Ph_Fedex_Woocommerce_Shipping_Common::phHasActiveLicense())
			{
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('--------------------------- FEDEX RATES ---------------------------', $this->debug);
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('Please use a valid plugin license to continue using WooCommerce FedEx Shipping Plugin with Print Label', $this->debug);

				wf_admin_notice::add_notice( 'Please use a valid plugin license to continue using WooCommerce FedEx Shipping Plugin with Print Label','error');
				wp_redirect( admin_url( '/post.php?post='.$order_id.'&action=edit#PH_Fedex_Metabox') );
				exit;
			}
		}
		
		if ( ! class_exists( 'wf_fedex_woocommerce_shipping_method' ) ) {
			include_once 'class-wf-fedex-woocommerce-shipping.php';
		}

		$shipping_obj		= new wf_fedex_woocommerce_shipping_method();
		$order				= wc_get_order($order_id);
		$shipping_address	= $order->get_address('shipping');

		$order_subtotal = is_object($order) ? $order->get_subtotal() : 0;

		if ( $this->min_order_amount_for_insurance > $order_subtotal ) {
			$insurance_arr	= [];

			if ( $this->settings['insure_contents'] == 'yes' ) {

				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('Insurance eligibility criteria not met - Order subtotal must be greater or equal to Min Order Amount', $this->debug);
			}
		}

		$address_package	= array(
			'destination'	=> array(
				'address'	=>	$shipping_address['address_1'],
				'address_2'	=>	$shipping_address['address_2'],
				'country'	=>	$shipping_address['country'],
				'state'		=>	$shipping_address['state'],
				'postcode'	=>	$shipping_address['postcode'],
				'city'		=>	$shipping_address['city'],

			),
		);

		// Adding destination country code to shipping_obj to use it when clicking Calculate cost
		$shipping_obj->destinationCountryCode = $shipping_address['country'];

		// See if address is residential
		if( $this->production )
		{
			$shipping_obj->residential_address_validation( $address_package );

			if ( $shipping_obj->residential == true ) {
				wf_admin_notice::add_notice( sprintf( __( 'Residential Address', 'ph-fedex-woocommerce-shipping' ) ), 'notice' );
			}
		}

		$fedex_requests 	= array();
		$satdelivery_rates 	= array();
		$packages 			= $get_stored_packages;

		// To recreate packages for package removed from order page, not required in case of automatic label generation
		if ( isset($_GET["package_key"]) ) {

			$group_index_package_index	= json_decode(stripslashes(html_entity_decode($_GET["package_key"])));
			$temp_insurance_arr			= $insurance_arr;
			$new_packages 				= [];

			foreach( $group_index_package_index as $key => $packages_indexes ) {

				// Empty for extra added packages manually
				if( ! empty($packages_indexes) ) {

					list( $main_arr_index, $inner_arr_index ) = explode( '_', $packages_indexes );

					if( ! empty($packages[$main_arr_index][$inner_arr_index]) ) {

						$new_packages[$main_arr_index][$inner_arr_index] 							= $packages[$main_arr_index][$inner_arr_index];
						if (!empty($insurance_arr[$key])) {
							$new_packages[$main_arr_index][$inner_arr_index]['InsuredValue']['Amount'] 	= round( array_shift($temp_insurance_arr), 2);
						}else{
							$new_packages[$main_arr_index][$inner_arr_index]['InsuredValue']['Amount'] 	= 0;
							array_shift($temp_insurance_arr);
						}
					}
				}
			}

			if( isset($new_packages) ) {
				$packages = $new_packages;
			}
		}
		// End of creation of the package depending on removed package

		$no_of_package_entered  = count($weight_arr);
		$no_of_packages 		= 0;

		foreach ($packages as $key => $package) {
			$no_of_packages += count($package);
		}

		// Populate extra packages, if entered manual values
		if ($no_of_package_entered > $no_of_packages) {

			// Get first package to clone default data
			$package_clone 			=   isset($packages[0]) && is_array($packages[0]) ? current($packages[0]) : '';
			$new_manual_package 	=	array();
			$new_manual_package[0] 	=	[];

			for($i=$no_of_packages; $i<$no_of_package_entered; $i++) {

				if( empty($package_clone) ) {

					$manual_package = array(
						'GroupNumber'			=> $i+1,
						'GroupPackageCount'		=> 1,
						'Weight'				=> array(

							'Value'		=> '',
							'Units'		=> $shipping_obj->labelapi_weight_unit,
						),

						'Dimensions'			=> array(),

						'InsuredValue'			=> array(
							'Amount'	=> 0,
							'Currency'	=> $shipping_obj->wf_get_fedex_currency()
						),

						'packed_products'		=> array(),
					);

					$new_manual_package[0][$i] = $manual_package;

				} else {

					$package_clone['GroupNumber'] 		= $i+1;
					$package_clone['packed_products'] 	= array();

					if( isset($package_clone['package_id']) ) {

						unset($package_clone['package_id']);
					}

					$new_manual_package[0][$i] = $package_clone;
				}
			}
			
			if( isset($packages[0]) && is_array($packages[0]) ) {

				$packages[0] = array_merge($packages[0], $new_manual_package[0]);
			} else {
				$packages[0] = $new_manual_package[0];
			}
		}

		foreach ($packages as $package) {

			if (!empty($package) && is_array($package)) {

				$package 	= array_values($package);

				foreach ($package as $key => $value) {

					if ( ! empty($weight_arr[$key] ) ) {

						$package[$key]['Weight']['Value']	= $weight_arr[$key];
						$package[$key]['Weight']['Units']	= $shipping_obj->labelapi_weight_unit;

					} else {

						wf_admin_notice::add_notice( sprintf( __( 'Fedex Rate Request Failed - Weight is missing in the pacakge. Aborting.', 'ph-fedex-woocommerce-shipping' ) ), 'error' );

						// Redirect to same order page
						wp_redirect( admin_url( '/post.php?post='.$order_id.'&action=edit') );
						//To stay on same order page
						exit;
					}

					if ( ! empty($length_arr[$key]) && ! empty($width_arr[$key]) && ! empty($height_arr[$key]) ) {

						$package[$key]['Dimensions']['Length']	= $length_arr[$key] ;
						$package[$key]['Dimensions']['Width']	= $width_arr[$key] ;
						$package[$key]['Dimensions']['Height']	= $height_arr[$key] ;
						$package[$key]['Dimensions']['Units']	= $shipping_obj->labelapi_dimension_unit;

					} else {
						unset($package[$key]['Dimensions']);
					}

					if ( isset($insurance_arr[$key]) && !empty($insurance_arr[$key]) ) {

						$package[$key]['InsuredValue']['Amount']	= $insurance_arr[$key];
					}
				}
			}

			$package_data[] = $package;
			$fedex_reqs = $shipping_obj->get_fedex_requests( $package, $address_package );

			if(is_array($fedex_reqs)){
				$fedex_requests	=	array_merge($fedex_requests,	$fedex_reqs);
				$shipping_obj->run_package_request($fedex_requests, 'normal_rates');
			}

			// SmartPost does not support Multi-piece shipments.
			$is_single_package = false;

			if ( count($package) == 1 && current($package)['GroupPackageCount'] == 1 ) {
				
				$is_single_package = true;
			}

			// SmartPost Request
			if ( $is_single_package && $this->custom_services['SMART_POST']['enabled'] && $this->settings['smartpost_hub'] && $address_package['destination']['country'] == 'US' ) {
				$smart_post_request = $shipping_obj->get_fedex_requests( $package, $address_package, 'smartpost');
				$shipping_obj->run_package_request($smart_post_request, 'smartpost');
			}

			// Freight Request
			if( $this->freight_enabled ) {

				$freight_request 		= $shipping_obj->get_fedex_requests( $package, $address_package, 'freight' );
				$shipping_obj->run_package_request($freight_request, 'freight');
			}

			if ( $this->saturday_delivery ) {

				$satdelivery_rates 	= $shipping_obj->get_fedex_requests( $package, $address_package, 'saturday_delivery');
			}
		}
		
		// To save the rate request response
		$_GET['oid'] = $order_id;

		if( $get_stored_packages != $package_data) {

			// Update the packages in database
			PH_WC_Fedex_Storage_Handler::ph_add_and_save_meta_data($order_id, '_wf_fedex_stored_packages', $package_data, true);
		}
		
		if ( $this->saturday_delivery && $shipping_obj->saturday_delivery && !empty($satdelivery_rates) ) {

			$shipping_obj->satday_rates = true;

			$shipping_obj->run_package_request( $satdelivery_rates, 'saturday_delivery' );
		}
		
		// Redirect to same order page
		wp_redirect( admin_url( '/post.php?post='.$order_id.'&action=edit#PH_Fedex_Metabox') );
		//To stay on same order page
		exit;
	}

	/**
     * Display fedex action button on orders table
     *
     * @access public
     * @return string
     */
	function fedex_action_column($order)
	{
		$order_id		= $order->get_id();
		$shipmentIds	= $this->ph_get_unique_shipment_ids($order_id);

		if (!empty($shipmentIds) && is_admin()) {

			foreach ($shipmentIds as $shipmentId) {

				$shipping_label = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipmentId);
				$shipping_label = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shippingLabel_' . $shipmentId);
				
				if (!empty($shipping_label)) {
					$download_url = admin_url('/post.php?wf_fedex_viewlabel=' . base64_encode($shipmentId . '|' . $order_id));
					printf('<a class="button tips" href="' . $download_url . '" target="_blank" data-tip="' . __('Fedex Print Label', 'ph-fedex-woocommerce-shipping') . '"><img src="' . plugin_dir_url(__DIR__) . 'resources/images/fedex_label.png" style="width:24px;margin:2px;margin-left:0px;"/></a>');
				}
				
				$additional_labels = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_fedex_additional_label_' . $shipmentId);
				
				if (!empty($additional_labels) && is_array($additional_labels)) {
					foreach ($additional_labels as $additional_key => $additional_label) {
						$download_add_label_url = admin_url('/post.php?wf_fedex_additional_label=' . base64_encode($shipmentId . '|' . $order_id . '|' . $additional_key));
						printf('<a class="button tips" href="' . $download_add_label_url . '" target="_blank" data-tip="' . __('FedEx Additional Label', 'ph-fedex-woocommerce-shipping') . '"><img src="' . plugin_dir_url(__DIR__) . 'resources/images/fedex_additional.png" style="width:24px;margin:2px;margin-left:0px;"/></a>');
					}
				}

				//Fedex tracking icon
				$shipment_tracking_url = "https://www.fedex.com/fedextrack/no-results-found?trknbr=" . $shipmentId;
				printf('<a class="button tips" href="' . $shipment_tracking_url . '" target="_blank" data-tip="' . __('FedEx Tracking-' . $shipmentId, 'ph-fedex-woocommerce-shipping') . '"><img src="' . plugin_dir_url(__DIR__) . 'resources/images/fedex_tracking.png" style="width:24px;margin:2px;margin-left:0px;"/></a>');
			}
		}
	}

	// Automatic Package Generation
	public function ph_fedex_auto_generate_packages($order_id, $fedex_settings, $minute = '')
	{
		// Check current time (minute) in Thank You Page for Automatic Package generation
		if (!$this->ph_user_permission($minute)) {
			return;
		}

		$order_id 	= base64_decode($order_id);
		$order 		= wc_get_order($order_id);

		if (!$order instanceof WC_Order) {
			return;
		}

		$this->xa_generate_package($order);
	}

	// Automatic Label Generation
	public function ph_fedex_auto_create_shipment( $order_id, $fedex_settings, $weight_arr, $length_arr, $width_arr, $height_arr, $service_arr, $minute = '')
	{
		// Check current time (minute) in Thank You Page for Automatic Label generation
		if (!$this->ph_user_permission( $minute )) {
			return;
		} 			

		$order 	= wc_get_order($order_id);
		$debug 	= ( $bool = $fedex_settings[ 'debug' ] ) && $bool == 'yes' ? true : false;

		if(isset($fedex_settings['dimension_weight_unit']) && $fedex_settings['dimension_weight_unit'] == 'LBS_IN'){
			
			$labelapi_dimension_unit 	=	'IN';
			$labelapi_weight_unit 		=	'LB';			
		}else{
			
			$labelapi_dimension_unit 	=	'CM';
			$labelapi_weight_unit		=	'KG';		
		}

		if( !$order )
		{
			return;
		}
		
		$shipment_ids = PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipmentId');

		if( empty($shipment_ids) ) {

			$i 					= 0;
			$stored_packages    = PH_WC_Fedex_Storage_Handler::ph_get_meta_data( $order_id, '_wf_fedex_stored_packages');
			
			foreach($stored_packages as $package_key => $stored_package){

				foreach($stored_package as $key => $package){

					if( !empty($length_arr[$i]) || !empty($width_arr[$i]) || !empty($height_arr[$i]) ){

						if(isset($length_arr[$i])){
							$stored_packages[$package_key][$key]['Dimensions']['Length'] =  $length_arr[$i];
						}

						if(isset($width_arr[$i])){
							$stored_packages[$package_key][$key]['Dimensions']['Width']  =  $width_arr[$i];
						}

						if(isset($height_arr[$i])){
							$stored_packages[$package_key][$key]['Dimensions']['Height'] = $height_arr[$i];
						}
						$stored_packages[$package_key][$key]['Dimensions']['Units']	= $labelapi_dimension_unit;
					}

					if( !empty($service_arr[$i]) ){
						$stored_packages[$package_key][$key]['service']  			= $service_arr[$i];
					}

					if(isset($weight_arr[$i])){
						$weight =   $weight_arr[$i];
						$stored_packages[$package_key][$key]['Weight']['Value']   =   $weight;
						$stored_packages[$package_key][$key]['Weight']['Units']   =   $labelapi_weight_unit;
					}
					$i++;
				}
			}

			PH_WC_Fedex_Storage_Handler::ph_update_and_save_meta_data($order_id, '_wf_fedex_stored_packages', $stored_packages);

			$this->wf_create_shipment( $order, $service_arr );

		}else{

			if( $debug ) {
				_e( 'Fedex label generation Suspended. Label has been already generated.', 'ph-fedex-woocommerce-shipping' );
			}
			if( class_exists('WC_Admin_Meta_Boxes') ) {
				WC_Admin_Meta_Boxes::add_error( 'Fedex label generation Suspended. Label has been already generated.', 'ph-fedex-woocommerce-shipping' );
			}
		}

	}
}
new wf_fedex_woocommerce_shipping_admin();
?>