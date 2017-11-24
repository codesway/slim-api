<?php

namespace CC\Core\Base;

use Illuminate\Database\Eloquent\Model; //ORM基类
use Illuminate\Support\Facades\DB; //查询构造器
use Illuminate\Database\Capsule\Manager;
class Database
{
    public static $db = null;
    public static $di = null;
    public static $instance = null;

    public static function getInstance($di, $db)
    {
        if (empty(self::$instance)) {
            self::$instance = new static($di, $db);
        }
        return self::$instance;
    }

    protected function __construct($di, $db)
    {
        self::$db = $db;
        self::$di = $di;
    }
}
