<?php

namespace Sails\Api\Client\Entities\Responses;

class ProductResponse
{
    const CREATED = 'CreatedProduct';

    const UPDATED = 'UpdatedProduct';

    const DELETED = 'DeletedProduct';

    /** @var string */
    protected $productId;

    /** @var StockUnitId[] */
    protected $stockUnitIds;

    /** @var string */
    protected $typeAction;

    /**
     * CreatedProductResponse constructor.
     *
     * @param string $typeAction
     * @param string $productId
     * @param array  $stockUnitIds
     */
    public function __construct($typeAction, $productId, array $stockUnitIds = [])
    {
        $this->typeAction = $typeAction;
        $this->productId = $productId;
        $this->stockUnitIds = array_map([StockUnitId::class, 'fromArray'], $stockUnitIds);
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @return StockUnitId[]
     */
    public function getStockUnitIds()
    {
        return $this->stockUnitIds;
    }

    /**
     * @param $typeAction
     *
     * @return bool
     */
    public function isTypeAction($typeAction)
    {
        return $this->typeAction === $typeAction;
    }

    /**
     * @param $stockUnitIds
     *
     * @return $this
     */
    public function setStockUnitIds($stockUnitIds)
    {
        $this->stockUnitIds = $stockUnitIds;
    }
}
