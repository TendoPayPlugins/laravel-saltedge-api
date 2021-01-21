<?php


namespace TendoPay\Integration\SaltEdge;

use stdClass;
use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;
use TendoPay\Integration\SaltEdge\Api\FilterDateOutOfRangeException;
use TendoPay\Integration\SaltEdge\Api\FilterValueOutOfRangeException;
use TendoPay\Integration\SaltEdge\Api\Providers\InvalidProviderCodeException;
use TendoPay\Integration\SaltEdge\Api\Providers\ProvidersListFilter;

class ProviderService
{
    const PROVIDER_SHOW_ENDPOINT_URL = "providers/%s";
    const PROVIDERS_LIST_ENDPOINT_URL = "providers";

    private $endpointCaller;

    /**
     * ProviderService constructor.
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }


    /**
     * Fetches provider by provider's code.
     *
     * @link https://docs.saltedge.com/reference/#providers-show
     *
     * @param $code provider's code
     *
     * @return stdClass the provider details
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
     * @throws InvalidProviderCodeException when the provider code is not present in our system
     */
    public function getByCode($code)
    {
        try {
            $received = $this->endpointCaller->call("GET", sprintf(self::PROVIDER_SHOW_ENDPOINT_URL, $code));
            return $received->data;
        } catch (ApiEndpointErrorException $e) {
            switch ($e->getOriginalError()->error->class) {
                case "ProviderNotFound":
                    throw new InvalidProviderCodeException($e);
                default:
                    throw $e;
            }
        }
    }

    /**
     * Fetches list of providers filtered by {@see ProvidersListFilter} from the API.
     *
     * @link https://docs.saltedge.com/reference/#providers-list
     *
     * @param ProvidersListFilter $filters filters the results
     *
     * @return stdClass[] list of the providers
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
     * @throws FilterDateOutOfRangeException when provided a date value that does not fit in admissible range
     * @throws FilterValueOutOfRangeException whea provided a value (e.g. id) which exceeds integer limit
     */
    public function getList(ProvidersListFilter $filters)
    {
        try {
            $received = $this->endpointCaller->call("GET", self::PROVIDERS_LIST_ENDPOINT_URL, $filters->toArray());
            return $received->data;
        } catch (ApiEndpointErrorException $e) {
            switch ($e->getOriginalError()->error->class) {
                case "DateOutOfRange":
                    throw new FilterDateOutOfRangeException($e);
                case "ValueOutOfRange":
                    throw new FilterValueOutOfRangeException($e);
                default:
                    throw $e;
            }
        }
    }
}
