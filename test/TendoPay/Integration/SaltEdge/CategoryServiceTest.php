<?php

namespace TendoPay\Integration\SaltEdge;


use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class CategoryServiceTest extends TestCase
{
    /** @var CategoryService $objectUnderTests */
    private $objectUnderTests;

    /** @var MockInterface $endpointCallerMock */
    private $endpointCallerMock;

    protected function setUp()
    {
        parent::setUp();

        $this->endpointCallerMock = Mockery::mock(EndpointCaller::class);

        $this->objectUnderTests = new CategoryService($this->endpointCallerMock);
    }

    /**
     * @throws Api\SaltEdgeApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testShouldProperlyPassParametersForCategoriesListToTheEndpointCaller()
    {
        // given
        $this->endpointCallerMock
            ->shouldReceive("call")
            ->with("GET", "categories")
            ->andReturn((object)["data" => "passed"]);

        // when
        $return = $this->objectUnderTests->getAll();

        // then
        $this->assertEquals("passed", $return);
    }
}
