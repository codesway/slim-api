<?php

namespace CC\Core\Base;
class BaseModel
{

//    protected static $_instance = null;
//
//    public static function getInstance($di) {
//        if (!(self::$_instance instanceof self)) {
//            self::$_instance = new static($di);
//        }
//        return self::$_instance;
//    }
//
//    protected function __construct($di) {
//        $this->_masterDbHandler = $di->get('travelDB');
//        $this->_slaveDbHandler = $di->get('travelDB');
//    }
//

    public function __construct($di)
    {
        $this->di = $di;
    }


}