<?php

namespace Sails\Api\Client\Entities;

class ProductTax
{
    /** @var string */
    public $code;

    /** @var integer */
    public $percentage;

    /**
     * ProductTax constructor.
     *
     * @param $code
     * @param $percentage
     */
    public function __construct($code, $percentage)
    {
        $this->code = $code;
        $this->percentage = $percentage;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'code' => $this->code,
            'percentage' => $this->percentage,
        ];
    }
}
