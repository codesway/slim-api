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
use CC\Core\Base\DbHandler;
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
        return [
            1 => 3,
        ];
        return 'this is list page ' . PHP_EOL;
    }


    public function getExecute()
    {
        return 'get==';
    }

    public function dddExecute()
    {

//        print_r(DbHandler::getQueryBuilder('callcenter_common')->table('c_call_evaluate')->get()); exit();

        //查询构造器就这么用
//        $obj = DbHandler::getQueryBuilder('callcenter_bj')->table('l_line_category')->get();
//        print_r($obj->toArray());
//        print_r($obj->toJson());
//        exit();

        // ORM 就这么调.
        $flights = \CC\Api\User\Orm\UserAnnexed::all();
        return $flights->toArray();
        foreach ($flights as $flight) {
            print_r($flight->cate_name); exit();
        }

        // 查询构造器和ORM的调用方式都按手册来调用就好
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