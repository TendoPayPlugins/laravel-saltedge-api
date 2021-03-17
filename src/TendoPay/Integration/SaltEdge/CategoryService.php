<?php


namespace TendoPay\Integration\SaltEdge;

use stdClass;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class CategoryService
{
    const LIST_URL = "categories";

    private $endpointCaller;


    /**
     * CategoryService constructor.
     *
     * @param EndpointCaller $endpointCaller injected dependency
     */
    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    /**
     * Fetches all categories.
     *
     * @link https://docs.saltedge.com/reference/#categories-list
     *
     * @return stdClass[] list of all categories
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
        try {
            $received = $this->endpointCaller->call("GET", self::LIST_URL);
            return data_get($received, 'data');
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error->class) {
                default:
                    return $exception->getOriginalError();
            }
        }
    }
}
