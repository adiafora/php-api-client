<?php

namespace Adiafora\ApiClient\Exceptions;

class ApiClientException extends \Exception
{
    protected $url;

    protected $params;

    protected $headers;

    /**
     * ApiClientException constructor.
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

    public function getApiUrl()
    {
        return $this->url;
    }

    public function getApiParams()
    {
        return $this->params;
    }

    public function getApiHeaders()
    {
        return $this->headers;
    }
}
