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
        $this->params = $params;
        $this->method = trim(strtoupper($method));
        $this->context = $this->getContext();
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
        $params = $this->getParams();
        $headers = $this->getHeaders();

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $urlForSend);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, define('SET_CURLOPT_CONNECTTIMEOUT', 10));
            curl_setopt($curl, CURLOPT_TIMEOUT, define('SET_CURLOPT_TIMEOUT', 10));
            curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $this->context->curlSetoptMethod($curl);

            $response = curl_exec($curl);

            $apiResponse = new ApiResponse($curl, $response);

            curl_close($curl);

        } catch (\Exception $exception) {
            throw new NotConnectApiClientException($urlForSend, $params, $headers, $exception->getMessage());
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
     * Depending on the request method, the request context will be formed differently.
     *
     * @return AbstractContext
     * @throws \Exception
     */
    private function getContext(): AbstractContext
    {
        switch ($this->method) {
            case Method::GET:
                $classContext = new GetContext($this);
                break;
            case Method::POST:
                $classContext = new PostContext($this);
                break;
            default:
                throw new NotFoundMethodApiException('Not found AbstractContext class for method ' . $method);
        }

        return $classContext;
    }
}
