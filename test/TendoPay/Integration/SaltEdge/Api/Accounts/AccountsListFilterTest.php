<?php

namespace TendoPay\Integration\SaltEdge\Api\Accounts;


use PHPUnit\Framework\TestCase;

class AccountsListFilterTest extends TestCase
{
    public function testShouldContainOnlySelectedFilters()
    {
        $this->assertEquals(["form_id" => "FAKE"],
            AccountsListFilter::builder()->withFormId("FAKE")->toArray());

        $this->assertEquals(["login_id" => "FAKE"],
            AccountsListFilter::builder()->withLoginId("FAKE")->toArray());

        $this->assertEquals(["customer_id" => "FAKE"],
            AccountsListFilter::builder()->withCustomerId("FAKE")->toArray());
    }

    public function testShouldContainEmptyFilters()
    {
        $this->assertEquals([], AccountsListFilter::builder()->toArray());
    }

    public function testShouldContainFullFilters()
    {
        $this->assertEquals([
            "form_id" => "FAKE FORM ID",
            "login_id" => "FAKE LOGIN ID",
            "customer_id" => "FAKE CUSTOMER ID"
        ],
            AccountsListFilter::builder()
                ->withFormId("FAKE FORM ID")
                ->withLoginId("FAKE LOGIN ID")
                ->withCustomerId("FAKE CUSTOMER ID")
                ->toArray()
        );
    }
}
