<?php

namespace Pscibisz\Inpost;

interface HttpClientInterface
{
    /** I leave the url split into base url and uri to allow injection via DI - if a framework were to come here */
    public function post(string $url, string $uri, \JsonSerializable $requestBody): string;
}