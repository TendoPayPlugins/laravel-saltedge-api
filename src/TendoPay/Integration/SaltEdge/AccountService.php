<?php


namespace TendoPay\Integration\SaltEdge;

use stdClass;
use TendoPay\Integration\SaltEdge\Api\Accounts\AccountsListFilter;
use TendoPay\Integration\SaltEdge\Api\Accounts\InvalidLoginIdException;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class AccountService
{
    const ACCOUNTS_LIST_API_URL = "accounts";

    private $endpointCaller;

    /**
     * AccountService constructor.
     *
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }


    /**
     * Fetches list of accounts from the API.
     *
     * @link https://docs.saltedge.com/reference/#accounts-list
     *
     * @param AccountsListFilter $accountsListFilter filters the results
     *
     * @return stdClass[] list of the accounts
     *
     * @throws ApiEndpointErrorException when unexpected error was returned by the API
     * @throws Api\ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws Api\ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws Api\UnexpectedStatusCodeException when status code was different than declared by API documentation
     *         {@link https://docs.saltedge.com/reference/#errors }
     * @throws Api\WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     * @throws \GuzzleHttp\Exception\GuzzleException only declared due to lower method's declarations, but should never
     *         be thrown
     * @throws InvalidLoginIdException when SaltEdge could not find a login with the requested login_id
     */
    public function getList(AccountsListFilter $accountsListFilter)
    {
        try {
            $received = $this->endpointCaller->call("GET", self::ACCOUNTS_LIST_API_URL, $accountsListFilter->toArray());
            return data_get($received, 'data');
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error->class) {
                // case "LoginNotFound":
                //     throw new InvalidLoginIdException();
                default:
                    return $exception->getOriginalError();
            }
        }
    }
}
