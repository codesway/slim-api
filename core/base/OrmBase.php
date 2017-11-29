<?php

namespace CC\Core\Base;

use Illuminate\Database\Eloquent\Model; //ORM基类
use Illuminate\Support\Facades\DB; //查询构造器
use Illuminate\Database\Capsule\Manager;

class OrmBase extends Model
{

    public static $apiBase = null;

    public static function init($apiBase)
    {
        if (empty(self::$apiBase)) {
            self::$apiBase = $apiBase;
        }
    }

    public function __construct()
    {
        $dbMaster = self::$apiBase->di['DbMaster'];
        if (!empty($this->connection) && !empty($this->table)) {
            $dbMaster::connection($this->connection)->table($this->table);
        }

        if (!empty($this->connection)) {
            $dbMaster::connection($this->connection);
        }

        parent::__construct();
    }
}
