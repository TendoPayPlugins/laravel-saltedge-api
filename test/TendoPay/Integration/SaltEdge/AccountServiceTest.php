<?php

namespace TendoPay\Integration\SaltEdge;


use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use TendoPay\Integration\SaltEdge\Api\Accounts\AccountsListFilter;
use TendoPay\Integration\SaltEdge\Api\Accounts\InvalidLoginIdException;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class AccountServiceTest extends TestCase
{
    /** @var AccountService $objectUnderTests */
    private $objectUnderTests;

    /** @var MockInterface $endpointCallerMock */
    private $endpointCallerMock;

    public function setUp()
    {
        parent::setUp();
        $this->endpointCallerMock = Mockery::mock(EndpointCaller::class);
        $this->objectUnderTests = new AccountService($this->endpointCallerMock);
    }

    /**
     * @throws Api\SaltEdgeApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldProperlyPassParametersForAccountsListToTheEndpointCaller()
    {
        // given
        $filters = AccountsListFilter::builder()
            ->withFormId("FAKE FORM ID")
            ->withCustomerId("FAKE CUSTOMER ID")
            ->withLoginId("FAKE LOGIN ID");

        $this->endpointCallerMock
            ->shouldReceive("call")
            ->with("GET", "accounts", $filters->toArray())
            ->andReturn((object)["data" => "passed"]);

        // when
        $return = $this->objectUnderTests->getList($filters);

        // then
        $this->assertEquals("passed", $return);
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\Accounts\InvalidLoginIdException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
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
        $this->objectUnderTests->getList(AccountsListFilter::builder());
    }

    /**
     * @throws ApiEndpointErrorException
     * @throws Api\ApiKeyClientMismatchException
     * @throws Api\ClientDisabledException
     * @throws Api\UnexpectedStatusCodeException
     * @throws Api\WrongApiKeyException
     * @throws InvalidLoginIdException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldThrowExceptionAfterReceivingLoginNotFoundError()
    {
        // given
        $this->expectException(InvalidLoginIdException::class);

        $error = (object)[
            "error_class" => "LoginNotFound",
            "error_message" => "error_message",
            "request" => "request"
        ];

        $enpointCallerException = new ApiEndpointErrorException($error);

        $this->endpointCallerMock->shouldReceive("call")->andThrow($enpointCallerException);

        // when
        $this->objectUnderTests->getList(AccountsListFilter::builder());
    }
}
