<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/27
 * Time: 10:43
 */


namespace CC\Codebase;

class ConfigDefine
{
//    const ENV = [
//        'develop' => [
//            // .是命名空间 alarm
//            'alarm.member.liulinliang' => self::NAME_LIULINLIANG,
//            'alarm.member.jinzhiqiang' => self::NAME_JINZHIQIANG,
//            'alarm.member.lihang' => self::NAME_LIHANG,
//            'global.bj' => self::CITY_BJ,
//            'global.gz' => self::CITY_GZ,
//            'global.wh' => self::CITY_WH,
//            'global.tj' => self::CITY_TJ,
//            'global.hz' => self::CITY_HZ,
//            'global.dev' => self::CITY_DEV,
//            'database.'
//        ],
//        'local' => [
//            'alarm.member.liulinliang' => self::NAME_LIULINLIANG,
//            'alarm.member.jinzhiqiang' => self::NAME_JINZHIQIANG,
//            'alarm.member.lihang' => self::NAME_LIHANG,
//        ],
//        'online' => [
//            'alarm.member.liulinliang' => self::NAME_LIULINLIANG,
//            'alarm.member.jinzhiqiang' => self::NAME_JINZHIQIANG,
//            'alarm.member.lihang' => self::NAME_LIHANG,
//        ],
//    ];

    // username
    const NAME_LIULINLIANG = 'liulinliang';
    const NAME_JINZHIQIANG = 'jinzhiqiang';
    const NAME_LIHANG = 'lihang';

    // error level
    const ERR_LEVEL_WARN = 1;
    const ERR_LEVEL_FATAL = 1;
    //IP
    const IP_MYSQL_MASTER = '127.0.0.1';
    //City
    const CITY_BJ = 100900;
    const CITY_GZ = 440100;
    const CITY_WH = 420100;
    const CITY_TJ = 120300;
    const CITY_HZ = 330100;
    const CITY_DEV = 14100;

}