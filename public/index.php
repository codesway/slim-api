<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . DS);
define('CORE_ROOT', ROOT . 'core' . DS);
include CORE_ROOT . 'Main.php';
$app = CC\Core\Main::getApp(PHP_SAPI, $argv ?? []);
$app->run();
