<?php

if (!defined('ABSPATH')) {
    exit;
}

class Ph_Fedex_Endpoint_Dispatcher
{
    // API to retrieve all the internal endpoints
    public const PH_FEDEX_CARRIER_ENDPOINT = 'https://ship-rate-track-proxy.pluginhive.io/api/ship-rate-track/carriers/fedex';

    /**
     * Fetch internal API endpoints from proxy server
     *
     * @param string $authProviderToken
     * @return array $endpoints
     */
    public static function phGetInternalEndpoints($authProviderToken)
    {
        $result     = [];
        $endpoints  = [];

        $fedexSettings    = get_option('woocommerce_' . WF_Fedex_ID . '_settings', null);
        $debug          = (isset($fedexSettings['debug']) && !empty($fedexSettings['debug']) && $fedexSettings['debug'] == 'yes') ? true : false;

        if (empty(get_transient('PH_FEDEX_INTERNAL_ENDPOINTS'))) {

            $result = Ph_Fedex_Api_Invoker::phCallApi(Ph_Fedex_Endpoint_Dispatcher::PH_FEDEX_CARRIER_ENDPOINT, $authProviderToken, [], [], 'GET');

            if ( is_wp_error($result) ) {
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- Failed to get Internal Endpoints -------------------------------', $debug);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($result->get_error_message(), $debug);

                return $endpoints;
            }

            if (!empty($result) && is_array($result) && isset($result['response'])) {

                if (isset($result['response']['code']) && $result['response']['code'] == 200 && isset($result['body'])) {

                    $result = json_decode($result['body'], 1);
                    $endpoints = $result['_links'];

                    // Update the endpoints in transient
                    set_transient('PH_FEDEX_INTERNAL_ENDPOINTS', $endpoints, 1800);
                } else {

                    Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- Failed to get Internal Endpoints -------------------------------', $debug);
                    Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($result['response']['message'], $debug);
                }
            } else {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- Failed to get Internal Endpoints -------------------------------', $debug);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($result, $debug);
            }
        }

        return $endpoints;
    }

    /**
     * Retrieve Auth Provider access token and the internal API endpoints
     *
     * @return array $apiAccessDetails
     */
    public static function phGetApiAccessDetails()
    {
        if(!class_exists('Ph_Fedex_Api_Invoker'))
        {
            include_once('class-ph-fedex-api-invoker.php');
        }

        $fedexSettings = get_option('woocommerce_' . WF_Fedex_ID . '_settings', null);

        $authProviderToken = get_transient('PH_FEDEX_AUTH_PROVIDER_TOKEN');
        $internalEndpoints = get_transient('PH_FEDEX_INTERNAL_ENDPOINTS');

        if (empty($authProviderToken)) {

            if(!class_exists('Ph_Fedex_Auth_Handler'))
            {
                include_once('class-ph-fedex-auth-handler.php');
            }

            $authProviderToken = Ph_Fedex_Auth_Handler::phGetAuthProviderToken();
        }

        if (!empty($authProviderToken) && empty($internalEndpoints)) {

            $internalEndpoints = Ph_Fedex_Endpoint_Dispatcher::phGetInternalEndpoints($authProviderToken);
        }

        if(empty($authProviderToken) || empty($internalEndpoints))
        {
            return false;
        }

        $apiAccessDetails = [
            'token'             => $authProviderToken,
            'internalEndpoints' => $internalEndpoints,
        ];

        return $apiAccessDetails;
    }
}
