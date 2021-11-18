<?php

namespace TendoPay\Integration\SaltEdge;

use TendoPay\Integration\SaltEdge\Api\ApiEndpointErrorException;
use TendoPay\Integration\SaltEdge\Api\Customers\CustomerNotFoundException;
use TendoPay\Integration\SaltEdge\Api\EndpointCaller;

class SaltEdgeConnect
{
    const CONNECT_USING_SALTEDGE_CONNECT   = 'connect_sessions/create';
    const RECONNECT_USING_SALTEDGE_CONNECT = 'connect_sessions/reconnect';
    const REFRESH_USING_SALTEDGE_CONNECT   = 'connect_sessions/refresh';

    // Supported Channels
    // Bank of Philippine Island
    public const BPI = 'BPI';
    // Metrobank
    public const MET = 'MET';
    // Banco de Oro
    public const BDO = 'BDO';

    public const AUTHORIZED_PROVIDERS = [
        'BPI' => 'bank_of_philippine_ph',
        'MET' => 'metrobank_ph',
        'BDO' => 'bdo_ph'
    ];

    private $endpointCaller;

    public function __construct(EndpointCaller $endpointCaller)
    {
        $this->endpointCaller = $endpointCaller;
    }

    public function connect($id, $provider = null)
    {
        try {
            if (!self::AUTHORIZED_PROVIDERS[$provider]) {
                throw new \Exception('Unsupported provider');
            }

            $received = $this->endpointCaller->call(
                "POST",
                self::CONNECT_USING_SALTEDGE_CONNECT,
                [
                    'data' => [
                        'customer_id' => $id,
                        'return_connection_id' => true,
                        'consent' => [
                            'scopes' => ['account_details', 'transactions_details', 'holder_information'],
                            'from_date' => \Carbon\Carbon::today()->subDays(364)->format('Y-m-d')
                        ],
                        'attempt' => [
                            'fetch_scopes' => ['accounts', 'transactions', 'holder_info']
                        ],
                        'provider_code' => self::AUTHORIZED_PROVIDERS[$provider],
                        'allowed_countries' => ['PH']
                    ]
                ]
            );
            return data_get($received, 'data');
        } catch (ApiEndpointErrorException $exception) {
            switch ($exception->getOriginalError()->error->class) {
                // case "CustomerNotFound":
                //     throw new CustomerNotFoundException();
                default:
                    return $exception->getOriginalError();
            }
        } catch (\Exception $exception) {
            return 'An error has occured.';
        }
    }

    // public function refresh()
    // {
    // }
}
