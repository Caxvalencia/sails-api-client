<?php

namespace Sails\Api\Client\Services;

use GuzzleHttp\Exception\RequestException;
use Sails\Api\Client\Entities\Product;
use Sails\Api\Client\Entities\Responses\ProductResponse;
use Sails\Api\Client\Entities\TokenResponse;
use Sails\Api\Client\Exceptions\SailsException;
use Sails\Api\Client\Traits\Singletonable;

class ProductService extends Service
{
    use Singletonable;

    const ENDPOINT_RESOURCE = '/api/v1/product/';

    /**
     * ProductService constructor.
     *
     * @param TokenResponse $tokenResponse
     * @param string|null   $apiBaseUri
     */
    protected function __construct($tokenResponse = null, $apiBaseUri = null)
    {
        parent::__construct($tokenResponse, $apiBaseUri);
    }

    /**
     * @param Product $product
     *
     * @return ProductResponse
     */
    public function create(Product $product)
    {
        $response = $this->httpClient->post(self::ENDPOINT_RESOURCE, [
            'json' => $product->toArray()
        ]);

        $responseContent = $this->jsonDecodeResponse($response);
        $stockUnitIds = $responseContent['addedElements']['stockUnitIds'];

        if (isset($stockUnitIds['stockUnitId']) && isset($stockUnitIds['sku'])) {
            $stockUnitIds = [$stockUnitIds];
        }

        return new ProductResponse(
            ProductResponse::CREATED,
            $responseContent['addedElements']['productId'],
            $stockUnitIds
        );
    }

    /**
     * @param Product $product
     *
     * @return ProductResponse
     */
    public function update(Product $product)
    {
        $productArray = $product->toArray();
        unset($productArray['stockUnits']);

        $response = $this->httpClient->put(self::ENDPOINT_RESOURCE . 'id/' . $product->getId(), [
            'json' => $productArray,
        ]);

        $responseContent = $this->jsonDecodeResponse($response);

        return new ProductResponse(
            ProductResponse::UPDATED,
            $responseContent['updatedElement']['productId']
        );
    }

    /**
     * @param Product $product
     *
     * @return ProductResponse
     * @throws SailsException
     */
    public function save(Product $product)
    {
        try {
            if (!empty($product->getId())) {
                return $this->update($product);
            }

            return $this->create($product);
        } catch (RequestException $ex) {
            throw $this->processRequestException($ex);
        }
    }

    /**
     * @param integer $productId
     *
     * @return ProductResponse
     * @throws SailsException
     */
    public function delete($productId)
    {
        try {
            $response = $this->httpClient->delete(self::ENDPOINT_RESOURCE . $productId);
            $responseContent = $this->jsonDecodeResponse($response);
        } catch (RequestException $ex) {
            throw $this->processRequestException($ex);
        }

        return new ProductResponse(
            ProductResponse::DELETED,
            $responseContent['deletedElement']['productId']
        );
    }
}
