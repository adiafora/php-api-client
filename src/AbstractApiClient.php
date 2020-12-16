<?php

namespace Adiafora\ApiClient;

use Adiafora\ApiClient\Exceptions\NotConnectApiClientException;
use Adiafora\ApiClient\Exceptions\NotFoundMethodApiException;
use Adiafora\ApiClient\Contexts\AbstractContext;
use Adiafora\ApiClient\Contexts\GetContext;
use Adiafora\ApiClient\Contexts\PostContext;

/**
 * The main class that executes a request to the API.
 *
 */
abstract class AbstractApiClient
{
    /**
     * Query parameter.
     *
     * @var array
     */
    protected $params;

    /**
     * Request method.
     *
     * @var string
     */
    protected $method;

    /**
     * Class for creating a request context.
     *
     * @var AbstractContext
     */
    protected $context;

    /**
     * AbstractApiClient constructor.
     *
     * @param string $method
     * @param array  $params
     *
     * @throws \Exception
     */
    public function __construct(string $method = Method::GET, array $params = [])
    {
        $this->params  = $params;
        $this->method  = trim(strtoupper($method));
        $this->context = $this->getContext();

        if (defined('SET_CURLOPT_CONNECTTIMEOUT') === false) {
            define('SET_CURLOPT_CONNECTTIMEOUT', 10);
        }

        if (defined('SET_CURLOPT_TIMEOUT') === false) {
            define('SET_CURLOPT_TIMEOUT', 10);
        }
    }

    /**
     * Setting HTTP request headers.
     *
     * @return array
     */
    abstract public function getHeaders(): array;

    /**
     * Address of the service for sending requests (case-sensitive).
     *
     * @return string
     */
    abstract public function getUrl(): string;

    /**
     * Main method for executing an API request.
     *
     * @return ApiResponse
     * @throws NotConnectApiClientException
     */
    public function send(): ApiResponse
    {
        $urlForSend = $this->context->getUrlForSend();
        $params     = $this->getParams();
        $headers    = $this->getHeaders();

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $urlForSend);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, SET_CURLOPT_CONNECTTIMEOUT);
            curl_setopt($curl, CURLOPT_TIMEOUT, SET_CURLOPT_TIMEOUT);
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $this->context->curlSetoptMethod($curl);

            $response = curl_exec($curl);

            $apiResponse = new ApiResponse($curl, $response, $this);

            curl_close($curl);

        } catch (\Exception $exception) {
            throw new NotConnectApiClientException($this->getUrl(), $params, $headers, $exception->getMessage());
        }

        return $apiResponse;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Here you define an array of classes for each method of sending data.
     *
     * @return array
     */
    protected function contexts()
    {
        return [
            Method::GET  => GetContext::class,
            Method::POST => PostContext::class,
        ];
    }

    /**
     * Depending on the request method, the request context will be formed differently.
     *
     * @return AbstractContext
     * @throws \Exception
     */
    protected function getContext(): AbstractContext
    {
        try {
            $classContext = $this->contexts()[$this->method];
        } catch (\Exception $exception) {
            throw new NotFoundMethodApiException($this->getUrl(), $this->getParams(), $this->getHeaders(), 'Not found AbstractContext class for method ' . $this->method);
        }

        return new $classContext($this);
    }
}
