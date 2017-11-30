<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/24
 * Time: 14:53
 */

return [
    'pool' => [
        'cc1' => [
            'read' => [
                'host' => '192.168.4.41',
            ],
            'write' => [
                'host' => '192.168.4.41'
            ],
            'sticky'    => true,
            'driver'    => 'mysql',
            'username'  => 'root',
            'password'  => '30043943',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
            'port' => 12306,
        ],
    ],
    'dbs' => [
        //真实的db name
        'callcenter_bj' => 'cc1',
        'callcenter_tj' => 'cc1',
        'callcenter_sh' => 'cc1',
        'callcenter_wh' => 'cc1',
        'callcenter_gz' => 'cc1',
        'callcenter_hz' => 'cc1',
        'callcenter_common' => 'cc1',
        //7个库，1个实例
    ],

    'db_prefix' => 'callcenter',
];
//
//
//return [
//    'determineRouteBeforeAppMiddleware' => false,
//    'displayErrorDetails' => true,
//    'db' => [
//        'driver' => 'mysql',
//        'host' => 'localhost',
//        'database' => 'database',
//        'username' => 'user',
//        'password' => 'password',
//        'charset'   => 'utf8',
//        'collation' => 'utf8_unicode_ci',
//        'prefix'    => '',
//    ]
//];