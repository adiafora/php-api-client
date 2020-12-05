<?php

namespace Adiafora\ApiClient;

/**
 * Class ApiResponse.
 *
 */
class ApiResponse
{
    private $code;

    private $error;

    private $errno;

    private $response;

    public function __construct($curl, $response)
    {
        $this->code     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->error    = curl_error($curl);
        $this->errno    = curl_errno($curl);
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

    /**
     * @return string
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return int
     */
    public function errno()
    {
        return $this->errno;
    }
}
