<?php

namespace Sails\Api\Client\Services;

use Sails\Api\Client\Traits\Singletonable;

class OrderService extends Service
{
    use Singletonable;

    const ENDPOINT_RESOURCE = '/api/v1/order/';

    /**
     * OrderService constructor.
     *
     * @param null $tokenResponse
     * @param null $apiBaseUri
     */
    protected function __construct($tokenResponse = null, $apiBaseUri = null)
    {
        parent::__construct($tokenResponse, $apiBaseUri);
    }

    /**
     * @return array|mixed|object
     */
    public function all()
    {
        $response = $this->httpClient->get(self::ENDPOINT_RESOURCE);
        $responseContent = $this->jsonDecodeResponse($response);

        return $responseContent;
    }
}
