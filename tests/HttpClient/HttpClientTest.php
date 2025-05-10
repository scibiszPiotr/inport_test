<?php

namespace Tests\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Pscibisz\Inpost\Exceptions\HttpClientException;
use Pscibisz\Inpost\Services\HttpClients\HttpClient;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class HttpClientTest extends TestCase
{
    private const string BASE_URL = 'https://api.example.com';
    private const string URI_TEMPLATE = '/organization/%d/endpoint';
    private const string TOKEN = 'test-token';
    private const int ORG_ID = 123;

    public function testSuccessfulPostReturnsResponse(): void
    {
        $expectedContent = 'success response';

        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn($expectedContent);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getBody')->willReturn($stream);

        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($response);

        $httpClient = new HttpClient($client, self::ORG_ID, self::TOKEN);

        $result = $httpClient->post(self::BASE_URL, self::URI_TEMPLATE, new DummyJson());

        $this->assertEquals($expectedContent, $result);
    }

    public function testPostThrowsHttpClientExceptionOnGuzzleFailure(): void
    {
        $request = new Request('POST', 'uri');
        $exception = new RequestException('Request failed', $request);

        $client = $this->createMock(Client::class);
        $client->method('request')->willThrowException($exception);

        $httpClient = new HttpClient($client, self::ORG_ID, self::TOKEN);

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Request failed');

        $httpClient->post(self::BASE_URL, self::URI_TEMPLATE, new DummyJson());
    }

    public function testPostThrowsHttpClientExceptionOnNon200Status(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn('Error occurred');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);
        $response->method('getBody')->willReturn($stream);

        $client = $this->createMock(Client::class);
        $client->method('request')->willReturn($response);

        $httpClient = new HttpClient($client, self::ORG_ID, self::TOKEN);

        $this->expectException(HttpClientException::class);
        $this->expectExceptionMessage('Error occurred');

        $httpClient->post(self::BASE_URL, self::URI_TEMPLATE, new DummyJson());
    }
}