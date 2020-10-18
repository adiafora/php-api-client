<?php

namespace Adiafora\ApiClient\Contexts;

/**
 * Class GetContext.
 *
 * GET Method of request.
 */
class GetContext extends AbstractContext
{
    public function curlSetoptMethod($curl)
    {
        curl_setopt($curl, CURLOPT_HTTPGET, true);
    }

    public function getUrlForSend()
    {
        return $this->api->getUrl() . '?' . http_build_query($this->api->getParams());
    }
}
