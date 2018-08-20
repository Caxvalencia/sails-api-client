<?php

namespace Sails\Api\Client\Entities;

use Sails\Api\Client\Exceptions\SailsException;

class ProductDeliveryTime
{
    /** @var string */
    public $minValue;

    /** @var string */
    public $maxValue;

    /**
     * ProductDeliveryTime constructor.
     *
     * @param integer $minValue
     * @param integer $maxValue
     *
     * @throws SailsException
     */
    public function __construct($minValue, $maxValue)
    {
        $this->minValue = intval($minValue);
        $this->maxValue = intval($maxValue);

        if ($this->minValue < 0 || $this->maxValue < 0) {
            throw SailsException::instance('Los tiempos de entrega deben ser mayores a 0');
        }

        if ($this->minValue > $this->maxValue) {
            throw SailsException::instance('El tiempo mínimo no puede ser mayor al tiempo máximo');
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'minValue' => $this->minValue,
            'maxValue' => $this->maxValue,
        ];
    }
}
