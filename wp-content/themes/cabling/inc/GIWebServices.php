<?php

class GIWebServices
{
    private string $tokenEndpoint;
    private string $clientId;
    private string $clientSecret;

    public function __construct($tokenEndpoint, $clientId, $clientSecret)
    {
        $this->tokenEndpoint = $tokenEndpoint;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * get access token endpoint
     * @return array|string[]
     */
    public function getAccessToken(): array
    {
        $credentials = base64_encode($this->clientId . ':' . $this->clientSecret);

        $request_data = array(
            'headers' => array(
                'Authorization' => 'Basic ' . $credentials,
                //'Content-Type' => 'application/x-www-form-urlencoded', // Adjust content type as needed
            ),
            'body' => array(
                'grant_type' => 'client_credentials',
            ),
        );

        $response = wp_remote_post($this->tokenEndpoint, $request_data);

        if (is_wp_error($response)) {
            return array('error' => $response->get_error_message());
        } else {
            $cookies = wp_remote_retrieve_header($response, 'set-cookie');
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['access_token'])) {
                return array(
                    'access_token' => $data['access_token'],
                    'cookies' => $cookies,
                );
            } else {
                return array('error' => 'Access token not found in the response.');
            }
        }
    }

    /**
     * @param $apiEndpoint
     * @param array $params
     * @return array|string[]
     */
    public function makeApiRequest($apiEndpoint, array $params): array
    {
        try {
            // Get the access token
            $tokenResult = $this->getAccessToken();
            if (isset($tokenResult['error'])) {
                return $tokenResult;
            }
            $access_token = $tokenResult['access_token'];
            $cookies = $tokenResult['cookies'];

            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token,
                'Cookie: ' . $cookies
            );

            $bodyParams = array();
            if (!empty($params)) {
                $prepareParams = array();
                $lastItem = endArray($params);
                foreach ($params as $param) {
                    $operator = $lastItem === $param ? '' : 'and';
                    $prepareParams[] = array(
                        'Field' => $param['Field'],
                        'Sign' => 'eq',
                        'Value' => $param['Value'],
                        'Operator' => $operator,
                    );
                }
                $bodyParams = array(
                    'Filters' => array(
                        'Filter' => $prepareParams,
                    ),
                );
            }

            $response = $this->curl($apiEndpoint, $bodyParams, $headers);

            return $response['success'] ?? $response;
        } catch (Exception $e) {
            return array('error' => $e->get_error_message());
        }
    }

    /**
     * @param $url
     * @param array $body
     * @param array $headers
     * @return array
     */
    private function curl($url, array $body, array $headers): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        $result = array();
        if (curl_errno($curl)) {
            $result['error'] = curl_error($curl);
        } else {
            $responseData = json_decode($response, true);
            if ($responseData !== null) {
                $result['success'] = $responseData;
            } else {
                $result['error'] = __('CURL error: Something went wrong!');
            }
        }

        curl_close($curl);

        return $result;
    }
}
