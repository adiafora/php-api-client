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

    private $dirty;

    private $requestHeaders;

    private $requestUrl;

    private $requestParameters;

    private $totalTime;

    public function __construct($curl, $response, AbstractApiClient $api)
    {
        $this->code              = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->totalTime         = curl_getinfo($curl, CURLINFO_TOTAL_TIME);
        $this->error             = curl_error($curl);
        $this->errno             = curl_errno($curl);
        $this->response          = json_decode($response, true);
        $this->dirty             = $response;
        $this->requestHeaders    = $api->getHeaders();
        $this->requestUrl        = $api->getUrl();
        $this->requestParameters = $api->getParams();
    }

    /**
     * Return the response as it is.
     *
     * @return string
     */
    public function dirty()
    {
        return $this->dirty;
    }
    
    /**
     * Return total transaction time in seconds for last transfer.
     *
     * @return string
     */
    public function totalTime()
    {
        return $this->totalTime;
    }

    /**
     * Return parameters of request.
     *
     * @return array
     */
    public function requestParameters()
    {
        return $this->requestParameters;
    }

    /**
     * Return url of request.
     *
     * @return string
     */
    public function requestUrl()
    {
        return $this->requestUrl;
    }

    /**
     * Return headers of request.
     *
     * @return array
     */
    public function requestHeaders()
    {
        return $this->requestHeaders;
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
