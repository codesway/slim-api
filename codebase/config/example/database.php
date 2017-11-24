<?php
/**
 * Created by PhpStorm.
 * User: CodeWay
 * Date: 2017/11/24
 * Time: 14:53
 */

return [
    'pool' => [
        'callcenter' => [
            'read' => [
                'host' => '127.0.0.1',
            ],
            'write' => [
                'host' => '127.0.0.1'
            ],
            'sticky'    => true,
            'driver'    => 'mysql',
            'database'  => 'demo',
            'username'  => 'root',
            'password'  => 'root',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]
    ],
    'dbs' => [
        'bj' => 'callcenter',
        'tj' => 'callcenter',
        'sh' => 'callcenter',
        'wh' => 'callcenter',
        'gz' => 'callcenter',
        'hz' => 'callcenter',
        'common' => 'callcenter',
        //7个库，1个实例
    ],
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