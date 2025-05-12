<?php

namespace Pscibisz\Inpost\Services\HttpClients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Pscibisz\Inpost\Exceptions\HttpClientException;

final readonly class HttpClient implements HttpClientInterface
{
    public function __construct(
        private Client $client,
        private int $organizationId,
        private string $apiToken
    ) {
    }

    public function post(string $url, string $uri, \JsonSerializable $requestBody): string
    {
        try {
            $response = $this->client->request('POST', $this->formatUri($uri), [
                'base_uri' => $url,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody,
            ]);
        } catch (GuzzleException $e) {
            $this->handleException($e);
        }

        if ($response->getStatusCode() !== 201) {
            throw new HttpClientException($response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    public function get(string $url, string $uri): string
    {
        try {
            $response = $this->client->request('GET', $this->formatUri($uri), [
                'base_uri' => $url,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
        } catch (GuzzleException $e) {
            $this->handleException($e);
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

    public function handleException(\Exception|GuzzleException $e)
    {
        $errorMessage = $e->getMessage();
        if ($e->hasResponse()) {
            $errorMessage = $e->getResponse()->getBody()->getContents();
        }
        /** I create my own exception to make it easier to locate the place in the code where it was created,
         * I attach the original one so that I can see the entire backtrace in sentry, for example
         */
        throw new HttpClientException($errorMessage, $e->getCode(), $e);
    }
}