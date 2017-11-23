<?php
/*
** Created by lll.
** FileName: autoload.php
** Author: liulinliang@hunbasha.com
** Date: 2017/11/20
** Brief
*/


return [
    'CC\\Controller\\User\\' => [C_ROOT . 'user'],
    'CC\\Codebase\\Config\\' => [CODEBASE_ROOT . 'config'],
    'CC\\Codebase\\Api\\' => [CODEBASE_ROOT . 'api'],
    'CC\\Codebase\\Libs\\' => [CODEBASE_ROOT . 'libs'],
    'CC\\Codebase\\Module\\' => [CODEBASE_ROOT . 'module'],
    'CC\\Codebase\\Service\\' => [CODEBASE_ROOT . 'service'],
    'CC\\Codebase\\Middleware\\' => [CODEBASE_ROOT . 'middleware'],
    'CC\\Core\\Base\\' => [CORE_ROOT . 'base'],
];