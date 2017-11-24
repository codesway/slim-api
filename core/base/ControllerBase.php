<?php
/*
** Created by lll.
** FileName: BaseController.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/18
** Brief
*/
namespace CC\Core\Base;

use CC\Core\Main;
class ControllerBase
{
    public function __construct($di)
    {
        $this->di = $di;
//        $this->app = Main::getApp(PHP_SAPI);
//        print_r($this->container); exit();
    }


    protected function _handler()
    {
    }

    protected function _init()
    {
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}