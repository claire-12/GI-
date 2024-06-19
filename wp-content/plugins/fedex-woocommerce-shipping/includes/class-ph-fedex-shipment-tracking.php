<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ph_fedex_api_shipment_tracking {

	/**
	 * WP Date Format
	 */
	public static $wp_date_format;
	/**
	 * WP Time Format
	 */
	public static $wp_time_format;
	/**
	 * WSDL Version
	 */
	public $fedex_tracking_wsdl_version;
	/**
	 * Soap Method
	 */
	public $soap_method;
	/**
	 * Settings
	 */
	public $settings;
	/**
	 * Production
	 */
	public $production;
	/**
	 * Debug
	 */
	public $debug;

	public function __construct() {

		$this->fedex_tracking_wsdl_version = 20;

		$this->ph_init();
		add_action( 'wp_ajax_ph_fedex_shipment_tracking', array($this,'ph_fedex_shipment_tracking'), 10, 1 );
	}


	public function ph_fedex_shipment_tracking(){

		$order_id		= isset($_POST['order_id']) ? $_POST['order_id'] : '';
		$order			= wc_get_order($order_id);
		$shipmentIds 	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'wf_woo_fedex_shipmentId', false);
		// Some Customers Site wont allow adding duplicate Meta Keys in DB, Adding new meta key with custom build Shipment Id Array
		$shipment_ids 	= PH_WC_Fedex_Storage_Handler::ph_get_meta_data($order_id, 'ph_woo_fedex_shipmentIds');

		$wc_meta_data_handler = new PH_WC_Fedex_Storage_Handler($order);

		if( is_array($shipmentIds) && is_array($shipment_ids) ){
			$shipmentIds  		= array_unique(array_merge($shipmentIds,$shipment_ids));
		}

		if ( isset($shipmentIds) && !empty($shipmentIds) ) {

			foreach ( $shipmentIds as $shipment_id ) {

				$wc_meta_data_handler->ph_update_meta_data($order_id, '_ph_fedex_tracking_status' . $shipment_id, '' );
				$wc_meta_data_handler->ph_update_meta_data($order_id, '_ph_fedex_tracking_status_error' . $shipment_id, '' );

				$response 	= $this->ph_get_response( $this->ph_get_fedex_tracking_request( $shipment_id ) );
				$result 	= $this->ph_get_result_text($response);

				if ( isset($result['success']) && !empty($result['success']) ) {

					$wc_meta_data_handler->ph_update_meta_data($order_id, '_ph_fedex_tracking_status' . $shipment_id, $result['success'] );

				} else if ( isset($result['error']) && !empty($result['error']) ) {

					$message = $result['error']['description'];
					$wc_meta_data_handler->ph_update_meta_data($order_id, '_ph_fedex_tracking_status_error' . $shipment_id, $message );

				} else{

					$message = 'Tracking Details Unavailable Please Try Later';
					$wc_meta_data_handler->ph_update_meta_data($order_id, '_ph_fedex_tracking_status_error' . $shipment_id, $message );
				}
			}

			$wc_meta_data_handler->ph_save_meta_data();
		}

		wp_die();
	}

	private function ph_get_result_text($response){

		$apiTracking = array();

		if( $response->HighestSeverity == 'ERROR' || $response->HighestSeverity == 'FAILURE' ) {

			$apiTracking['error']['error_number'] 	= $response->Notifications->Code;
			$apiTracking['error']['description'] 	= $response->Notifications->Message;

		} elseif( $response->HighestSeverity == 'SUCCESS' || $response->HighestSeverity == 'WARNING' ) {

			// If Tracking Number is Invalid
			if( ! empty($response->CompletedTrackDetails->TrackDetails->Notification->Code) && $response->CompletedTrackDetails->TrackDetails->Notification->Code == '9040' ) {

				$apiTracking['error']['error_number'] 	= $response->CompletedTrackDetails->TrackDetails->Notification->Code;
				$apiTracking['error']['description'] 	= $response->CompletedTrackDetails->TrackDetails->Notification->Message;

			} else {

				$apiTracking['success']['trackingnumber'] = $response->CompletedTrackDetails->TrackDetails->TrackingNumber;
				$apiTracking['success']['status'] = (string) $response->CompletedTrackDetails->TrackDetails->StatusDetail->Description;

				// Assign Status Detail Code
				$apiTracking['success']['livestatus'] 	= (string) $response->CompletedTrackDetails->TrackDetails->StatusDetail->Code;

				if( ! empty($response->CompletedTrackDetails->TrackDetails->Events) ) {

					if( empty(self::$wp_date_format) ) {
						self::$wp_date_format = get_option('date_format');
					}

					if( empty(self::$wp_time_format) ) {
						self::$wp_time_format = get_option('time_format');
					}

					// Object if only one status
					if( is_object($response->CompletedTrackDetails->TrackDetails->Events) ) {
						$response->CompletedTrackDetails->TrackDetails->Events = array($response->CompletedTrackDetails->TrackDetails->Events);
					}

					foreach( $response->CompletedTrackDetails->TrackDetails->Events as $activity) {

						$location 		 = null;
						$activity_status = $activity->EventDescription;
						$activityDate 	 = new DateTime($activity->Timestamp);
						
						// Location of current Activity
						if( ! empty($activity->Address->City) ) {
							$location = $activity->Address->City;
						}

						if( ! empty($activity->Address->StateOrProvinceCode) ) {
							$location = ! empty($location) ? $location.', '.$activity->Address->StateOrProvinceCode : $activity->Address->StateOrProvinceCode;
						}
						if( ! empty($activity->Address->CountryName) ) {
							$location = ! empty($location) ? $location.', '.$activity->Address->CountryName : $activity->Address->CountryName;
						}

						// Set in few cases only
						if( ! empty($activity->StatusExceptionDescription) ) {
							$activity_status .= '<br/>'.(string)$activity->StatusExceptionDescription;
						}

						$activity_history[] = array(
							'location'	=>	$location,
							'date'		=>	(string)$activityDate->format( self::$wp_date_format ),
							'time'		=>	(string)$activityDate->format( self::$wp_time_format ),
							'status'	=>	$activity_status
						);
					}

					$apiTracking['success']['shipment_tracking'] = $activity_history;

				} else {
					$apiTracking['success']['shipment_tracking'] = array();
				}
			}
		}

		return $apiTracking;
	}

	private function ph_get_response( $request ){

		$wsdl = plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/' . ( $this->production ? 'production' : 'test' ) . '/TrackService_v' . $this->fedex_tracking_wsdl_version. '.wsdl';

		// Check if new registration method
		if(Ph_Fedex_Woocommerce_Shipping_Common::phIsNewRegistration())
		{
			//Check for active license
			if(!Ph_Fedex_Woocommerce_Shipping_Common::phHasActiveLicense())
			{
				$this->admin_diagnostic_report( "------------------------------- Fedex Shipment Tracking -------------------------------" );
				$this->admin_diagnostic_report( "Please use a valid plugin license to continue using WooCommerce FedEx Shipping Plugin with Print Label" );
				return [];
			} else {

				if(!class_exists('class-ph-fedex-endpoint-dispatcher.php'))
				{
					include_once('class-ph-fedex-endpoint-dispatcher.php');
				}

				$apiAccessDetails = Ph_Fedex_Endpoint_Dispatcher::phGetApiAccessDetails();

				$isNewAndActiveRegistration = true;

				if(!$apiAccessDetails)
				{
					return false;
				}

				$proxyParams = Ph_Fedex_Woocommerce_Shipping_Common::phGetParamsForProxyCall($apiAccessDetails, $request, 'tracking');

				$client = $this->ph_create_soap_client( $wsdl, $proxyParams['options'] );


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

			$client = $this->ph_create_soap_client($wsdl);
		}
		
		// If Soap is available
		if( $this->soap_method == 'soap' ) { 
			try {
				$response = $client ->track($request);
			}
			catch( Exception $e ) {
			}
		}
		// If soap is not available
		else {
			try{

				$result 	= $client->call( 'track', array( 'TrackRequest' => $request ) );

				$response 	= Ph_Fedex_Woocommerce_Shipping_Common::phConvertArrayToObject($result);
			}
			catch( Exception $e ) {
			}
		}
		
		if ( WF_FEDEX_ADV_DEBUG_MODE == "on" ) { // Test mode is only for development purpose.
			
			$xml_request 	= $this->soap_method != 'nusoap' ? $client->__getLastRequest() : $client->request;
			$xml_response 	= $this->soap_method != 'nusoap' ? $client->__getLastResponse() : $client->response;

			$this->debug( 'FedEx REQUEST in XML Format: <a href="#" class="debug_reveal">Reveal</a><pre class="debug_info" style="background:#EEE;color:#000;border:1px solid #DDD;padding:5px;overflow: auto;">' . print_r( htmlspecialchars( $xml_request ), true ) . "</pre>\n" );
			$this->debug( 'FedEx RESPONSE in XML Format: <a href="#" class="debug_reveal">Reveal</a><pre class="debug_info" style="background:#EEE;color:#000;border:1px solid #DDD;padding:5px;overflow: auto;">' . print_r( htmlspecialchars( $xml_response ), true ) . "</pre>\n" );

			if( $this->debug ) {

				$this->admin_diagnostic_report( "------------------------------- Fedex Shipment Tracking Request -------------------------------" );
				$this->admin_diagnostic_report( htmlspecialchars( $xml_request ) );
				$this->admin_diagnostic_report( "------------------------------- Fedex Shipment Tracking Response -------------------------------" );
				$this->admin_diagnostic_report( htmlspecialchars( $xml_response ) );
			}
		}

		return $response;
	}

	private function ph_get_fedex_tracking_request( $shipment_id ){

		$settings			= apply_filters( 'xa_fedex_settings',get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null ) );
		$account_number 	= isset($settings['account_number']) && !empty($settings['account_number']) ? $settings['account_number'] : '';
		$meter_number 		= isset($settings['meter_number']) && !empty($settings['meter_number']) ? $settings['meter_number'] : '';
		$web_services_key 	= isset($settings['api_key']) && !empty($settings['api_key']) ? $settings['api_key'] : '';
		$password 			= isset($settings['api_pass']) && !empty($settings['api_pass']) ? $settings['api_pass'] : '';

		$request['WebAuthenticationDetail'] = array(
			// 'ParentCredential' => array(
			// 	'Key' 		=> 'qOmazU3qBwUtiKqC', 
			// 	'Password' 	=> 'HPS1yHV6ZBU7UfPvLdfHoeSKW'
			// ),
			'UserCredential' => array(
				'Key' 		=> $web_services_key, 
				'Password' 	=> $password
			)
		);
		$request['ClientDetail'] = array(
			'AccountNumber' => $account_number, 
			'MeterNumber' 	=> $meter_number
		);
		$request['TransactionDetail'] = array(
			'CustomerTransactionId' => '*** Track Request ***'
		);
		$request['Version'] = array(
			'ServiceId' 	=> 'trck',
			'Major' 		=> '20', 
			'Intermediate' 	=> '0', 
			'Minor' 		=> '0'
		);
		$request['SelectionDetails'] = array(
			'PackageIdentifier' => array(
				'Type' 	=> 'TRACKING_NUMBER_OR_DOORTAG',
				'Value'	=> $shipment_id
			)
		);
		// For Complete history
		$request['ProcessingOptions'] = array(
			'INCLUDE_DETAILED_SCANS'
		);

		return $request;
	}

	private function is_soap_available(){
		if( extension_loaded( 'soap' ) ){
			return true;
		}
		return false;
	}

	private function ph_init(){
		$this->soap_method = $this->is_soap_available() ? 'soap' : 'nusoap';
		if( $this->soap_method == 'nusoap' && !class_exists('nusoap_client') ){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/nusoap/lib/nusoap.php';
		}
		$this->settings 		= get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );
		$this->production 		= (isset($this->settings['production']) && ($this->settings['production'] == 'yes')) ? true : false;
		$this->debug           	= ( isset( $this->settings['debug'] ) && ( $this->settings['debug'] == 'yes' ) ) ? true : false;

	}

	private function ph_create_soap_client( $wsdl, $options = ['trace' => true] ){
		
		if( $this->soap_method=='nusoap' ){
			$soapclient = new nusoap_client( $wsdl, 'wsdl' );
		}else{
			$soapclient = new SoapClient( $wsdl, $options);
		}
		return $soapclient;
	}

	public function admin_diagnostic_report( $data ) {
	
		if( function_exists("wc_get_logger") && $this->debug ) {

			$log = wc_get_logger();
			$log->debug( ($data).PHP_EOL.PHP_EOL, array('source' => 'PluginHive-FedEx-Error-Debug-Log'));
		}
	}

	public function debug( $message, $type = 'notice' ) {

		if ( $this->debug && is_admin() ) {
			echo( $message);
		}
	}
}
new ph_fedex_api_shipment_tracking();