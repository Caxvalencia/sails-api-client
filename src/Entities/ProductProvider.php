<?php

namespace Sails\Api\Client\Entities;

class ProductProvider
{
    /** @var string */
    public $name;

    /** @var string */
    public $providerId;

    /**
     * ProductProvider constructor.
     *
     * @param string   $name
     * @param string $providerId
     */
    public function __construct($name, $providerId)
    {
        $this->name = $name;
        $this->providerId = $providerId;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->name,
            'providerId' => $this->providerId
        ];
    }
}
