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