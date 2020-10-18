<?php

namespace Adiafora\ApiClient\Contexts;

/**
 * Class PostContext
 *
 * POST Method of request.
 */
class PostContext extends AbstractContext
{
    public function curlSetoptMethod($curl)
    {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->api->getParams(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    public function getUrlForSend()
    {
        return $this->api->getUrl();
    }
}
