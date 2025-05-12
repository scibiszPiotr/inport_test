<?php

namespace Tests\HttpClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
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
        $response->method('getStatusCode')->willReturn(201);
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

    public function testPostThrowsHttpClientExceptionOnNon201Status(): void
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

    public function testGetReturnsResponseContent()
    {
        $expectedBody = '{"status":"ok"}';
        $response = new Response(200, [], $expectedBody);

        $guzzleMock = $this->createMock(Client::class);
        $guzzleMock->method('request')
            ->with('GET', 'v1/test/uri', $this->anything())
            ->willReturn($response);

        $client = new HttpClient($guzzleMock, 123, 'fake_token');

        $result = $client->get('https://example.com', 'v1/test/uri');

        $this->assertSame($expectedBody, $result);
    }

    public function testGetThrowsHttpClientExceptionOnNon200Response()
    {
        $this->expectException(HttpClientException::class);

        $response = new Response(500, [], 'Internal Server Error');

        $guzzleMock = $this->createMock(Client::class);
        $guzzleMock->method('request')
            ->willReturn($response);

        $client = new HttpClient($guzzleMock, 123, 'fake_token');
        $client->get('https://example.com', 'v1/test/uri');
    }

    public function testGetHandlesGuzzleException()
    {
        $this->expectException(HttpClientException::class);

        $guzzleMock = $this->createMock(Client::class);
        $guzzleMock->method('request')
            ->willThrowException(new RequestException(
                'Request failed',
                new Request('GET', 'v1/test/uri')
            ));

        $client = new HttpClient($guzzleMock, 123, 'fake_token');
        $client->get('https://example.com', 'v1/test/uri');
    }
}