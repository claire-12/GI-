<?php
/**
 * Common FedEx Class.
 */
if( ! defined('ABSPATH') )	exit();

if( ! class_exists('Ph_Fedex_Woocommerce_Shipping_Common') ) {
	class Ph_Fedex_Woocommerce_Shipping_Common {

		/**
		 * Active plugins.
		 */
		private static $active_plugins;

		/**
		 * FedEx country code mapper
		 */
		public static $ph_fedex_country_code_mapper = array(
			'JE'	=> 'GB'
		);
		
		/**
		 * Active plugins.
		 * @return array
		 */
		public static function get_active_plugins() {
			if( empty(self::$active_plugins) ) {
				self::$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) );
				// Multisite case
				if ( is_multisite() ) {
					self::$active_plugins = array_merge( self::$active_plugins, array_keys( get_site_option( 'active_sitewide_plugins', array() ) ) );
				}
			}
			return self::$active_plugins;
		}
		
		/**
		 * Get the Converted Weight.
		 * @param mixed $to_unit To Unit.
		 * @param string $from_unit (Optional) From unit if noting is passed then store dimension unit will be taken.
		 * @return float
		 */
		public static function ph_get_converted_weight( $weight, $to_unit, $from_unit=''){
			$weight = (float) $weight;
			$converted_weight = wc_get_weight( $weight, $to_unit, $from_unit );
			return apply_filters( 'ph_fedex_get_converted_weight',$converted_weight, $weight, $to_unit, $from_unit );
		}

		/**
		 * Get the Converted Dimension.
		 * @param mixed $to_unit To Unit.
		 * @param string $from_unit (Optional) From unit if noting is passed then store weight unit will be taken.
		 * @return float
		 */
		public static function ph_get_converted_dimension( $dimension, $to_unit, $from_unit='' ){
			$dimension = (float) $dimension;
			$converted_dimension = wc_get_dimension( $dimension, $to_unit, $from_unit );
			return apply_filters( 'ph_fedex_get_converted_dimension', $converted_dimension, $dimension, $to_unit, $from_unit );
		}

		/**
		 * Add admin diagnostic report
		 *
		 * @param mixed $data
		 */
		public static function addAdminDiagnosticReport( $data, $debug = false ) {
	
			if( function_exists("wc_get_logger") ) {
	
				$log = wc_get_logger();
				$log->debug( ($data).PHP_EOL.PHP_EOL, array('source' => 'PluginHive-FedEx-Error-Debug-Log'));
			}
		}

		/**
		 * Convert Array to Object
		 *
		 * @param array $arrayData
		 * @return object
		 */
		public static function phConvertArrayToObject( $arrayData ) {
			
			// Array to JSON
			$encodedData = json_encode( $arrayData );

			// Sometimes Response will contain Invalid Characters, failing to encode the Data then Ignore and try again
			if( empty($encodedData) ) {
				
				$encodedData = json_encode( $arrayData, JSON_INVALID_UTF8_IGNORE );
			}

			// JSON to Object
			$decodedData = json_decode($encodedData, false);

			return $decodedData;
		}

		/**
		 * Check for new registration method
		 */
		public static function phIsNewRegistration()
		{
			$isRegisteredUser = get_option('ph_fedex_registered_user', false);

			return $isRegisteredUser;

		}

		/**
		 * Check if current user has active license
		 */
		public static function phHasActiveLicense()
		{
			$phLicenseActivationStatus = get_option('wc_am_client_fedex_woocommerce_shipping_activated');
			$phLicenseActivationStatus = $phLicenseActivationStatus == 'Activated' ? true : false; 
			return $phLicenseActivationStatus;
		}

		/**
		 * Prepare parameters for proxy call
		 *
		 * @param array $apiAccessDetails
		 * @param array $request
		 * @param string $type
		 */
		public static function phGetParamsForProxyCall($apiAccessDetails, $request = [], $type = '')
		{
			$proxyParams 				= [];
			$fedexSettings 				= get_option('woocommerce_' . WF_Fedex_ID . '_settings', []);
			$phFedexClientLicenseHash 	= isset($fedexSettings['client_license_hash']) && !empty($fedexSettings['client_license_hash']) ? $fedexSettings['client_license_hash'] : null;

			$apiHeaders = [
				'Authorization: Bearer ' . $apiAccessDetails['token'],
				'Content-Type: application/vnd.ph.carrier.fedex.v1+xml',
				'x-license-key-id: ' . $phFedexClientLicenseHash,
				'env: live',
				'Accept: text/xml'
			];

			$headers = implode("\r\n", $apiHeaders);

			// Custom headers for proxy using SOAP client stream context
			$options = [
				'stream_context' => stream_context_create([
					'http' => [
						'header' => $headers
					],
				]),
				'trace' => true
			];

			// Adding default Parent credentials
			$request['WebAuthenticationDetail']['ParentCredential'] = [
    			'Key'	=> 'PHIVEPK007PHIVE',
    			'Password' => 'PHIVEPP007PHIVE'
    		];

			$endpointKey = '';

			switch($type)
			{
				case 'normal_rates' :
					$endpointKey = 'rates';
					break;
				case 'smartpost' :
					$endpointKey = 'rates/smart-post/ground-economy';
					break;
				case 'freight' :
					$endpointKey = 'rates/freight';
					break;
				case 'saturday_delivery' :
					$endpointKey = 'rates/saturday-delivery';
					break;
				case 'ship' :
					$endpointKey = 'shipment/confirmed';
					break;
				case 'cancel_ship' :
					$endpointKey = 'shipment/cancelled';
					break;
				case 'pickup' :
					$endpointKey = 'shipment/pickup/requested';
					break;
				case 'cancel_pickup' :
					$endpointKey = 'shipment/pickup/cancelled';
					break;
				case 'return_ship' : 
					$endpointKey = 'shipment/returns/confirmed';
					break;
				case 'tracking' :
					$endpointKey = 'shipment/tracking';
					break;
				case 'hal' : 
					$endpointKey = 'access-points';
					break;
				case 'image_upload' :
					$endpointKey = 'shipment/documents-customisation/images';
					break;
				case 'validate_address' :
					$endpointKey = 'validated-address';
					break;
			}

			$proxyParams = [
				'options'	=> $options,
				'endpoint'	=> PH_FEDEX_PROXY_API_BASE_URL . $apiAccessDetails['internalEndpoints'][$endpointKey]['href'] ,
				'request'	=> $request,
				'headers'	=> $apiHeaders
			];

			return $proxyParams;
		}
	}
}