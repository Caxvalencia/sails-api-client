<?php

namespace Sails\Api\Client\Entities;

use Sails\Api\Client\Exceptions\SailsException;
use Sails\Api\Client\Traits\ArrayItemToArray;

class Product
{
    use ArrayItemToArray;

    protected $id;

    /** @var string */
    protected $name;

    /** @var string */
    protected $shortDescription;

    /** @var string */
    protected $brand;

    /** @var string */
    protected $category;

    /** @var string */
    protected $imageUrl;

    /** @var boolean */
    protected $enable;

    /** @var string[] */
    protected $tags;

    /** @var boolean */
    protected $stockEnabled;

    /** @var ProductDeliveryTime */
    protected $deliveryTime;

    /** @var ProductProvider */
    protected $productProvider;

    /** @var ProductVariation[] */
    protected $variations;

    /** @var StockUnit[] */
    protected $stockUnits;

    /** @var ProductTax[] */
    protected $taxes;

    /** @var ProductVariationHelp[] */
    protected $variationHelps;

    /**
     * Product constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $shortDescription
     * @param string $category
     * @param string $imageUrl
     * @param bool   $stockEnabled
     * @param bool   $enable
     *
     * @throws SailsException
     */
    public function __construct(
        $id,
        $name,
        $shortDescription,
        $category,
        $imageUrl,
        $stockEnabled = true,
        $enable = true
    ) {
        $this->id = $id;
        $this->setName($name);
        $this->setShortDescription($shortDescription);
        $this->setCategory($category);
        $this->setImageUrl($imageUrl);
        $this->setEnable($enable);
        $this->setStockEnabled($stockEnabled);
        $this->variations = [];
        $this->stockUnits = [];
        $this->taxes = [];
        $this->variationHelps = [];

        $this->validate();
    }

    /**
     * @param array $data
     *
     * @return Product
     * @throws SailsException
     */
    public static function fromArray(array $data)
    {
        $product = new self(
            isset($data['id']) ? $data['id'] : null,
            $data['name'],
            $data['shortDescription'],
            $data['category'],
            $data['imageUrl'],
            $data['stockEnabled'],
            $data['enable']
        );

        $product->setProductProvider($data['productProvider']);
        $product->setStockUnits($data['stockUnits']);

        $product->setBrand(isset($data['brand']) ? $data['brand'] : '');
        $product->setTags(isset($data['tags']) ? $data['tags'] : []);

        if (isset($data['deliveryTime'])) {
            $product->setDeliveryTime($data['deliveryTime']);
        }

        if (isset($data['variations'])) {
            $product->setVariations($data['variations']);
        }

        if (isset($data['taxes'])) {
            $product->setTaxes($data['taxes']);
        }

        if (isset($data['variationHelps'])) {
            $product->setVariationHelps($data['variationHelps']);
        }

        return $product;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $productArray = [
            'name' => $this->name,
            'shortDescription' => $this->shortDescription,
            'brand' => $this->brand,
            'category' => $this->category,
            'imageUrl' => $this->imageUrl,
            'enable' => $this->enable,
            'tags' => $this->tags,
            'stockEnabled' => $this->stockEnabled,
            'deliveryTime' => $this->deliveryTime ? $this->deliveryTime->toArray() : null,
            'productProvider' => $this->productProvider->toArray(),
            'variations' => $this->getVariationsLikeArray(),
            'stockUnits' => $this->getStockUnitsLikeArray(),
            'taxes' => $this->getTaxesLikeArray(),
            'variationHelps' => $this->getVariationHelpsLikeArray(),
        ];

        if (empty($productArray['brand'])) {
            unset($productArray['brand']);
        }

        return $productArray;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param $shortDescription
     *
     * @return $this
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param string $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @param bool $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }

    /**
     * @param bool $stockEnabled
     */
    public function setStockEnabled($stockEnabled)
    {
        $this->stockEnabled = $stockEnabled;
    }

    /**
     * @param string[] $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param ProductDeliveryTime $deliveryTime
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;
    }

    /**
     * @param ProductProvider $productProvider
     */
    public function setProductProvider($productProvider)
    {
        $this->productProvider = $productProvider;
    }

    /**
     * @param ProductVariation[] $variations
     */
    public function setVariations(array $variations)
    {
        $this->variations = $variations;
    }

    /**
     * @param StockUnit[] $stockUnits
     */
    public function setStockUnits(array $stockUnits)
    {
        $this->stockUnits = $stockUnits;
    }

    /**
     * @param ProductTax[] $taxes
     */
    public function setTaxes(array $taxes)
    {
        $this->taxes = $taxes;
    }

    /**
     * @param ProductVariationHelp[] $variationHelps
     */
    public function setVariationHelps(array $variationHelps)
    {
        $this->variationHelps = $variationHelps;
    }

    /**
     * @param ProductVariation $variation
     */
    public function addVariation($variation)
    {
        $this->variations[] = $variation;
    }

    /**
     * @param StockUnit $stockUnit
     */
    public function addStockUnit($stockUnit)
    {
        $this->stockUnits[] = $stockUnit;
    }

    /**
     * @param ProductVariationHelp $variationHelp
     */
    public function addVariationHelp($variationHelp)
    {
        $this->variationHelps[] = $variationHelp;
    }

    /**
     * @return string|integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return StockUnit[]
     */
    public function getStockUnits()
    {
        return $this->stockUnits;
    }

    /**
     * @return array
     */
    public function getVariationsLikeArray()
    {
        return array_map([$this, 'arrayItemToArray'], $this->variations);
    }

    /**
     * @return array
     */
    public function getStockUnitsLikeArray()
    {
        return array_map([$this, 'arrayItemToArray'], $this->stockUnits);
    }

    /**
     * @return array
     */
    public function getTaxesLikeArray()
    {
        return array_map([$this, 'arrayItemToArray'], $this->taxes);
    }

    /**
     * @return array
     */
    public function getVariationHelpsLikeArray()
    {
        return array_map([$this, 'arrayItemToArray'], $this->variationHelps);
    }

    /**
     * @throws SailsException
     */
    private function validate()
    {
        $required = [];
        $max = [];

        if (empty($this->name)) {
            $required[] = 'name';
        }

        if (strlen($this->name) > 50) {
            $max[] = ['name', 70];
        }

        if (empty($this->shortDescription)) {
            $required[] = 'short description';
        }

        if (strlen($this->shortDescription) > 100) {
            $max[] = ['short description', 100];
        }

        if (!filter_var($this->imageUrl, FILTER_VALIDATE_URL)) {
            $required[] = 'image url';
        }

        if (count($required) > 0 || count($max) > 0) {
            throw SailsException::initFieldException([
                'required' => $required,
                'max' => $max,
            ]);
        }
    }
}
