<?php

namespace Adiafora\ApiClient\Exceptions;

/**
 * Exception for problems connecting to the remote API.
 *
 */
class NotConnectApiClientException extends ApiClientException
{
    /**
     * NotConnectApiException constructor.
     *
     * @param string         $url
     * @param array          $params
     * @param array          $headers
     * @param string         $message
     * @param int            $code
     */
    public function __construct(
        string $url,
        array $params,
        array $headers,
        string $message = "",
        int $code = 0
    ) {
        $this->url     = $url;
        $this->params  = $params;
        $this->headers = $headers;

        parent::__construct($message, $code);
    }
}
