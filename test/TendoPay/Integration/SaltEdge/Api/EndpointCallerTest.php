<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 08.12.2018
 * Time: 01:15
 */

namespace TendoPay\Integration\SaltEdge\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Mockery;
use Nexmo\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use stdClass;

class EndpointCallerTest extends TestCase
{
    /**
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws WrongApiKeyException
     */
    public function testShouldThrowExceptionForWrongApiKey()
    {
        // given
        $this->expectException(WrongApiKeyException::class);

        $clientMock = Mockery::mock(Client::class);

        $bodyMock = Mockery::mock(StreamInterface::class);
        $content = new stdClass();
        $content->error_class = "ApiKeyNotFound";
        $content->error_message = "FAKE MESSAGE";
        $content->request = "FAKE REQUEST";
        $bodyMock->shouldReceive("getContents")->andReturn(json_encode($content));

        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive("getStatusCode")->andReturn(400);
        $responseMock->shouldReceive("getBody")->andReturn($bodyMock);

        $clientExceptionMock = new ClientException(null, Mockery::mock(RequestInterface::class), $responseMock);

        $clientMock->shouldReceive("request")->with("GET", "FAKE URL", Mockery::any())->andThrow($clientExceptionMock);

        $objectUnderTests = new EndpointCaller($clientMock, "FAKE URL", "FAKE APP ID", "FAKE SECRET");

        // when
        $objectUnderTests->call("GET", "");
    }

    /**
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionWhenClientDisabled()
    {
        // given
        $this->expectException(ClientDisabledException::class);

        $clientMock = Mockery::mock(Client::class);

        $bodyMock = Mockery::mock(StreamInterface::class);
        $content = new stdClass();
        $content->error_class = "ClientDisabled";
        $content->error_message = "FAKE MESSAGE";
        $content->request = "FAKE REQUEST";
        $bodyMock->shouldReceive("getContents")->andReturn(json_encode($content));

        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive("getStatusCode")->andReturn(400);
        $responseMock->shouldReceive("getBody")->andReturn($bodyMock);

        $clientExceptionMock = new ClientException(null, Mockery::mock(RequestInterface::class), $responseMock);

        $clientMock->shouldReceive("request")->with("GET", "FAKE URL", Mockery::any())->andThrow($clientExceptionMock);

        $objectUnderTests = new EndpointCaller($clientMock, "FAKE URL", "FAKE APP ID", "FAKE SECRET");

        // when
        $objectUnderTests->call("GET", "");
    }

    /**
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionWhenApiKeyDoesNotBelongToClient()
    {
        // given
        $this->expectException(ApiKeyClientMismatchException::class);

        $clientMock = Mockery::mock(Client::class);

        $bodyMock = Mockery::mock(StreamInterface::class);
        $content = new stdClass();
        $content->error_class = "ClientNotFound";
        $content->error_message = "FAKE MESSAGE";
        $content->request = "FAKE REQUEST";
        $bodyMock->shouldReceive("getContents")->andReturn(json_encode($content));

        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive("getStatusCode")->andReturn(400);
        $responseMock->shouldReceive("getBody")->andReturn($bodyMock);

        $clientExceptionMock = new ClientException(null, Mockery::mock(RequestInterface::class), $responseMock);

        $clientMock->shouldReceive("request")->with("GET", "FAKE URL", Mockery::any())->andThrow($clientExceptionMock);

        $objectUnderTests = new EndpointCaller($clientMock, "FAKE URL", "FAKE APP ID", "FAKE SECRET");

        // when
        $objectUnderTests->call("GET", "");
    }

    /**
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionWhenUnexpectedErrorClassReturned()
    {
        // given
        $this->expectException(UnexpectedErrorException::class);

        $clientMock = Mockery::mock(Client::class);

        $bodyMock = Mockery::mock(StreamInterface::class);
        $content = new stdClass();
        $content->error_class = "FAKE CLASS";
        $content->error_message = "FAKE MESSAGE";
        $content->request = "FAKE REQUEST";
        $bodyMock->shouldReceive("getContents")->andReturn(json_encode($content));

        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive("getStatusCode")->andReturn(400);
        $responseMock->shouldReceive("getBody")->andReturn($bodyMock);

        $clientExceptionMock = new ClientException(null, Mockery::mock(RequestInterface::class), $responseMock);

        $clientMock->shouldReceive("request")->with("GET", "FAKE URL", Mockery::any())->andThrow($clientExceptionMock);

        $objectUnderTests = new EndpointCaller($clientMock, "FAKE URL", "FAKE APP ID", "FAKE SECRET");

        // when
        $objectUnderTests->call("GET", "");
    }

    /**
     * @throws ApiKeyClientMismatchException
     * @throws ClientDisabledException
     * @throws UnexpectedErrorException
     * @throws WrongApiKeyException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionWhenUnexpectedStatusCodeReturned()
    {
        // given
        $this->expectException(UnexpectedErrorException::class);

        $clientMock = Mockery::mock(Client::class);

        $bodyMock = Mockery::mock(StreamInterface::class);
        $content = new stdClass();
        $content->error_class = "FAKE CLASS";
        $content->error_message = "FAKE MESSAGE";
        $content->request = "FAKE REQUEST";
        $bodyMock->shouldReceive("getContents")->andReturn(json_encode($content));

        $responseMock = Mockery::mock(ResponseInterface::class);
        $responseMock->shouldReceive("getStatusCode")->andReturn(500);
        $responseMock->shouldReceive("getBody")->andReturn($bodyMock);

        $clientExceptionMock = new ClientException(null, Mockery::mock(RequestInterface::class), $responseMock);

        $clientMock->shouldReceive("request")->with("GET", "FAKE URL", Mockery::any())->andThrow($clientExceptionMock);

        $objectUnderTests = new EndpointCaller($clientMock, "FAKE URL", "FAKE APP ID", "FAKE SECRET");

        // when
        $objectUnderTests->call("GET", "");
    }
}