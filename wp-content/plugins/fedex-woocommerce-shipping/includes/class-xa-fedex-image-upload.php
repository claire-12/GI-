<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class xa_fedex_image_upload {

	/**
	 * Upload document wsdl version
	 */
	public $upload_document_wsdl_version;
	/**
	 * Settings
	 */
	public $settings;
	/**
	 * Production
	 */
	public $production;
	/**
	 * API Key
	 */
	public $api_key;
	/**
	 * API Pass
	 */
	public $api_pass;
	/**
	 * Account Number
	 */
	public $account_number;
	/**
	 * Meter Number
	 */
	public $meter_number;
	/**
	 * Debug
	 */
	public $debug;
	/**
	 * Soap Method
	 */
	public $soap_method;
	/**
	 * Encode Uploaded Document
	 */
	public $encode_uploaded_document;

	public function __construct() {
		$this->upload_document_wsdl_version = 19;

		$this->xa_init();
		add_action( 'wp_ajax_xa_fedex_upload_image', array($this,'xa_upload_image'), 10, 1 );
	}

	public function xa_upload_image(){
		$image_url	= isset($_POST['image']) ? $_POST['image'] : '';
		$image_id	= isset($_POST['image_id']) ? $_POST['image_id'] : '';
		if( empty($image_url) ){
			echo "Not able to get image, Please select a proper image";
			wp_die();
		}

		$response = $this->xa_get_response( $this->xa_get_fedex_image_upload_request($image_url, $image_id) );
		$result = $this->xa_get_result_text($response);
		wp_die( json_encode($result) );
	}

	private function xa_get_result_text($response){
		if ( isset($response->HighestSeverity) && $response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR') {
			$result = array(
				'success' => true,
				'message' => 'Image uploaded successfully',
			);
		}else{
			$result_text = isset($response->Notifications->Message) ? $response->Notifications->Message : 'An unexpected error occurred, Please try again later';
			$result = array(
				'success' => false,
				'message' => $result_text,
			);
		}
		return $result;
	}

	private function xa_get_response( $request ){
		$wsdl = plugin_dir_path( dirname( __FILE__ ) ) . 'fedex-wsdl/' . ( $this->production ? 'production' : 'test' ) . '/UploadDocumentService_v' . $this->upload_document_wsdl_version. '.wsdl';

		// Check if new registration method
		if(Ph_Fedex_Woocommerce_Shipping_Common::phIsNewRegistration())
		{
			//Check for active license
			if(!Ph_Fedex_Woocommerce_Shipping_Common::phHasActiveLicense())
			{
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport( "------------------------------- Fedex Shipment Tracking -------------------------------" );
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport( "Please use a valid plugin license to continue using WooCommerce FedEx Shipping Plugin with Print Label" );
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

				$proxyParams = Ph_Fedex_Woocommerce_Shipping_Common::phGetParamsForProxyCall($apiAccessDetails, $request, 'image_upload');

				$client = $this->wf_create_soap_client( $wsdl, $proxyParams['options'] );
				
				// Updating the SOAP location to Proxy server
				$client->__setLocation($proxyParams['endpoint']);

				// Get modified request
				$request = $proxyParams['request'];
			}
		} else {
		
			$client = $this->wf_create_soap_client($wsdl);
		}

		try{

			if ($this->soap_method == 'nusoap') {

				$response = $client->call('uploadImages', array('UploadImagesRequest' => $request));
				$response = Ph_Fedex_Woocommerce_Shipping_Common::phConvertArrayToObject($response);
			} else {

				$response = $client->uploadImages($request);
			}

			// Add admin diagnostic report
			if( $this->debug ) {

				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- FedEx Image Upload Request -------------------------------');
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport( $this->soap_method == 'nusoap' ? $client->request :  $client->__getLastRequest() );
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- FedEx Image Upload Response -------------------------------');
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport( $this->soap_method == 'nusoap' ? $client->response : $client->__getLastResponse() );
			}

		} catch ( exception $e ) {

			return 'Uexpected error on image upload';
		}
		return $response;
	}

	private function is_soap_available(){
		if( extension_loaded( 'soap' ) ){
			return true;
		}
		return false;
	}

	private function xa_init(){
		$this->settings = get_option( 'woocommerce_'.WF_Fedex_ID.'_settings', null );
		
		$this->production				= ( isset($this->settings[ 'production' ]) && ( $bool = $this->settings[ 'production' ] ) && $bool == 'yes' ) ? true : false;
		$this->api_key					= isset($this->settings[ 'api_key' ]) ? $this->settings[ 'api_key' ] : '';
		$this->api_pass					= isset($this->settings[ 'api_pass' ]) ? $this->settings[ 'api_pass' ] : '';
		$this->account_number			= isset($this->settings[ 'account_number' ]) ? $this->settings[ 'account_number' ] : '';
		$this->meter_number				= isset($this->settings[ 'meter_number' ]) ? $this->settings[ 'meter_number' ] : '';
		$this->debug					= ( isset($this->settings[ 'debug' ]) && ( $this->settings[ 'debug' ] == 'yes' ) ) ? true : false;

		$this->encode_uploaded_document = isset($this->settings[ 'encode_uploaded_document' ]) ? ($this->settings[ 'encode_uploaded_document' ] == 'yes' ? true : false ) : true;

		$this->soap_method = $this->is_soap_available() ? 'soap' : 'nusoap';
		if( $this->soap_method == 'nusoap' && !class_exists('nusoap_client') ){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/nusoap/lib/nusoap.php';
		}
	}
/*
	private function xa_soap_call( $method, $request ){
		if( $this->soap_method == 'nusoap' ){
			$response = $client->call( $method, array( $request.'Request' => $request ) );
			$response = json_decode( json_encode( $result ), false );
		}else{
			$response = $client->__call( $request );
		}
		return $response;
	}*/

	private function wf_create_soap_client( $wsdl, $options = ['trace' =>	true]){
		if( $this->soap_method=='nusoap' ){
			$soapclient = new nusoap_client( $wsdl, 'wsdl' );
		}else{
			$soapclient = new SoapClient( $wsdl, $options);
		}
		return $soapclient;
	}

	private function xa_get_fedex_image_upload_request( $image_url, $image_id='IMAGE_1' ){
		
		//Get the absolute path of the image file from url 
		$image_url = $_SERVER['DOCUMENT_ROOT'].parse_url($image_url,PHP_URL_PATH);
		
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
				'Key'	  => $this->api_key,
				'Password' => $this->api_pass
			)
		);
		$request['ClientDetail'] = array(
			'AccountNumber' => $this->account_number,
			'MeterNumber'   => $this->meter_number
		);


		$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Upload Documents Request using PHP ***');
		$request['Version'] = array(
			'ServiceId' => 'cdus', 
			'Major' => '19', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		$request['Images']['Id'] = $image_id;
		
		$request['Images']['Image'] = $this->encode_uploaded_document ? base64_encode( stream_get_contents(fopen($image_url, "r")) ) : stream_get_contents(fopen($image_url, "r"));

		return $request;
	}
}
new xa_fedex_image_upload();