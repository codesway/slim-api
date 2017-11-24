<?php
/*
** Created by lll.
** FileName: UserController.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/
namespace CC\Controller\User;

use CC\Core\Main;
use CC\Core\Base\ControllerBase;
use CC\Codebase\Model\User\UserModel;

class UserController extends ControllerBase
{

    public function listAction()
    {
        return 'this is list page ' . PHP_EOL;
    }

    public function dddAction()
    {
        // api luyou

        print_r((new UserModel())->getUser());
//        print_r(Main::getDI('cc_city')); exit();
//        $this->app = Main::getApp(PHP_SAPI);
//        print_r(UserModel::All());
        return 'eeee' . PHP_EOL;
    }


    public function xxxAction()
    {
        return __METHOD__ . "\r\n";
    }


    public function findAction()
    {
        return 'xxx' . PHP_EOL;
    }

}