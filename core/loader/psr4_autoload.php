<?php
/*
** Created by lll.
** FileName: autoload.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/


return [
    // codebase
    'CC\\Codebase\\Config\\' => [CODEBASE_ROOT . 'config'],
    'CC\\Codebase\\Api\\' => [CODEBASE_ROOT . 'api'],
    'CC\\Codebase\\Libs\\' => [CODEBASE_ROOT . 'libs'],
    'CC\\Codebase\\Model\\' => [CODEBASE_ROOT . 'model'],
    'CC\\Codebase\\Model\\User\\' => [CODEBASE_ROOT . 'model' . DS . 'user'],
    'CC\\Codebase\\Service\\' => [CODEBASE_ROOT . 'service'],
    'CC\\Codebase\\Middleware\\' => [CODEBASE_ROOT . 'middleware'],
    //core
    'CC\\Core\\Base\\' => [CORE_ROOT . 'base'],
    'CC\\Core\\Exception\\' => [CORE_ROOT . 'exception'],
    'CC\\Core\\Handler\\' => [CORE_ROOT . 'handler'],
    //module
    'CC\\Api\\User\\' => [A_ROOT . 'user'],
    'CC\\Api\\User\\Orm\\' => [A_ROOT . 'user' . DS . 'orm'],


    'CC\\Api\\Error\\' => [A_ROOT . 'error'],
    'CC\\Api\\Error\\Orm\\' => [A_ROOT . 'error' . DS . 'orm'],
];
