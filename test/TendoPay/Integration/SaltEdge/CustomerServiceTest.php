<?php

namespace TendoPay\Integration\SaltEdge;


use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\Customers\CustomerNotFoundException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class CustomerServiceTest extends TestCase
{
    /** @var CustomerService $objectUnderTests */
    private $objectUnderTests;

    /** @var MockInterface $endpointCallerMock */
    private $endpointCallerMock;

    public function setUp()
    {
        parent::setUp();

        $this->endpointCallerMock = Mockery::mock(EndpointCaller::class);

        $this->objectUnderTests = new CustomerService($this->endpointCallerMock);
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws CustomerNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionAfterReceivingCustomerNotFoundError()
    {
        // given
        $this->expectException(CustomerNotFoundException::class);

        $error = (object)[
            "error_class" => "CustomerNotFound",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getById("FAKE ID");
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws CustomerNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldPassProperArgumentsToTheEndpointCallerFromGetById()
    {
        // given
        $this->endpointCallerMock
            ->shouldReceive("call")
            ->once()
            ->with("GET", "customers/FAKE_ID")
            ->andReturn((object)["data" => "passed"]);

        // when
        $received = $this->objectUnderTests->getById("FAKE_ID");

        // then
        $this->assertEquals("passed", $received);
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws CustomerNotFoundException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldPassProperArgumentsToTheEndpointCallerFromGetAll()
    {
        // given
        $this->endpointCallerMock
            ->shouldReceive("call")
            ->once()
            ->with("GET", "customers")
            ->andReturn((object)["data" => "passed"]);

        // when
        $received = $this->objectUnderTests->getAll();

        // then
        $this->assertEquals("passed", $received);
    }
}
