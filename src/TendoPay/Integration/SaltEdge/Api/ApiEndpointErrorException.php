<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 08.12.2018
 * Time: 00:46
 */

namespace TendoPay\Integration\SaltEdge\Api;


use stdClass;
use Throwable;

class ApiEndpointErrorException extends SaltEdgeApiException
{
    private $originalError;

    /**
     * ApiEndpointErrorException constructor. Note: The message is NOT binary safe.
     *
     * @param stdClass $originalError the original unmodified error object as received from the API.
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    public function __construct(
        stdClass $originalError,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->originalError = $originalError;
    }

    /**
     * Returns the unmodified error object as received from the API.
     * @return stdClass
     */
    public function getOriginalError()
    {
        return $this->originalError;
    }
}