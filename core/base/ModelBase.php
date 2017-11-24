<?php

namespace CC\Core\Base;

use CC\Core\Main;
use Illuminate\Database\Eloquent\Model; //ORM基类
use Illuminate\Support\Facades\DB; //查询构造器
use Illuminate\Database\Capsule\Manager;


class ModelBase
{

    public static $db = null;
    public static $di = null;

    public function __construct()
    {
        self::$di = Main::getDI();
    }

    public static function getDb($name)
    {
        if (empty($name)) {
            throw new Exception('xxx');
        }
        return self::$di->get($name);
    }

    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
    }

    public function __call($name, $arguments)
    {
        return self::$di->get($name)($arguments);
    }
}
