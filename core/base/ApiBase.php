<?php
/*
** Created by lll.
** FileName: BaseApi.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/18
** Brief
*/
namespace CC\Core\Base;

class ApiBase
{

    public function __construct($di)
    {
        $this->di = $di;
        if (!isset($this->onceExecuted)) {
            $this->onceExecuted = null;
        }
        if (method_exists($this, '_once')) {
            $this->onceExecuted = false;
        }
    }


    //路由中实现这三个方法
//    public function once()
//    {
//
//    }
//
//    public function _begin()
//    {
//
//    }
//
//    public function _after()
//    {
//
//    }

    protected function _handler()
    {
    }

    protected function _init()
    {
    }

    public function __destruct()
    {
    }
}