<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 07.12.2018
 * Time: 00:25
 */

namespace TendoPay\Integration\SaltEdge\Api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class EndpointCaller
{
    private $client;
    private $apiUrl;
    private $appId;
    private $secret;

    /**
     * EndpointCaller constructor.
     *
     * @param ClientInterface $client http client implementation
     * @param string $apiUrl root API url
     * @param string $appId the app-id used to authorize the API call
     * @param string $secret the secret used to authorize the API call
     */
    public function __construct(ClientInterface $client, $apiUrl, $appId, $secret)
    {
        $this->client = $client;
        $this->appId = $appId;
        $this->secret = $secret;
        $this->apiUrl = $apiUrl;
    }

    /**
     * Prepares for the call and actually calls the SaltEdge API.
     *
     * @param string $method type of http method {@see }
     * @param string $url part of the endpoint URL (all the segments except for the root API url).
     * @param array $payload the data to be sent
     *
     * @return stdClass[]|stdClass the response
     *
     * @throws \GuzzleHttp\Exception\GuzzleException declared due to same declaration in lower methods of GuzzleHttp
     *         client. This exception should be properly handled in this function and it should not be rethrown.
     * @throws ApiEndpointErrorException when unexpected error was returned by the API
     * @throws ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws UnexpectedStatusCodeException when status code was different than declared by API documentation
     *         {@link https://docs.saltedge.com/reference/#errors }
     * @throws WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     */
    public function call($method, $url, array $payload = [])
    {
        $privateKey = null;
        if (config('saltedge.storage_mode') === 'local') {
            $privateKey = storage_path(config('saltedge.private_key_path'));
        } elseif (config('saltedge.storage_mode') === 's3') {
            $privateKey = config('saltedge.private_key_path');
        }

        if ($privateKey === null) {
            throw new \Exception('Make sure to pass a private key in your environment, as well as a storage mode.');
        }

        $privateKey = openssl_get_privatekey(file_get_contents($privateKey));

        $expiresAt = \Carbon\Carbon::now()->addMinutes(5)->timestamp;
        $signature = "$expiresAt|$method|$this->apiUrl" . "$url" . (count($payload) > 0 ? '|' . json_encode($payload) : '');
        openssl_sign($signature, $encodedSignature, $privateKey, OPENSSL_ALGO_SHA256);

        \Log::debug($signature);

        $options = [
            "headers" => [
                "Accept" => "application/json",
                "Content-type" => "application/json",
                "App-id" => $this->appId,
                "Secret" => $this->secret,
                "Expires-At" => $expiresAt,
                "Signature" => base64_encode($encodedSignature)
            ]
        ];

        if (!empty($payload)) {
            $options["json"] = $payload;
        }

        try {
            $response = $this->client->request($method, $this->apiUrl . $url, $options);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
        }

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents());
        } else {
            $this->handleErrors($response);
            // no return, will throw exception
        }
    }

    /**
     * Handles errors and throws non-generic exceptions.
     *
     * @param ResponseInterface $response response from the API. It will contain the API error details.
     *
     * @throws ApiKeyClientMismatchException when the API key used in the request does not belong to a client
     * @throws ClientDisabledException when the client has been disabled. You can find out more about the disabled
     *         status on {@link https://docs.saltedge.com/guides/your_account/#disabled } guides page
     * @throws ApiEndpointErrorException when unexpected error was returned by the API
     * @throws WrongApiKeyException when the API key with the provided App-id and Secret does not exist or is
     *         inactive
     */
    private function handleErrors(ResponseInterface $response)
    {
        $originalError = json_decode($response->getBody()->getContents());
        \Log::debug(json_encode($originalError));

        switch ($originalError->error->class) {
            // case "ApiKeyNotFound":
            //     throw new WrongApiKeyException(sprintf(
            //         "%s: %s. Request: %s",
            //         $originalError->error->class,
            //         $originalError->error_message,
            //         var_export($originalError->request, true)
            //     ));
            //     break;
            // case "ClientDisabled":
            //     throw new ClientDisabledException(sprintf(
            //         "%s: %s. Request: %s",
            //         $originalError->error->class,
            //         $originalError->error_message,
            //         var_export($originalError->request, true)
            //     ));
            //     break;
            // case "ClientNotFound":
            //     throw new ApiKeyClientMismatchException(sprintf(
            //         "%s: %s. Request: %s",
            //         $originalError->error->class,
            //         $originalError->error_message,
            //         var_export($originalError->request, true)
            //     ));
            //     break;
            default:
                throw new ApiEndpointErrorException(
                    $originalError,
                    sprintf(
                        "Got error code: %s, error class: %s. Not sure how to handle it. Body of error response: %s",
                        $response->getStatusCode(),
                        $originalError->error->class,
                        var_export($originalError, true)
                    )
                );
        }
    }
}
