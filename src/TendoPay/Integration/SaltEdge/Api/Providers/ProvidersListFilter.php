<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 08.12.2018
 * Time: 15:32
 */

namespace TendoPay\Integration\SaltEdge\Api\Providers;


use DateTime;

class ProvidersListFilter
{
    private $filters = [];

    /**
     * ProvidersListFilter constructor.
     */
    private function __construct()
    {
    }

    public static function builder()
    {
        return new ProvidersListFilter();
    }

    public function withFormId($formId)
    {
        $this->filters["form_id"] = $formId;
        return $this;
    }

    public function withFormDate(DateTime $formDate)
    {
        $this->filters["form_date"] = $formDate;
        return $this;
    }

    public function withCountryCode($countryCode)
    {
        $this->filters["country_code"] = $countryCode;
        return $this;
    }

    public function withMode($mode)
    {
        $this->filters["mode"] = $mode;
        return $this;
    }

    public function withIncludeFakeProviders(bool $includeFakeProviders)
    {
        $this->filters["include_fake_providers"] = $includeFakeProviders;
        return $this;
    }

    public function withIncludeProviderFields(bool $includeProviderFields)
    {
        $this->filters["include_provider_fields"] = $includeProviderFields;
        return $this;
    }

    public function withProviderKeyOwner($providerKeyOwner)
    {
        $this->filters["provider_key_owner"] = $providerKeyOwner;
        return $this;
    }

    public function toArray()
    {
        return $this->filters;
    }
}