<?php

if (!defined('ABSPATH')) {
    exit;
}

class Ph_Fedex_Auth_Handler
{
    // Auth provider API
    public const PH_FEDEX_AUTH_PROVIDER_API = 'https://auth-provider.pluginhive.io/api/auth-provider/token';

    /**
     * Get Auth provider token
     *
     * @param string $invoker
     * @return string $token
     */
    public static function phGetAuthProviderToken($invoker = '')
    {
        $token          = '';
        $endpoint       = '';
        $fedexSettings  = get_option('woocommerce_' . WF_Fedex_ID . '_settings', null);
        $debug          = (isset($fedexSettings['debug']) && !empty($fedexSettings['debug']) && $fedexSettings['debug'] == 'yes') ? true : false;

        $headers = [
            "Content-Type" => "application/json",
        ];

        $body = [
            'subject' => 'PH_FEDEX_PLUGIN',
        ];

        $body = wp_json_encode($body);

        if ($invoker == 'ph_iframe') {

            $endpoint = Ph_Fedex_Auth_Handler::PH_FEDEX_AUTH_PROVIDER_API;
            $headers['Authorization'] = "Basic MDg4NDcxYmYtNWZmNi00MzdiLWFjYjUtNzY4Y2M1ODg5YzllOmRrUlVacTRvRHpGQ29CNVZGOG9ZTw==";

        } else {

            $phFedexClientCredentials     = isset($fedexSettings['client_credentials']) ? $fedexSettings['client_credentials'] : null;
            $headers['Authorization']   = "Basic $phFedexClientCredentials";

            $endpoint = Ph_Fedex_Auth_Handler::PH_FEDEX_AUTH_PROVIDER_API;

        }

        $result = Ph_Fedex_Api_Invoker::phCallApi($endpoint, '', $body, $headers, 'POST', 'auth_token');

        if (!empty($result) && is_array($result) && isset($result['response'])) {

            if (isset($result['response']['code']) && $result['response']['code'] == 200 && isset($result['body'])) {
                $result = json_decode($result['body']);

                // Update access token in transient
                if (isset($result->accessToken) && !empty($result->accessToken)) {

                    $token = $result->accessToken;

                    // Do not cache Iframe token
                    if ($invoker != 'ph_iframe') {
                        set_transient('PH_FEDEX_AUTH_PROVIDER_TOKEN', $token, 1800);
                    }
                }

                // Update refresh token in transient
                if (isset($result->refreshToken) && !empty($result->refreshToken)) {
                    set_transient('PH_FEDEX_AUTH_PROVIDER_REFRESH_TOKEN', $result->refreshToken, 1800);
                }
            } else {

                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- Failed to get Authentication Token -------------------------------', $debug);
                Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport($result['response']['message'], $debug);
            }
        } else {

            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport('------------------------------- Failed to get Authentication Token -------------------------------', $debug);
            Ph_Fedex_Woocommerce_Shipping_Common::addAdminDiagnosticReport(print_r($result, 1), $debug);
        }

        return $token;
    }
}
