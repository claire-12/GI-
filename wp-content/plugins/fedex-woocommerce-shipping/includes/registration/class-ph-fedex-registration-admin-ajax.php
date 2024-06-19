<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class PH_Fedex_Registration_Admin_Ajax
{

	public function __construct()
	{

		add_action('wp_ajax_ph_fedex_update_registration_data', array($this, 'ph_fedex_update_registration_data'));
	}

	function ph_fedex_update_registration_data()
	{

		$clientId 		= isset($_POST['clientId']) ? $_POST['clientId'] : '';
		$clientSecret 	= isset($_POST['clientSecret']) ? $_POST['clientSecret'] : '';
		$licenseHash 	= isset($_POST['licenseHash']) ? $_POST['licenseHash'] : '';

		$phClientCredentials 	= base64_encode($clientId . ':' . $clientSecret);
		$fedexSettings 			= get_option('woocommerce_' . WF_Fedex_ID . '_settings', []);	
		
		$fedexSettings['account_number']	= "PHIVEACCOUNT007PHIVE";
		$fedexSettings['meter_number']		= "PHIVEMETER007PHIVE";
		$fedexSettings['api_key'] 			= "PHIVEKEY007PHIVE";
		$fedexSettings['api_pass'] 			= "PHIVEV0UDstWPY4nu5w=PHIVE";
		$fedexSettings['production']		= "yes";
		
		$fedexSettings['client_credentials'] 	= $phClientCredentials;
		$fedexSettings['client_license_hash'] = $licenseHash;

		update_option('ph_fedex_registered_user', true);

		update_option('woocommerce_' . WF_Fedex_ID . '_settings', $fedexSettings);

		$response = array("status" => 1, "error" => 0, "data" => array(), "message" => 'Success');

		echo json_encode($response);
		wp_die();
	}
}

new PH_Fedex_Registration_Admin_Ajax();
