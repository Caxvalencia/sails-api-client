<?php

namespace Sails\Api\Client\Entities;

use Sails\Api\Client\Exceptions\SailsException;
use Sails\Api\Client\Traits\ArrayItemToArray;

class StockUnit
{
    use ArrayItemToArray;

    /** @var string */
    protected $id;

    /** @var string */
    protected $sku;

    /** @var integer */
    protected $quantity;

    /** @var integer */
    protected $price;

    /** @var integer */
    protected $comparePrice;

    /** @var string */
    protected $imageUrl;

    /** @var string[] */
    protected $tags;

    /** @var ProductStockUnitVariant[] */
    protected $variants;

    /**
     * ProductStockUnit constructor.
     *
     * @param string  $id
     * @param string  $sku
     * @param integer $quantity
     * @param integer $price
     * @param string  $imageUrl
     *
     * @throws SailsException
     */
    public function __construct($id, $sku, $quantity, $price, $imageUrl)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->quantity = intval($quantity);
        $this->price = floatval($price);
        $this->comparePrice = 0;
        $this->imageUrl = $imageUrl;
        $this->variants = [];

        $this->validate();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'sku' => $this->sku,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'imageUrl' => $this->imageUrl,
            'comparePrice' => $this->comparePrice,
            'tags' => $this->tags,
            'variant' => $this->getVariantsLikeArray()
        ];
    }

    /**
     * @param int $comparePrice
     */
    public function setComparePrice($comparePrice)
    {
        $this->comparePrice = floatval($comparePrice);
    }

    /**
     * @param string[] $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param ProductStockUnitVariant $variant
     */
    public function addVariant($variant)
    {
        $this->variants[] = $variant;
    }

    /**
     * @return array
     */
    public function getVariantsLikeArray()
    {
        return array_map([$this, 'arrayItemToArray'], $this->variants);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @return int
     */
    public function getComparePrice()
    {
        return $this->comparePrice;
    }

    /**
     * @throws SailsException
     */
    private function validate()
    {
        $required = [];

        if (empty($this->sku)) {
            $required[] = 'sku';
        }

        if (empty($this->price)) {
            $required[] = 'price';
        }

        if (count($required) > 0) {
            throw SailsException::initFieldException([
                'required' => $required,
            ]);
        }
    }
}
