<?php
//
//class start
//{
//
//    public $tip = null;
//
//    public function __construct()
//    {
//        $this->tip = function () {
//            call_user_func([$this, 'do']);
//        };
//    }
//
//    public function __invoke()
//    {
//        $call = $this->tip;
//        $call();
//        // TODO: Implement __invoke() method.
//    }
//
//
//    public function do()
//    {
//        echo  'ccccc';
//    }
//}
//
//$a = new start();
//$a();
//exit('xxx');
//main


$a = function ($c, $d) {

};

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__) . DS);
define('CORE_ROOT', ROOT . 'core' . DS);
include CORE_ROOT . 'Main.php';
$app = CC\Core\Main::getApp(PHP_SAPI);
$app->run();



