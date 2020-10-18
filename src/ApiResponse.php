<?php

namespace Adiafora\ApiClient;

/**
 * Class ApiResponse.
 *
 */
class ApiResponse
{
    private $code;

    private $response;

    public function __construct($curl, $response)
    {
        $this->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->response = json_decode($response, true);
    }

    /**
     * Return code of response.
     *
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * Return body of response.
     *
     * @return array
     */
    public function response()
    {
        return $this->response;
    }
}