<?php
/*
** Created by lll.
** FileName: global.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/23
** Brief
*/


return [
    'cities' => [
        'bj'=>110900,
        'sh'=>310900,
        'gz'=>440100,
        'wh'=>420100,
        'tj'=>120300,
        'hz'=>330100,
        'dev'=>141000,
    ],
    'sms'=>array(
        'use' => 'mengwang',
        'mengwang' => array(
            'server_url' => 'http://211.100.34.81:897/MWGate/wmgw.asmx',
            'user_name' => defined('MW_UNAME') ? MW_UNAME : '',
            'password' => '123456',
            'port' => '*',
            'encoding'=>'UTF-8'
        ),
        'mengwang_hz_my' => array(
            'server_url' => 'http://211.100.34.81:897/MWGate/wmgw.asmx',
            'user_name' => 'HZ0Y03',
            'password' => '123456',
            'port' => '*',
            'encoding'=>'UTF-8',
        ),
        'mengwang_bj_my' => array(
            'server_url' => 'http://211.100.34.81:897/MWGate/wmgw.asmx',
            'user_name' => 'BJ0Y03',
            'password' => '123456',
            'port' => '*',
            'encoding'=>'UTF-8',
        ),
        'mengwang_gz_my' => array(
            'server_url' => 'http://211.100.34.81:897/MWGate/wmgw.asmx',
            'user_name' => 'GZ0Y03',
            'password' => '123456',
            'port' => '*',
            'encoding'=>'UTF-8',
        ),
    ),
];