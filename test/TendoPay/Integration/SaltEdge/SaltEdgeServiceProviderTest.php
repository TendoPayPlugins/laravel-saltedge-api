<?php

namespace TendoPay\Integration\SaltEdge;

use Orchestra\Testbench\TestCase;

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 06.12.2018
 * Time: 23:54
 */
class SaltEdgeServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [SaltEdgeServiceProvider::class];
    }

    public function testShouldRegisterAccountService()
    {
        $service = $this->app->get(AccountService::class);
        $this->assertEquals(get_class($service), AccountService::class);
    }

    public function testShouldRegisterCategoryService()
    {
        $service = $this->app->get(CategoryService::class);
        $this->assertEquals(get_class($service), CategoryService::class);
    }

    public function testShouldRegisterCustomerService()
    {
        $service = $this->app->get(CustomerService::class);
        $this->assertEquals(get_class($service), CustomerService::class);
    }

    public function testShouldRegisterProviderService()
    {
        $service = $this->app->get(ProviderService::class);
        $this->assertEquals(get_class($service), ProviderService::class);
    }
}