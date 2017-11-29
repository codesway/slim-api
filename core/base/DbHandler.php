<?php

namespace CC\Core\Base;
use CC\Core\Main;
use Illuminate\Database\Eloquent\Model; //ORM基类
use Illuminate\Support\Facades\DB; //查询构造器
use Illuminate\Database\Capsule\Manager;

class DbHandler
{
    public static $apiBase = null;

    public static function init($apiBase)
    {
        if (empty(self::$apiBase)) {
            self::$apiBase = $apiBase;
        }
    }

    public static function getQueryBuilder($dbName)
    {
//        if (strpos(get_class(self::$apiBase), 'CC\Api') == 0) {
//            //可以考虑不让api层调用DB
//        }setDefaultConnection
        $dbMaster = self::$apiBase->di['DbMaster'];
        return $dbMaster->getConnection($dbName);
//        return $dbMaster;

//
//        print_r($con->table('l_line_category')->get()); exit();
//
//        print_r($dbMaster); exit();
//        \Illuminate\Database\Capsule\Manager::connection($dbName);
//
//        print_r($dbMaster); exit();

//        $db = self::$apiBase->di[DB_ALIAS . 'callcenter_' . $dbName];
//
//        ($db->getDatabaseManager())->setDefaultConnection('DB.callcenter_' . $dbName);

//        return $dbMaster;
//        return (self::$apiBase->di['DB.callcenter_' . $dbName]->getDatabaseManager())->setDefaultConnection('DB.callcenter_' . $dbName);
    }
}
