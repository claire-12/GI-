<?php

defined('ABSPATH') || exit;

if (!function_exists('ph_fedex_fetch_license_hash_and_update_db')) {

	function ph_fedex_fetch_license_hash_and_update_db($license_key) {

		$fedex_settings 	= get_option('woocommerce_' . WF_Fedex_ID . '_settings', []);
		$debug  			= (isset($fedex_settings['debug']) && !empty($fedex_settings['debug']) && $fedex_settings['debug'] == 'yes') ? true : false;

		// Delete the transient
		delete_transient('PH_FEDEX_INTERNAL_ENDPOINTS');
		delete_transient('PH_FEDEX_AUTH_PROVIDER_TOKEN');
		
		$reg_endpoint 	= "https://carrier-registration-api.pluginhive.io/api/carriers/5758707e-2346-45b0-9553-4240dd35bda3/registration/?licenseKey=" . $license_key;

		if (!class_exists('Ph_Fedex_Auth_Handler')) {

			include_once plugin_dir_path(__DIR__) . 'class-ph-fedex-auth-handler.php';
		}

		if (!class_exists('Ph_Fedex_Api_Invoker')) {

			include_once plugin_dir_path(__DIR__) . 'class-ph-fedex-api-invoker.php';
		}

		$auth_token = Ph_Fedex_Auth_Handler::phGetAuthProviderToken('ph_iframe');

		if (empty($auth_token)) {

			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- PH FEDEX License Hash Update - No token found -------------------------------', $debug);

			return;
		}

		$headers = [
			'Authorization'	=> "Bearer $auth_token",
		];

		$bookmark_response = Ph_Fedex_Api_Invoker::phCallApi($reg_endpoint, '', [], $headers, 'GET');

		$response_code 		= wp_remote_retrieve_response_code($bookmark_response);
		$response_message 	= wp_remote_retrieve_response_message($bookmark_response);
		$response_body 		= wp_remote_retrieve_body($bookmark_response);

		if (is_wp_error($bookmark_response) && is_object($bookmark_response)) {

			$error_message = $bookmark_response->get_error_message();

			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- PH FEDEX License Hash Update - WP Error -------------------------------', $debug);
			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($error_message, $debug);

			return;
		}

		$response_obj 	= json_decode($response_body, true);

		Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- PH FEDEX License Hash Update - Registration Response -------------------------------', $debug);
		Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($response_obj, 1), $debug);

		if ($response_code == 200 && isset($response_obj['_links']) && isset($response_obj['_links']['accessKey'])) {

			$endpoint = $response_obj['_links']['accessKey']['href'];

			$response = Ph_Fedex_Api_Invoker::phCallApi($endpoint, '', [], [], 'POST');

			$response_code 		= wp_remote_retrieve_response_code($response);
			$response_message 	= wp_remote_retrieve_response_message($response);
			$response_body 		= wp_remote_retrieve_body($response);

			if (is_wp_error($response) && is_object($response)) {

				$error_message = $response->get_error_message();

				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- PH FEDEX License Hash Update - WP Error -------------------------------', $debug);
				Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($error_message, $debug);

				return;
			}

			$response_obj 	= json_decode($response_body, true);

			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- PH FEDEX License Hash Update - Registration Details -------------------------------', $debug);
			Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($response_obj, 1), $debug);

			if ($response_code == 200 && isset($response_obj['clientId'])) {

				$client_id 		= $response_obj['clientId'];
				$client_secret 	= $response_obj['secret'];
				$license_hash 	= $response_obj['externalClientId'];

				$ph_client_credentials 	= base64_encode($client_id . ':' . $client_secret);

				$fedex_settings['client_credentials'] 	= $ph_client_credentials;
				$fedex_settings['client_license_hash'] 	= $license_hash;

				update_option('woocommerce_' . WF_Fedex_ID . '_settings', $fedex_settings);
			}
		}
	}
}

add_action('ph_wc_fedex_plugin_license_activated', 'ph_fedex_fetch_license_hash_and_update_db', 10, 1);