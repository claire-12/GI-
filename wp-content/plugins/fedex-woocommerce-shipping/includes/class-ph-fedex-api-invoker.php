<?php

if (!defined('ABSPATH')) {
    exit;
}

class Ph_Fedex_Api_Invoker
{
    /**
     * Make API call
     */
    public static function phCallApi($endpoint, $token, $body = [], $headers = [], $method = 'POST', $type = '')
    {
        $args           = [];
        $fedexSettings  = get_option('woocommerce_' . WF_Fedex_ID . '_settings', null);
        $debug          = (isset($fedexSettings['debug']) && !empty($fedexSettings['debug']) && $fedexSettings['debug'] == 'yes') ? true : false;

        if (!empty($token)) {
            $headers['Authorization']       = "Bearer $token";
            $phFedexClientLicenseHash     = isset($fedexSettings['client_license_hash']) ? $fedexSettings['client_license_hash'] : null;
            $headers['x-license-key-id']    = $phFedexClientLicenseHash;
            $headers['env']                 = 'live';
        }

        if (!empty($headers)) {
            $args['headers'] = $headers;
        }

        if (!empty($body)) {
            $args['body'] = $body;
        }

        $args['timeout'] = 20;
        $args['method']  = $method;

        try {

            $result = wp_remote_request($endpoint, $args);

            return $result;
        } catch (Exception $e) {

            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- PH FedEx API Invoker Exception -------------------------------', $debug);
            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($e->getMessage(), $debug);
        }
    }
}
