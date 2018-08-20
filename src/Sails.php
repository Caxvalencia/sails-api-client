<?php

namespace Sails\Api\Client;

use Sails\Api\Client\Entities\Product;
use Sails\Api\Client\Entities\Responses\StockUnitId;
use Sails\Api\Client\Services\OrderService;
use Sails\Api\Client\Services\ProductService;
use Sails\Api\Client\Services\StockUnitService;
use Sails\Api\Client\Services\TokenService;

/**
 * Class Sails
 * @package Sails\Api\Client
 */
class Sails
{
    const BASE_URI_FOR_DEVELOPMENT = 'https://dev-api.sails.com.co';

    const BASE_URI_FOR_PRODUCTION = 'https://api.sails.com.co';

    /** @var ProductService */
    protected $productService;

    /** @var OrderService */
    protected $orderService;

    /** @var StockUnitService */
    protected $stockUnitService;

    /** @var TokenService */
    private $tokenService;

    /** @var string */
    private $apiUrl;

    /**
     * Sails constructor.
     *
     * @param string $consumerId
     * @param string $consumerSecret
     * @param string $apiUrl
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __construct($consumerId, $consumerSecret, $apiUrl)
    {
        $this->apiUrl = $apiUrl;

        $this->tokenService = TokenService::getInstance($consumerId, $consumerSecret, $apiUrl);
        $this->productService = ProductService::getInstance($this->tokenService->get(), $apiUrl);
        $this->orderService = OrderService::getInstance($this->tokenService->get(), $apiUrl);
        $this->stockUnitService = StockUnitService::getInstance($this->tokenService->get(), $apiUrl);
    }

    /**
     * @param Product $product
     *
     * @return Entities\Responses\ProductResponse
     * @throws Exceptions\SailsException
     */
    public function saveProduct(Product $product)
    {
        $productResponse = $this->productService->save($product);

        if (!empty($product->getId())) {
            $stockUnitsCreated = $this->updateProductStockUnits($product);
            $productResponse->setStockUnitIds($stockUnitsCreated);
        }

        return $productResponse;
    }

    /**
     * @param integer $productId
     *
     * @return Entities\Responses\ProductResponse
     * @throws Exceptions\SailsException
     */
    public function deleteProduct($productId)
    {
        return $this->productService->delete($productId);
    }

    /**
     * @param $stockUnitId
     *
     * @return StockUnitId
     * @throws Exceptions\SailsException
     */
    public function deleteStockUnit($stockUnitId)
    {
        return $this->stockUnitService->delete($stockUnitId);
    }

    /**
     * @return array|mixed|object
     */
    public function getOrders()
    {
        return $this->orderService->all();
    }

    /**
     * @param Product $product
     *
     * @return Entities\Responses\StockUnitId[]
     * @throws Exceptions\SailsException
     */
    private function updateProductStockUnits(Product $product)
    {
        /** @var StockUnitId[] $stockUnitIds */
        $stockUnitIds = [];
        $toCreate = [];

        foreach ($product->getStockUnits() as $stockUnit) {
            if (empty($stockUnit->getId())) {
                $toCreate[] = $stockUnit;
                continue;
            }

            $stockUnitIds[] = $this->stockUnitService->update($stockUnit);
        }

        try {
            $created = $this->stockUnitService->create($toCreate, $product->getId());

            return array_merge($stockUnitIds, $created);
        } catch (Exceptions\SailsException $e) {
            $response = $e->getResponse();

            if ($response !== null && isset($response['codeError']) && $response['codeError'] === '0004') {
                foreach ($toCreate as $stockUnit) {
                    $stockUnitIds[] = $this->stockUnitService->updateBySku($stockUnit);
                }

                return $stockUnitIds;
            }

            throw $e;
        }
    }
}
