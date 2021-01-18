<?php

use TendoPay\Integration\SaltEdge\Api\Accounts\TransactionsListFilter;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\Transactions\TransactionNotFoundException;

class TransactionService
{
    const LIST_TRANSACTIONS_API_URL = "customers";

    private $endpointCaller;

    /**
     * TransactionService constructor.
     *
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    /**
     * Fetches Transactions by Connection ID and account
     *
     * @link https://docs.saltedge.com/account_information/v5/#transactions-attributes
     *
     * @param int $connectionId is Customer's Connection ID
     * @param int $accountId is customer's account ID
     *
     * @return stdClass Transaction
     *
     * @throws Api\ApiEndpointErrorException when unexpected error was returned by the API
     * @throws Api\ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws Api\ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws Api\UnexpectedStatusCodeException when status code was different than declared by API documentation
     *         {@link https://docs.saltedge.com/reference/#errors }
     * @throws Api\WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     * @throws \GuzzleHttp\Exception\GuzzleException only declared due to lower method's declarations, but should never
     *         be thrown
     * @throws TransactionNotFoundException when transaction with given Connection ID or Account ID could not be found
     */
    public function getList(TransactionsListFilter $transactionsListFilter)
    {
        try {
            $received = $this->endpointCaller->call("GET", sprintf(self::LIST_TRANSACTIONS_API_URL, $transactionsListFilter->toArray()));
            return $received->data;
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error_class) {
                case "CustomerNotFound":
                    throw new TransactionNotFoundException();
                default:
                    throw $exception;
            }
        }
    }

}
