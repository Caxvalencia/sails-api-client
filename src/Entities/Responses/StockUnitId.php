<?php

namespace Sails\Api\Client\Entities\Responses;

class StockUnitId
{
    /** @var string */
    protected $stockUnitId;

    /** @var string */
    protected $sku;

    /**
     * StockUnitId constructor.
     *
     * @param string $stockUnitId
     * @param string $sku
     */
    public function __construct($stockUnitId, $sku)
    {
        $this->stockUnitId = $stockUnitId;
        $this->sku = $sku;
    }

    /**
     * @param array $stockUnitId
     *
     * @return StockUnitId
     */
    public static function fromArray(array $stockUnitId)
    {
        return new self($stockUnitId['stockUnitId'], $stockUnitId['sku']);
    }

    /**
     * @return string
     */
    public function getStockUnitId()
    {
        return $this->stockUnitId;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }
}
