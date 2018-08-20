<?php

namespace Sails\Api\Client;

abstract class Config
{
    private static $config = [];

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get($key, $default = null)
    {
        return isset(self::$config[$key])
            ? self::$config[$key]
            : $default;
    }
}
