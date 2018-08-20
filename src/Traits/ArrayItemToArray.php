<?php

namespace Sails\Api\Client\Traits;

trait ArrayItemToArray
{
    /**
     * @param mixed $item
     *
     * @return mixed
     */
    public function arrayItemToArray($item)
    {
        return $item->toArray();
    }
}
