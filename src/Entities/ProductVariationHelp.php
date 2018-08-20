<?php

namespace Sails\Api\Client\Entities;

class ProductVariationHelp
{
    /** @var string */
    public $url;

    /** @var string */
    public $label;

    /**
     * ProductVariationHelp constructor.
     *
     * @param $url
     * @param $label
     */
    public function __construct($url, $label)
    {
        $this->url = $url;
        $this->label = $label;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'url' => $this->url,
            'label' => $this->label,
        ];
    }
}
