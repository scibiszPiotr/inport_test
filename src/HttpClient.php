<?php

namespace Pscibisz\Inpost;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Pscibisz\Inpost\Exceptions\HttpClientException;

readonly class HttpClient implements HttpClientInterface
{
    public function __construct(private int $organizationId, private string $apiToken)
    {
    }

    public function post(string $url, string $uri, \JsonSerializable $requestBody): string
    {
        $client = new Client([
            'base_uri' => $url,
        ]);

        try {
            $response = $client->request('POST', $this->formatUri($uri), [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);
        } catch (GuzzleException $e) {
            $errorMessage = $e->getMessage();
            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody()->getContents();
            }
            /** I create my own exception to make it easier to locate the place in the code where it was created,
             * I attach the original one so that I can see the entire backtrace in sentry, for example
             */
            throw new HttpClientException($errorMessage, $e->getCode(), $e);
        }

        if ($response->getStatusCode() !== 200) {
            throw new HttpClientException($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    private function formatUri(string $uri): string
    {
        return sprintf($uri, $this->organizationId);
    }
}