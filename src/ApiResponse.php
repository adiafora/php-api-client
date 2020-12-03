<?php

namespace Adiafora\ApiClient;

/**
 * Class ApiResponse.
 *
 */
class ApiResponse
{
    private $curl;

    private $response;

    public function __construct($curl, $response)
    {
        $this->curl = $curl;
        $this->response = json_decode($response, true);
    }

    /**
     * Return code of response.
     *
     * @return string
     */
    public function code()
    {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
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

    /**
     * @return string
     */
    public function error()
    {
        return curl_error($this->curl);
    }

    /**
     * @return int
     */
    public function errno()
    {
        return curl_errno($this->curl);
    }
}