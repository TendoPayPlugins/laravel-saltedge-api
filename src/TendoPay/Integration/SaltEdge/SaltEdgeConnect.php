<?php

namespace TendoPay\Integration\SaltEdge;


use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\Customers\CustomerNotFoundException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class SaltEdgeConnect {
    const CONNECT_USING_SALTEDGE_CONNECT   = 'connect_sessions/create';
    const RECONNECT_USING_SALTEDGE_CONNECT = 'connect_sessions/reconnect';
    const REFRESH_USING_SALTEDGE_CONNECT   = 'connect_sessions/refresh';

    private $endpointCaller;

    public function __construct( EndpointCaller $endpointCaller ) {
        $this->endpointCaller = $endpointCaller;
    }

    public function connect($id)
    {
        try {
            $received = $this->endpointCaller->call("POST",
                self::CONNECT_USING_SALTEDGE_CONNECT, [
                    'data' => [
                        'customer_id' => $id,
                        'return_connection_id' => true,
                        'consent' => [
                            'scopes' => ['account_details', 'transactions_details', 'holder_information'],
                        ],
                        'attempt' => [
                            'fetch_scopes' => ['accounts', 'transactions', 'holder_info']
                        ]
                    ]
                ]);
            return $received->data;
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error_class) {
                case "CustomerNotFound":
                    throw new CustomerNotFoundException();
                default:
                    throw $exception;
            }
        }
    }

    public function refresh()
    {

    }

}