<?php

namespace TendoPay\Integration\SaltEdge\Api\Transactions;


class TransactionsListFilter
{
    private $filters = [];

    /**
     * TransactionsListFilter constructor.
     */
    private function __construct()
    {
    }

    public static function builder()
    {
        return new TransactionsListFilter();
    }

    public function withConnectionId($connectionId)
    {
        $this->filters["connection_id"] = $connectionId;
        return $this;
    }

    public function withAccountId($accountId)
    {
        $this->filters['account_id'] = $accountId;
        return $this;
    }

    public function toArray()
    {
        return $this->filters;
    }
}
