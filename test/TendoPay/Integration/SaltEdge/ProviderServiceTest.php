<?php

namespace TendoPay\Integration\SaltEdge;

use DateTime;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;
use TendoPay\Integration\SaltEdge\Api\FilterDateOutOfRangeException;
use TendoPay\Integration\SaltEdge\Api\FilterValueOutOfRangeException;
use TendoPay\Integration\SaltEdge\Api\Providers\InvalidProviderCodeException;
use TendoPay\Integration\SaltEdge\Api\Providers\ProvidersListFilter;

class ProviderServiceTest extends TestCase
{
    /** @var ProviderService $objectUnderTests */
    private $objectUnderTests;

    /** @var MockInterface $endpointCallerMock */
    private $endpointCallerMock;

    public function setUp()
    {
        parent::setUp();

        $this->endpointCallerMock = Mockery::mock(EndpointCaller::class);

        $this->objectUnderTests = new ProviderService($this->endpointCallerMock);
    }

    /**
     * @throws Api\ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws FilterDateOutOfRangeException
     * @throws FilterValueOutOfRangeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionAfterReceivingDateOutOfRangeError()
    {
        // given
        $this->expectException(FilterDateOutOfRangeException::class);

        $error = (object)[
            "error_class" => "DateOutOfRange",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getList(ProvidersListFilter::builder());
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws FilterDateOutOfRangeException
     * @throws FilterValueOutOfRangeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionAfterReceivingValueOutOfRangeError()
    {
        // given
        $this->expectException(FilterValueOutOfRangeException::class);

        $error = (object)[
            "error_class" => "ValueOutOfRange",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getList(ProvidersListFilter::builder());
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws FilterDateOutOfRangeException
     * @throws FilterValueOutOfRangeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldRethrowExceptionAfterReceivingUnknownErrorAfterCallingGetList()
    {
        // given
        $this->expectException(ApiEndpointErrorException::class);

        $error = (object)[
            "error_class" => "FakeError",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getList(ProvidersListFilter::builder());
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws InvalidProviderCodeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionAfterReceivingProviderNotFoundError()
    {
        // given
        $this->expectException(InvalidProviderCodeException::class);

        $error = (object)[
            "error_class" => "ProviderNotFound",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getByCode("FAKE");
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws InvalidProviderCodeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldRethrowExceptionAfterReceivingUnknownErrorAfterCallingGetByCode()
    {
        // given
        $this->expectException(ApiEndpointErrorException::class);

        $error = (object)[
            "error_class" => "FakeError",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getByCode("FAKE CODE");
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws InvalidProviderCodeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldProperlyPassParametersForSingleProviderToTheEndpointCaller()
    {
        // given
        $this->endpointCallerMock
            ->shouldReceive("call")
            ->once()
            ->with("GET", "providers/FAKE")
            ->andReturn((object)["data" => "passed"]);

        // when
        $return = $this->objectUnderTests->getByCode("FAKE");

        // then
        $this->assertEquals("passed", $return);
    }


    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws FilterDateOutOfRangeException
     * @throws FilterValueOutOfRangeException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldProperlyPassParametersForProvidersListToTheEndpointCaller()
    {
        // given
        $currentDate = new DateTime();

        $filters = ProvidersListFilter::builder()
            ->withFormId("ID")
            ->withFormDate($currentDate)
            ->withCountryCode("COUNTRY CODE")
            ->withMode("MODE")
            ->withIncludeFakeProviders(true)
            ->withIncludeProviderFields(false)
            ->withProviderKeyOwner("KEY OWNER");

        $this->endpointCallerMock
            ->shouldReceive("call")
            ->once()
            ->with("GET", "providers", $filters->toArray())
            ->andReturn((object)["data" => "passed"]);

        // when
        $return = $this->objectUnderTests->getList($filters);

        // then
        $this->assertEquals("passed", $return);
    }
}
