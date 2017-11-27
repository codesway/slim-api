<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/25
 * Time: 10:16
 */

namespace CC\Core\Base;
use CC\Core\Main;
class MiddlewareBase
{

    protected static $di = null;

    public static $setMap = [];

    public function __construct()
    {
        if (empty(self::$di)) {
            self::$di = Main::getDI();
        }
    }

    public function set($name, $value)
    {
        if (empty(self::$setMap[$name])) {
            self::$setMap[$name] = $value;
        }
    }

    public function get($name, $default = false)
    {
        if (!empty(self::$setMap[$name])) {
            return self::$setMap[$name];
        }
        return $default;
    }

    public function stop()
    {
        exit('stop');
    }
}