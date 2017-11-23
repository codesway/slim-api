<?php
/*
** Created by lll.
** FileName: UserController.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/
namespace CC\Controller\User;

use CC\Core\Base\BaseController;
class UserController extends BaseController
{

    public function listAction()
    {
        return 'this is list page ' . PHP_EOL;
    }

    public function dddAction()
    {
//        $this->app = Main::getApp(PHP_SAPI);
        return 'eeee' . PHP_EOL;
    }


    public function xxxAction()
    {
        return __METHOD__ . "\r\n";
    }
}