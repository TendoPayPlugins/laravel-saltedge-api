<?php

namespace TendoPay\Integration\SaltEdge;

use TendoPay\Integration\SaltEdge\Api\Accounts\AccountsListFilter;
use TendoPay\Integration\SaltEdge\Api\Accounts\InvalidLoginIdException;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\Connections\ConnectionNotFoundException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class ConnectionService
{
    const CONNECTIONS_API_URL = "connections";
    const SHOW_CUSTOMER_API_URL = "connections/%s";

    private $endpointCaller;

    /**
     * ConnectionService constructor.
     *
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    /**
     * Fetches the list of Connections associated with accounts
     *
     * @link https://docs.saltedge.com/account_information/v5/#connections-list
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
    public function getAllConnections()
    {
        try {
            $received = $this->endpointCaller->call("GET", self::CONNECTIONS_API_URL);
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

    /**
     * Removes a connection
     *
     * @link https://docs.saltedge.com/account_information/v5/#connections-remove
     *
     * @param int $id connection's id
     *
     * @return stdClass response body - example: {"data": {"id": "111111111111111111","removed": true}}
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
     * @throws ConnectionNotFoundException when SaltEdge could not find a connection associated with the passed parameter
     */
    public function remove($id)
    {
        try {
            $received = $this->endpointCaller->call('DELETE', sprintf(self::SHOW_CUSTOMER_API_URL, $id));
            return data_get($received, 'data');
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error->class) {
                // case 'ConnectionNotFound':
                //     throw new ConnectionNotFoundException();
                default:
                    return $exception->getOriginalError();
            }
        }
    }
}
