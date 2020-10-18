<?php

namespace Adiafora\ApiClient\Contexts;

use Adiafora\ApiClient\AbstractApiClient;

abstract class AbstractContext
{
    /**
     * @var AbstractApiClient
     */
    protected $api;

    /**
     * AbstractContext constructor.
     *
     * @param AbstractApiClient $api
     */
    public function __construct(AbstractApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * Setting the Method for a curl request.
     *
     * @param $curl
     *
     * @return void
     */
    abstract public function curlSetoptMethod($curl);

    /**
     * Getting the final url address for the request.
     *
     * @return string
     */
    abstract public function getUrlForSend();
}
