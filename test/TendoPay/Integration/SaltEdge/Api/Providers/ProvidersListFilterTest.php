<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 09.12.2018
 * Time: 01:00
 */

namespace TendoPay\Integration\SaltEdge\Api\Providers;


use DateTime;
use PHPUnit\Framework\TestCase;

class ProvidersListFilterTest extends TestCase
{
    public function testShouldContainOnlySelectedFilters()
    {
        $this->assertEquals(["form_id" => "FAKE"],
            ProvidersListFilter::builder()->withFormId("FAKE")->toArray());

        $this->assertEquals(["provider_key_owner" => "FAKE"],
            ProvidersListFilter::builder()->withProviderKeyOwner("FAKE")->toArray());

        $this->assertEquals(["include_provider_fields" => true],
            ProvidersListFilter::builder()->withIncludeProviderFields(true)->toArray());

        $this->assertEquals(["include_fake_providers" => false],
            ProvidersListFilter::builder()->withIncludeFakeProviders(false)->toArray());

        $this->assertEquals(["mode" => "FAKE"],
            ProvidersListFilter::builder()->withMode("FAKE")->toArray());

        $this->assertEquals(["country_code" => "FAKE"],
            ProvidersListFilter::builder()->withCountryCode("FAKE")->toArray());

        $currentDate = new DateTime();
        $this->assertEquals(["form_date" => $currentDate],
            ProvidersListFilter::builder()->withFormDate($currentDate)->toArray());
    }

    public function testShouldContainEmptyFilters()
    {
        $this->assertEquals([], ProvidersListFilter::builder()->toArray());
    }

    public function testShouldContainFullFilters()
    {
        $currentDate = new DateTime();
        $this->assertEquals([
            "form_id" => "FAKE FORM ID",
            "form_date" => $currentDate,
            "mode" => "FAKE MODE",
            "country_code" => "FAKE COUNTRY_CODE",
            "include_fake_providers" => true,
            "include_provider_fields" => false,
            "provider_key_owner" => "FAKE OWNER"
        ], ProvidersListFilter::builder()
            ->withFormId("FAKE FORM ID")
            ->withFormDate($currentDate)
            ->withMode("FAKE MODE")
            ->withCountryCode("FAKE COUNTRY_CODE")
            ->withIncludeFakeProviders(true)
            ->withIncludeProviderFields(false)
            ->withProviderKeyOwner("FAKE OWNER")
            ->toArray()
        );
    }
}