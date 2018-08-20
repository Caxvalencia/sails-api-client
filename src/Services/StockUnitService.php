<?php

namespace Sails\Api\Client\Services;

use GuzzleHttp\Exception\RequestException;
use Sails\Api\Client\Entities\Responses\StockUnitId;
use Sails\Api\Client\Entities\StockUnit;
use Sails\Api\Client\Entities\TokenResponse;
use Sails\Api\Client\Exceptions\SailsException;
use Sails\Api\Client\Traits\ArrayItemToArray;
use Sails\Api\Client\Traits\Singletonable;

class StockUnitService extends Service
{
    use Singletonable,
        ArrayItemToArray;

    const ENDPOINT_RESOURCE = '/api/v1/stock/';

    /**
     * StockUnitService constructor.
     *
     * @param TokenResponse $tokenResponse
     * @param string|null   $apiBaseUri
     */
    protected function __construct($tokenResponse = null, $apiBaseUri = null)
    {
        parent::__construct($tokenResponse, $apiBaseUri);
    }

    /**
     * @param StockUnit[] $stockUnits
     * @param string      $productId
     *
     * @return StockUnitId[]
     * @throws SailsException
     */
    public function create(array $stockUnits, $productId)
    {
        try {
            $response = $this->httpClient->post(self::ENDPOINT_RESOURCE . 'id/' . $productId, [
                'json' => array_map([$this, 'arrayItemToArray'], $stockUnits)
            ]);

            $responseContent = $this->jsonDecodeResponse($response);
            $stockUnitIds = $responseContent['addedElements'];

            if (isset($stockUnitIds['stockUnitId']) && isset($stockUnitIds['sku'])) {
                $stockUnitIds = [$stockUnitIds];
            }

            return array_map([StockUnitId::class, 'fromArray'], $stockUnitIds);
        } catch (RequestException $ex) {
            throw $this->processRequestException($ex);
        }
    }

    /**
     * @param StockUnit $stockUnit
     *
     * @return StockUnitId
     * @throws SailsException
     */
    public function update(StockUnit $stockUnit)
    {
        try {
            $response = $this->httpClient->put(self::ENDPOINT_RESOURCE . 'id/' . $stockUnit->getId(), [
                'json' => $this->getDataForUpdate($stockUnit)
            ]);

            $responseContent = $this->jsonDecodeResponse($response);
        } catch (RequestException $ex) {
            throw $this->processRequestException($ex);
        }

        return new StockUnitId(
            $responseContent['updatedStockUnit']['stockUnitId'],
            $responseContent['updatedStockUnit']['sku']
        );
    }

    /**
     * @param StockUnit $stockUnit
     *
     * @return StockUnitId
     * @throws SailsException
     */
    public function updateBySku(StockUnit $stockUnit)
    {
        try {
            $response = $this->httpClient->put(self::ENDPOINT_RESOURCE . 'sku/' . $stockUnit->getSku(), [
                'json' => $this->getDataForUpdate($stockUnit)
            ]);

            $responseContent = $this->jsonDecodeResponse($response);
        } catch (RequestException $ex) {
            throw $this->processRequestException($ex);
        }

        return new StockUnitId(
            $responseContent['updatedStockUnit']['stockUnitId'],
            $responseContent['updatedStockUnit']['sku']
        );
    }

    /**
     * @param string $stockUnitId
     *
     * @return StockUnitId
     * @throws SailsException
     */
    public function delete($stockUnitId)
    {
        try {
            $response = $this->httpClient->delete(self::ENDPOINT_RESOURCE . $stockUnitId);
            $responseContent = $this->jsonDecodeResponse($response);
        } catch (RequestException $ex) {
            throw $this->processRequestException($ex);
        }

        return new StockUnitId(
            $responseContent['deletedElement']['stockUnitId'],
            $responseContent['deletedElement']['sku']
        );
    }

    /**
     * @param StockUnit $stockUnit
     * @param null      $productId
     *
     * @return StockUnitId|StockUnitId[]
     * @throws SailsException
     */
    public function save(StockUnit $stockUnit, $productId = null)
    {
        if (empty($stockUnit->getId())) {
            return $this->create([$stockUnit], $productId);
        }

        return $this->update($stockUnit);
    }

    /**
     * @param StockUnit $stockUnit
     *
     * @return array
     */
    private function getDataForUpdate(StockUnit $stockUnit)
    {
        return [
            'quantity' => $stockUnit->getQuantity(),
            'price' => $stockUnit->getPrice(),
            'comparePrice' => $stockUnit->getComparePrice(),
            'imageUrl' => $stockUnit->getImageUrl(),
        ];
    }
}
