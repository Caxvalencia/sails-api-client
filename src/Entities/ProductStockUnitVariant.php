<?php

namespace Sails\Api\Client\Entities;

class ProductStockUnitVariant
{
    /** @var string */
    public $name;

    /** @var string */
    public $value;

    /**
     * ProductStockUnitVariant constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
