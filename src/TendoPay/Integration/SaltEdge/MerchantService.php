<?php

namespace TendoPay\Integration\SaltEdge;

use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class MerchantService
{
    const LIST_Merchants_API_URL = "merchants";

    private $endpointCaller;

    /**
     * CustomerService constructor.
     *
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    /**
     * Fetches all merchants.
     *
     * @link https://docs.saltedge.com/account_information/v5/#merchants-identification
     *
     * @return stdClass[] list of all customers
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
     */
    public function getAll()
    {
        $received = $this->endpointCaller->call("POST", self::LIST_Merchants_API_URL);
        return $received->data;
    }

}
