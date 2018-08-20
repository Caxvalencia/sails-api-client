<?php

namespace Sails\Api\Client\Entities;

class ProductVariation
{
    /** @var string */
    public $name;

    /** @var string[] */
    public $value;

    /**
     * ProductVariant constructor.
     *
     * @param string   $name
     * @param string[] $value
     */
    public function __construct($name, array $value)
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
            'value' => $this->value
        ];
    }
}
