<?php
/*
** Created by lll.
** FileName: UserController.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/
namespace CC\Api\User;

use CC\Core\Main;
use CC\Core\Base\ApiBase;
//use CC\Codebase\Model\User\UserModel;

class UserApi extends ApiBase
{

    public function _once()
    {
        return 'once==';
    }

    public function _begin()
    {
        return 'begin==';
    }

    public function listExecute()
    {
        return 'this is list page ' . PHP_EOL;
    }


    public function get()
    {
        return 'get==';
    }

    public function dddExecute()
    {
        // api luyou
//        print_r((new UserModel())->getUser());
//        print_r(Main::getDI('cc_city')); exit();
//        $this->app = Main::getApp(PHP_SAPI);
//        print_r(UserModel::All());
//        throw new \InvalidArgumentException('xxx');
//        throw new \Exception('exc');
//        echo $b;
//        fun();
//        $this->xxx();
        $str = $this->get();
        return $str . 'eeee==';
    }


    public function xxxExecute()
    {
        return __METHOD__ . "\r\n";
    }


    public function findExecute()
    {
        return 'xxx' . PHP_EOL;
    }

    public function _after()
    {
        return 'after==';
    }

}