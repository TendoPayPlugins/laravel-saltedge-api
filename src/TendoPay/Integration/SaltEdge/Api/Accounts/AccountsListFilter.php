<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 08.12.2018
 * Time: 23:22
 */

namespace TendoPay\Integration\SaltEdge\Api\Accounts;


class AccountsListFilter
{
    private $filters = [];

    /**
     * AccountsListFilter constructor.
     */
    private function __construct()
    {
    }

    public static function builder()
    {
        return new AccountsListFilter();
    }

    public function withFormId($formId)
    {
        $this->filters["form_id"] = $formId;
        return $this;
    }

    public function withLoginId($loginId)
    {
        $this->filters["login_id"] = $loginId;
        return $this;
    }

    public function withCustomerId($customerId)
    {
        $this->filters["customer_id"] = $customerId;
        return $this;
    }

    public function toArray()
    {
        return $this->filters;
    }
}