<?php

namespace Sails\Api\Client\Traits;

/**
 * Trait Singletonable
 * @package Sails\Api\Client\Traits
 */
trait Singletonable
{
    protected static $instance;

    final public static function getInstance(...$args)
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static(...$args);
    }

    final protected function __construct($args = null)
    {
    }

    final private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}
