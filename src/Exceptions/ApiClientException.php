<?php

namespace Adiafora\ApiClient\Exceptions;

class ApiClientException extends \Exception
{
    protected $url;

    protected $params;

    protected $headers;

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
